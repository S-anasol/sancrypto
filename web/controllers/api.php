<?php
	
	Class Controller_Api Extends Controller_Base
	{
		public function index()
		{
		}
		
		public function xml()
		{
			global $get;
			header("Content-Type: text/xml");
			$response = $this->api();
			$xml = new SimpleXMLElement('<response/>');
			$this->array_to_xml($response,$xml);
			echo $xml->asXML();
		}
		
		public function json()
		{
			global $get;
			$response = $this->api();
			echo json_encode($response);
			die();
		}
		
		function api()
		{
			global $bcMath,$memcache_obj, $get;
			$response = array();
			$highest_key = 'none';
			$highest_value = $highest_avg = 0;
			$curl = new Curl();
			/**/$cache_file = PATH_CACHE.'ltc_difficulty.cache';
			$cache = $memcache_obj->get($cache_file);
			if ($cache['time'] < (time() - 60 * 5 )) 
			{
				$curl->get('http://ws.ltcchain.com/latestblock');
				if(!$curl->error)
				{
					$ltcdiff = json_decode($curl->response);
					if(!empty($ltcdiff->difficulty))
					{
						$ltcdiff = $ltcdiff->difficulty;
					}
				}
				$cache = array('time'=> time(), 'info' => $ltcdiff);
				$memcache_obj->set($cache_file, $cache, MEMCACHE_COMPRESSED , 0);
			}
			$ltcdiff = $cache['info'];
			$var1 = $bcMath->multiplication($ltcdiff,pow(2, 32));
			$var2 = $bcMath->division($var1,1000*1000);
			$ltc_per_day = $bcMath->division(3600 * 24 * 50,$var2);
			
			if(!empty($get[0]) && $get[0] >= 5)
			{
				$interval = (int)$get[0];
			}
			else
			{
				$interval = 30;
			}
			
			$profits = Profit::find('all',array('select'=>'avg(percent) as profit, name, short, crypto_id', 'joins'=>array('crypto'), 'conditions'=>array('cryptos.status = 1 and time >= unix_timestamp(DATE_SUB(NOW(),INTERVAL '.$interval.' MINUTE))'), 'group' => 'crypto_id'));
			//var_dump($profits);
			foreach($profits as $profit)
			{
				$crypto = Crypto::find($profit->crypto_id);
				$var1 = $bcMath->multiplication($crypto->diff(),pow(2, 32));
				$var2 = $bcMath->division($var1,1000*1000);
				$crypto_per_day = $bcMath->division(3600 * 24 * $crypto->cpb,$var2);
				$btc_per_day = $bcMath->multiplication($crypto_per_day,$crypto->btc());
				$ltc_in_crypto_per_day = $bcMath->division($btc_per_day,$crypto->btcltc());
				$current = $bcMath->rounding($bcMath->division($ltc_in_crypto_per_day * 100, $ltc_per_day));
				if ($profit->profit > $highest_avg && $current > 100) {
					//var_dump($profit->crypto);
					$highest_key = $profit;
					$highest_avg = round($profit->profit);
					$highest_value = $current;
				}
				$response['list'][$profit->short] = array('current' => $current, 'average' => round($profit->profit));
			}
			
			$response['mine_it']['name'] = $highest_key->name;
			$response['mine_it']['short'] = $highest_key->short;
			$response['mine_it']['current'] = $highest_value;
			$response['mine_it']['average'] = $highest_avg;
			$response['interval'] = $interval;
			return $response;
		}
		
		function array_to_xml($array, &$xml) 
		{
			foreach($array as $key => $value) 
			{
				if(is_array($value)) 
				{
					if(!is_numeric($key))
					{
						$subnode = $xml->addChild($key);
						$this->array_to_xml($value, $subnode);
					}
					else 
					{
						$this->array_to_xml($value, $xml);
					}
				}
				else 
				{
					$xml->addChild($key,$value);
				}
			}
		}
	}
?>