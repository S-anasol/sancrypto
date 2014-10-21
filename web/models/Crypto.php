<?php
	class Crypto extends ActiveRecord\Model
	{
		//static $has_many = array(
		//array('profits','conditions' => 'time >= UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL 1 HOUR))'), array('prices'));
		
		public function getblockcount()
		{
			global $memcache_obj;
			$cache_file = PATH_CACHE.$this->id.'_getblockcount.cache';
			
			$cache = $memcache_obj->get($cache_file);
			if ($cache['time'] < (time() - 60 * 5 ) || $cache['info'] == 0) 
			{
				$curl = new Curl();
				$info = 0;
				$curl->get($this->explorer.'/q/getblockcount');
				if(!$curl->error && strlen($curl->response) < 30)
				{
					$info = (int)$curl->response;
				}
				$cache = array('time'=> time(), 'info' => $info);
				$memcache_obj->set($cache_file, $cache, MEMCACHE_COMPRESSED , 0);
			}
			return number_format($cache['info']);
		}
		
		
		function totalbc()
		{
			global $memcache_obj;
			$curl = new Curl();
			$info = 0;
			$cache_file = PATH_CACHE.$this->id.'_totalbc.cache';
			
			$cache = $memcache_obj->get($cache_file);
			if ($cache['time'] < (time() - 60 * 5 ) || $cache['info'] == 0) 
			{
				$curl = new Curl();
				$curl->get($this->explorer.'/q/totalbc');
				if(!$curl->error && strlen($curl->response) < 30)
				{
					$info = (int)$curl->response;
				}
				$cache = array('time'=> time(), 'info' => $info);
				$memcache_obj->set($cache_file, $cache, MEMCACHE_COMPRESSED , 0);
			}
			return number_format($cache['info']);
		}
		
		function diff()
		{
			global $memcache_obj;
			$curl = new Curl();
			$info = 0;
			$cache_file = PATH_CACHE.$this->id.'_getdifficulty.cache';
			
			$cache = $memcache_obj->get($cache_file);
			if ($cache['time'] < (time() - 60 * 5 ) || $cache['info'] == 0) 
			{
				$curl = new Curl();
				$curl->get($this->explorer.'/q/getdifficulty');
				if(!$curl->error && strlen($curl->response) < 30)
				{
					$info = number_format($curl->response,2,".","");
				}
				$cache = array('time'=> time(), 'info' => $info);
				$memcache_obj->set($cache_file, $cache, MEMCACHE_COMPRESSED , 0);
			}
			return $cache['info'];
		}
		
		function btcltc()
		{
			global $memcache_obj;
			$cache_file = PATH_CACHE.'btcltc.cache';
			
			$cache = $memcache_obj->get($cache_file);
			if ($cache['time'] < (time() - 60 * 5 )) 
			{
				$curl = new Curl();
				$info = 0;
				$curl->get('https://btc-e.com/api/2/ltc_btc/ticker');
				if(!$curl->error)
				{
					$info = json_decode($curl->response);
					if(!empty($info->ticker->avg))
					{
						$info = $info->ticker->avg;
					}
				}
				$cache = array('time'=> time(), 'info' => number_format($info,8,'.',''));
				$memcache_obj->set($cache_file, $cache, MEMCACHE_COMPRESSED , 0);
			}
			return $cache['info'];
		}
		
		
		function chart()
		{
			global $memcache_obj;
			$cache_file = PATH_CACHE.$this->id.'_chart.cache';
			$cache = $memcache_obj->get($cache_file);
			//echo "13";
			if ($cache['time'] < (time() - 60*10 ) || empty($cache['info'])) 
			{
				$info = '';
				$curl = new Curl();
				$curl->get($this->explorer.'/q/nethash');
				if(!$curl->error)
				{
					ob_start();
					$opop = $curl->response;
					$opop = explode('START DATA',$opop);
					$opop2 = $opop[1];
					$opop3 = explode("\n",$opop2);
					$opop = explode(',',$opop2);
					$blblbb = $blblbb2 = array();
					
					foreach($opop3 as $opop)
					{
						if(strlen($opop) > 5)
						{
							$opop = explode(',',$opop);
							$blblbb[] = "[{$opop[1]}000,{$opop[4]}]";
							$opop[7] = round($opop[7]/1024/1024);
							$blblbb2[] = "[{$opop[1]}000,{$opop[7]}]";
						}
					}
				?>Highcharts.theme = {
				colors: ["#DDDF0D", "#7798BF", "#55BF3B", "#DF5353", "#aaeeee", "#ff0066", "#eeaaee", "#55BF3B", "#DF5353", "#7798BF", "#aaeeee"],
				chart: {
				animation: true,
				zoomType: "x",
				backgroundColor: {
				linearGradient: {
                x1: 0,
                y1: 0,
                x2: 0,
                y2: 1
				},
				stops: [
                [0, "rgb(18, 18, 18)"],
                [1, "rgb(18, 18, 18)"]
				]
				},
				borderWidth: 1,
				borderColor: "rgb(28, 28, 28)",
				borderRadius: 15,
				plotBackgroundColor: null,
				plotShadow: false,
				plotBorderWidth: 0
				},
				title: {
				style: {
				color: "#FFF",
				font: "16px Lucida Grande, Lucida Sans Unicode, Verdana, Arial, Helvetica, sans-serif"
				}
				},
				subtitle: {
				style: {
				color: "#DDD",
				font: "12px Lucida Grande, Lucida Sans Unicode, Verdana, Arial, Helvetica, sans-serif"
				}
				},
				xAxis: {
				gridLineWidth: 0,
				lineColor: "#999",
				tickColor: "#999",
				labels: {
				style: {
                color: "#999",
                fontWeight: "bold"
				}
				},
				title: {
				style: {
                color: "#AAA",
                font: "bold 12px Lucida Grande, Lucida Sans Unicode, Verdana, Arial, Helvetica, sans-serif"
				}
				}
				},
				yAxis: {
				alternateGridColor: null,
				minorTickInterval: null,
				gridLineColor: "rgba(255, 255, 255, .1)",
				minorGridLineColor: "rgba(255,255,255,0.07)",
				lineWidth: 0,
				tickWidth: 0,
				labels: {
				style: {
                color: "#999",
                fontWeight: "bold"
				}
				},
				title: {
				style: {
                color: "#AAA",
                font: "bold 12px Lucida Grande, Lucida Sans Unicode, Verdana, Arial, Helvetica, sans-serif"
				}
				}
				},
				legend: {
				itemStyle: {
				color: "#CCC"
				},
				itemHoverStyle: {
				color: "#FFF"
				},
				itemHiddenStyle: {
				color: "#333"
				}
				},
				labels: {
				style: {
				color: "#CCC"
				}
				},
				tooltip: {
				backgroundColor: {
				linearGradient: {
                x1: 0,
                y1: 0,
                x2: 0,
                y2: 1
				},
				stops: [
                [0, "rgba(18, 18, 18, .8)"],
                [1, "rgba(18, 18, 18, .8)"]
				]
				},
				borderWidth: 0,
				style: {
				color: "#FFF"
				},
				shared: true,
				animation: false
				},
				plotOptions: {
				animation: false,
				series: {
				shadow: true
				},
				line: {
				dataLabels: {
                color: "#CCC"
				},
				marker: {
                lineColor: "#333",
                enabled: false
				}
				},
				spline: {
				marker: {
                lineColor: "#333"
				}
				},
				scatter: {
				marker: {
                lineColor: "#333"
				}
				},
				candlestick: {
				lineColor: "white"
				}
				},
				toolbar: {
				itemStyle: {
				color: "#CCC"
				}
				},
				navigation: {
				buttonOptions: {
				symbolStroke: "#181818",
				hoverSymbolStroke: "#181818",
				theme: {
                fill: {
				linearGradient: {
				x1: 0,
				y1: 0,
				x2: 0,
				y2: 1
				},
				stops: [
				[0.4, "#181818"],
				[0.6, "#181818"]
				]
                },
                stroke: "#181818"
				}
				}
				},
				rangeSelector: {
				buttonTheme: {
				fill: {
                linearGradient: {
				x1: 0,
				y1: 0,
				x2: 0,
				y2: 1
                },
                stops: [
				[0.4, "#181818"],
				[0.6, "#181818"]
                ]
				},
				stroke: "#181818",
				style: {
                color: "#CCC",
                fontWeight: "bold"
				},
				states: {
                hover: {
				fill: {
				linearGradient: {
				x1: 0,
				y1: 0,
				x2: 0,
				y2: 1
				},
				stops: [
				[0.4, "#181818"],
				[0.6, "#181818"]
				]
				},
				stroke: "#181818",
				style: {
				color: "white"
				}
                },
                select: {
				fill: {
				linearGradient: {
				x1: 0,
				y1: 0,
				x2: 0,
				y2: 1
				},
				stops: [
				[0.1, "#181818"],
				[0.3, "#181818"]
				]
				},
				stroke: "#181818",
				style: {
				color: "yellow"
				}
                }
				}
				},
				inputStyle: {
				backgroundColor: "#181818",
				color: "silver"
				},
				labelStyle: {
				color: "silver"
				}
				},
				navigator: {
				handles: {
				backgroundColor: "#181818",
				borderColor: "#282828"
				},
				outlineColor: "#CCC",
				maskFill: "rgba(18, 18, 18, 0.5)",
				series: {
				color: "#7798BF",
				lineColor: "#A6C7ED"
				}
				},
				scrollbar: {
				barBackgroundColor: {
				linearGradient: {
                x1: 0,
                y1: 0,
                x2: 0,
                y2: 1
				},
				stops: [
                [0.4, "#181818"],
                [0.6, "#181818"]
				]
				},
				barBorderColor: "#CCC",
				buttonArrowColor: "#CCC",
				buttonBackgroundColor: {
				linearGradient: {
                x1: 0,
                y1: 0,
                x2: 0,
                y2: 1
				},
				stops: [
                [0.4, "#181818"],
                [0.6, "#181818"]
				]
				},
				buttonBorderColor: "#181818",
				rifleColor: "#FFF",
				trackBackgroundColor: {
				linearGradient: {
                x1: 0,
                y1: 0,
                x2: 0,
                y2: 1
				},
				stops: [
                [0, "#181818"],
                [1, "#181818"]
				]
				},
				trackBorderColor: "#181818"
				},
				legendBackgroundColor: "rgba(18, 18, 18, 0.8)",
				legendBackgroundColorSolid: "rgb(18, 18, 18)",
				dataLabelsColor: "#444",
				textColor: "#E0E0E0",
				maskColor: "rgba(255,255,255,0.3)"
				};
				Highcharts.setOptions(Highcharts.theme);
				$("#container").highcharts('StockChart', {
				title: {
				text: "<?=$this->name?> Chart"
				},
				xAxis: {
				title: {
				text: "Block #"
				}
				},
				yAxis: [{
				labels: {
				formatter: function () {
                return this.value
				}
				},
				allowDecimals: true,
				title: {
				text: "Difficulty"
				},
				opposite: true
				}, {
				gridLineWidth: 0,
				title: {
				text: "Network Hashrate"
				},
				labels: {
				formatter: function () {
                return this.value + " MH/s"
				}
				}
				}],
				tooltip: {},
				series: [{
				name: "Difficulty",
				yAxis: 0,
				data: [ <?php echo implode(',', $blblbb); ?> ]
				}, {
				name: "Network HashRate",
				yAxis: 1,
				data: [ <?php echo implode(',', $blblbb2); ?> ]
				}]
				});<?php
					
					$info = ob_get_contents();
					ob_end_clean();
				}
				else
				{
					$info = $curl->error_message;
				}
				$cache = array('time'=> time(), 'info' => $info);
				$memcache_obj->set($cache_file, $cache, MEMCACHE_COMPRESSED , 0);
			}
			return $cache['info'];
		}
		
		public function btc()
		{
			global $memcache_obj;
			$cache_file = PATH_CACHE.$this->id.'_exchange_rate.cache';
			$cache = $memcache_obj->get($cache_file);
			
			if ($cache['time'] < (time() - 60*5 ) || $cache['info'] == '0.00000000' || $cache['info'] == 0) 
			{
				$info = 0;
				$curl = new Curl();
				switch($this->exchange)
				{
					case 'poloniex':
					$curl->get('https://poloniex.com/ticker.txt');
					if(!$curl->error)
					{
						$info = json_decode($curl->response);
						$name = 'BTC_'.$this->exid;
						if(!empty($info->{$name}) || $info->{$name} == 0)
						{
							$info = number_format($info->{$name},8,".","");
						}
					}
					break;
					
					case 'cryptsy':
					$curl->get('http://pubapi.cryptsy.com/api.php?method=singleorderdata&marketid='.$this->exid);
					if(!$curl->error)
					{
						$info = json_decode($curl->response);
						if(!empty($info->return->{$this->short}->buyorders[0]->price) || $info->return->{$this->short}->buyorders[0]->price == 0)
						{
							$info = number_format($info->return->{$this->short}->buyorders[0]->price,8,".","");
						}
					}
					break;
					
					case 'cryptorush':
					$curl->get("https://cryptorush.in/index.php?p=trading&m={$this->short}&b=BTC");
					if(!$curl->error)
					{
						preg_match('/sellOrder\(0\);"><td>(.*?)</',$curl->response,$matches);
						if(!empty($matches[1]))
						{
							$info = $matches[1];
						}
					}
					break;
					
					case 'mintpal':
					$curl->get("https://api.mintpal.com/v1/market/stats/{$this->short}/BTC");
					if(!$curl->error)
					{
						$info = json_decode($curl->response);
						if(!empty($info[0]->last_price) || $info[0]->last_price == 0)
						{
							$info = number_format($info[0]->last_price,8,".","");
						}
					}
					//var_dump($curl->error_message);
					break;
					
					case 'bter':
					$curl->get("https://data.bter.com/api/1/ticker/{$this->short}_btc");
					if(!$curl->error)
					{
						$info = json_decode($curl->response);
						if(!empty($info->buy) || $info->buy == 0)
						{
							$info = $info->buy;
						}
					}
					break;
					
					case 'coinmarket':
					$curl->get("https://www.coinmarket.io/ajax/orderbook/{$this->short}BTC");
					if(!$curl->error)
					{
						$info = json_decode($curl->response);
						if(!empty($info->orders->bids[0]->rate) || $info->orders->bids[0]->rate == 0)
						{
							$info = $info->orders->bids[0]->rate;
						}
					}
					break;
					
					case 'allcoin':
					/*$curl->get("https://www.allcoin.com/openapi/get_trade_page_info/?type={$this->short}&danwei=BTC");
					if(!$curl->error)
					{
						$info = json_decode($curl->response);
						if(!empty($info->data->buy_max_price) || $info->data->buy_max_price == 0)
						{
							$info = $info->data->buy_max_price;
						}
					}*/
					$this->short = strtolower($this->short);
					$curl->get("https://www.allcoin.com/api1/pair/{$this->short}_btc");
					if(!$curl->error)
					{
						$info = json_decode($curl->response);
						if(!empty($info->data->min_24h_price) || $info->data->min_24h_price == 0)
						{
							$info = $info->data->min_24h_price;
						}
					}
					break;
					
					case 'xnigma':
					$curl->get('https://xnigma.com/api');
					if(!$curl->error)
					{
						$info = json_decode($curl->response);
						foreach($info as $cur)
						{
							if($cur->cur1 == $this->short && $cur->cur2 == 'BTC')
							{
								$info = number_format($cur->sellprice,8,".","");
								break;
							}
						}
					}
					break;
					
					case 'coinex':
					$curl->get('https://coinex.pw/api/v2/trade_pairs');
					if(!$curl->error)
					{
						$info = json_decode($curl->response);
						//var_dump($info);
						foreach($info->trade_pairs as $cur)
						{
							if($cur->id == $this->exid)
							{
								$info = number_format($cur->last_price/100000000,8,".","");
								break;
							}
						}
					}
					break;
					
					case 'cryptoaltex':
					$curl->get('https://www.cryptoaltex.com/api/public.php');
					if(!$curl->error)
					{
						$info = json_decode($curl->response);
						if(!empty($info->{$this->short}->last_trade) || $info->{$this->short}->last_trade == 0)
						{
							$info = number_format($info->{$this->short}->last_trade,8,".","");
						}
					}
					break;
					
					case 'btce':
					$curl->get('https://btc-e.com/api/2/'.$this->exid.'_btc/ticker');
					if(!$curl->error)
					{
						$info = json_decode($curl->response);
						if(!empty($info->ticker->sell))
						{
							$info = $info->ticker->sell;
						}
					}
					break;
					
					case 'bittrex':
					$curl->get('https://bittrex.com/api/v1/public/getticker?market=BTC-'.$this->exid);
					if(!$curl->error)
					{
						$info = json_decode($curl->response);
						if(!empty($info->result->Bid))
						{
							$info = $info->result->Bid;
						}
					}
					break;
					
					case 'bleutrade':
					$curl->get('https://bleutrade.com/api/v1/last_price?market='.$this->exid.'_LTC');
					if(!$curl->error)
					{
						if(!empty($curl->response))
						{
							$info = $curl->response;
						}
					}
					break;
				}
				if(empty($info))
				{
					$info = 0;
				}
				$cache = array('time'=> time(), 'info' => number_format($info,8,'.',''));
				$memcache_obj->set($cache_file, $cache, MEMCACHE_COMPRESSED , 0);
			}
			return $cache['info'];
		}
	}
?>