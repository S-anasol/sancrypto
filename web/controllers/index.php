<?php
	
	Class Controller_Index Extends Controller_Base
	{
		
		function cron()
		{
			global $bcMath,$memcache_obj, $get;
			$curl = new Curl();
			
			$ltcdiff = $this->get_ltc_diff();
			$var1 = $bcMath->multiplication($ltcdiff,pow(2, 32));
			$var2 = $bcMath->division($var1,1000*1000);
			$_ltc_per_day = $bcMath->division(3600 * 24 * 50,$var2);
			
			$btcdiff = $this->get_btc_diff();
			$_var1 = $bcMath->multiplication($btcdiff,pow(2, 32));
			$_var2 = $bcMath->division($_var1,1000*1000*1000);
			$_btc_per_day = $bcMath->division(3600 * 24 * 25,$_var2);
			
			$list = Crypto::find('all',array('conditions' => array('status = 1'),'order'=>'name asc'));
			$cryptos = array();
			$btcltc = Crypto::btcltc();
			foreach($list as $crypto)
			{
				$btc = $crypto->btc();
				if($crypto->algo != 3)
				{
					$ltc_per_day = $_ltc_per_day;
				}
				else
				{
					$ltc_per_day = $_btc_per_day;
				}
				
				$var1 = $var2 = $crypto_per_day = $btc_per_day = $ltc_in_crypto_per_day = 0;
				$var1 = $bcMath->multiplication($crypto->diff(),pow(2, 32));
				$hrate = 1000;
				if($crypto->algo == 2)
				{
					$hrate = 500;
				}
				
				if($crypto->algo == 3)
				{
					$hrate = 1000000;
				}
					
				$var2 = $var1/($hrate*1000);
				$crypto_per_day = (3600 * 24 * $crypto->cpb)/$var2;
				$btc_per_day = $bcMath->multiplication($crypto_per_day,$btc);
				if($crypto->id != 17)
				{
					if($crypto->algo != 3)
					{
						$ltc_in_crypto_per_day = $btc_per_day/$crypto->btcltc();
					}
					else
					{
						$ltc_in_crypto_per_day = $btc_per_day;
					}
				}
				else
				{
					$ltc_in_crypto_per_day = $btc_per_day;
				}
				if($bcMath->rounding(($ltc_in_crypto_per_day * 100)/ $ltc_per_day) > 0)
				{
					Profit::create(array('crypto_id' => $crypto->id,'time' => time(), 'percent' => $bcMath->rounding(($ltc_in_crypto_per_day * 100)/ $ltc_per_day)));
				}
				
				if($btc > 0)
				{
					Price::create(array('crypto_id' => $crypto->id,'time' => time(), 'price' => $btc, 'exchange' => $crypto->exchange));
				}
			}
			
			Profit::find_by_sql("delete from profits where percent > 500");
		}
		
		function cron2()
		{
			global $bcMath,$memcache_obj, $get;
			$cache_file_main = PATH_CACHE.'main_page.cache';
			$cache_main = $memcache_obj->get($cache_file_main);
			if ($cache_main['time'] < (time() - 60 * 10 ) || $get[0] == 'force') 
			{
				$info = '';
				$curl = new Curl();
				
				$ltcdiff = $this->get_ltc_diff();
				$var1 = $bcMath->multiplication($ltcdiff,pow(2, 32));
				$var2 = $bcMath->division($var1,1000*1000);
				$_ltc_per_day = $bcMath->division(3600 * 24 * 50,$var2);
				
				$btcdiff = $this->get_btc_diff();
				$_var1 = $bcMath->multiplication($btcdiff,pow(2, 32));
				$_var2 = $bcMath->division($_var1,1000*1000*1000);
				$_btc_per_day = $bcMath->division(3600 * 24 * 25,$_var2);
				
				$algos = array(1 => 'Scrypt', 2 => 'Scrypt-N', 3 => 'SHA-256', 4 => 'X11');
				
				$list = Crypto::find('all',array('order'=>'name asc', 'conditions' => array('status = 1')));
				$cryptos = array();
				foreach($list as $crypto)
				{
					if($crypto->algo != 3)
					{
						$ltc_per_day = $_ltc_per_day;
					}
					else
					{
						$ltc_per_day = $_btc_per_day;
					}
					
					$var1 = $var2 = $crypto_per_day = $btc_per_day = $ltc_in_crypto_per_day = 0;
					$var1 = $bcMath->multiplication($crypto->diff(),pow(2, 32));
					$hrate = 1000;
					if($crypto->algo == 2)
					{
						$hrate = 500;
					}
					
					if($crypto->algo == 3)
					{
						$hrate = 1000000;
					}
					
					$var2 = $var1/($hrate*1000);
					$crypto_per_day = (3600 * 24 * $crypto->cpb)/$var2;
					
					$btc_per_day = $bcMath->multiplication($crypto_per_day,$crypto->btc());
					
					if($crypto->id != 17)
					{
						if($crypto->algo != 3)
						{
							$ltc_in_crypto_per_day = $btc_per_day/$crypto->btcltc();
						}
						else
						{
							$ltc_in_crypto_per_day = $btc_per_day;
						}
					}
					else
					{
						$ltc_in_crypto_per_day = $btc_per_day;
					}
					$cryptos[$crypto->id]['percent'] = $bcMath->rounding(($ltc_in_crypto_per_day * 100)/ $ltc_per_day);
					
					$cryptos[$crypto->id]['short'] = $crypto->short;
					$cryptos[$crypto->id]['logo'] = $crypto->logo;
					$cryptos[$crypto->id]['name'] = $crypto->name;
					$cryptos[$crypto->id]['algo'] = $crypto->algo;
					$cryptos[$crypto->id]['algo_name'] = $algos[$crypto->algo];
					
					
					$cryptos[$crypto->id]['chart'] = '<div class="profit24" style="width: 400px;height: 75px; margin: 0 auto" data-crypto="'.$crypto->id.'"></div>';
				}
				
				function my_sorter($a, $b) {
					return strcmp($a['percent'], $b['percent']);
				}
				usort($cryptos, 'my_sorter');
				rsort($cryptos);
				$cache_main = array('time'=> time(), 'info' => $cryptos);
				$memcache_obj->set($cache_file_main, $cache_main, MEMCACHE_COMPRESSED , 0);
			}
		}
		
		function index()
		{
			global $template, $router, $core, $bcMath,$memcache_obj;
			$alerts = array();
			if(!empty($get[0]) && $get[0] == 'success')
			{
				$alerts[] = array('type' => 'success', 'icon' => 'check', 'head' => 'Отлично', 'body' => 'Добавлено в систему');
			}
			$cache_file_main = PATH_CACHE.'main_page.cache';
			$cache_main = $memcache_obj->get($cache_file_main);
			
			$template->render("cryptos/index", array('subtitle' => "Cryptos", 'alerts' => $alerts, 'list' => $cache_main['info']));
		}
		
		function archive()
		{
			global $template, $router, $core, $bcMath,$memcache_obj;
			$list = Crypto::find('all',array('order'=>'name asc', 'conditions' => array('status = 0')));
			$template->render("cryptos/archive", array('subtitle' => "CryptoArchive", 'alerts' => $alerts, 'list' => $list));
		}
		
		function show()
		{
			global $template, $get,$memcache_obj;
			$crypto = Crypto::find_by_short($get[0]);
			if(!empty($crypto->id))
			{
				$curl = new Curl();
				
				$ltcdiff = $this->get_ltc_diff();
				$btcdiff = $this->get_btc_diff();
				
				$cache_file = PATH_CACHE.'btc_price.cache';
				$cache = $memcache_obj->get($cache_file);
				if ($cache['time'] < (time() - 1 )) 
				{
					$curl->get('https://btc-e.com/api/2/btc_usd/ticker');
					if(!$curl->error)
					{
						$bitprice = json_decode($curl->response);
						if(!empty($bitprice->ticker->sell))
						{
							$bitprice = $bitprice->ticker->sell;
						}
					}
					else
					{
						//	echo $curl->error_message;
					}
					$cache = array('time'=> time(), 'info' => $bitprice);
					$memcache_obj->set($cache_file, $cache, MEMCACHE_COMPRESSED , 0);
				}
				$bit_price = $cache['info'];
				//echo $bit_price;
				$cache_file = PATH_CACHE.'ltc_price.cache';
				$cache = $memcache_obj->get($cache_file);
				if ($cache['time'] < (time() - 1 )) 
				{
					$curl->get('https://btc-e.com/api/2/ltc_usd/ticker');
					if(!$curl->error)
					{
						$liteprice = json_decode($curl->response);
						if(!empty($liteprice->ticker->sell))
						{
							$liteprice = $liteprice->ticker->sell;
						}
					}
					$cache = array('time'=> time(), 'info' => $liteprice);
					$memcache_obj->set($cache_file, $cache, MEMCACHE_COMPRESSED , 0);
				}
				$lite_price = ($cache['info']) ? $cache['info']:0;
				//echo $lite_price;
				//$profits = array();
				//foreach($crypto->profits as $profit)
				//{
				//	$profits[] = "[{$profit->time}000,{$profit->percent}]";
				//}
				//$chart = '<div id="profit'.$crypto->id.'" style="width: 800px;height: 200px; margin: 0 auto"></div><script>$(function(){$("#profit'.$crypto->id.'").highcharts({navigation:{buttonOptions:{enabled:false}},legend:{enabled:false},chart:{type:"line",backgroundColor:"transparent"},title:{text:"'.$crypto->name.' vs LTC Profitability Chart"},xAxis:{type: "datetime",gridLineWidth: 0,lineWidth: 0, minorGridLineWidth: 0, lineColor: "transparent",minorTickLength: 0, tickLength: 0,labels: {enabled: true}},yAxis:{lineWidth: 0, gridLineWidth: 0,minorGridLineWidth: 0, lineColor: "transparent",minorTickLength: 0, tickLength: 0,labels: {enabled: false},title:{text:""}},plotOptions:{line:{marker:{enabled:false}}},series:[{name:"'.$crypto->name.' Profitability Chart",data:['.implode(',',$profits).']}],tooltip:{ formatter: function() {return Highcharts.dateFormat("%m/%d/%Y %H:%M", this.x)+": "+this.y+"%";}}})});</script>';
				$exchanges = array('allcoin' => '<a href="https://www.allcoin.com/trade/'.$crypto->short.'_btc?ref=124104" target="_blank">AllCoin.com</a>', 'cryptorush' => '<a href="https://www.cryptorush.in/index.php?p=trading&m='.$crypto->short.'&b=BTC" target="_blank">CryptoRush.in</a>', 'poloniex' => '<a href="https://poloniex.com/exchange/btc_'.$crypto->short.'" target="_blank">Poloniex.com</a>', 'mintpal' => '<a href="https://www.mintpal.com/market/'.$crypto->short.'/BTC" target="_blank">MintPal.com</a>', 'bter' => '<a href="https://bter.com/ref/144408" target="_blank">Bter.com</a>', 'cryptsy' => '<a href="https://www.cryptsy.com/users/register?refid=201806" target="_blank">Cryptsy.com</a>', 'xnigma' => '<a href="https://xnigma.com" target="_blank">Xnigma.com</a>', 'cryptoaltex' => '<a href="https://cryptoaltex.com" target="_blank">CryptoALTeX.com</a>', 'btce' => '<a href="https://btc-e.com" target="_blank">BTC-e.com</a>', 'bittrex' => '<a href="https://bittrex.com/Market/Index?MarketName=BTC-'.strtoupper($crypto->short).'" target="_blank">Bittrex.com</a>', 'bleutrade' => '<a href="https://bleutrade.com/exchange/1460" target="_blank">Bleutrade.com</a>');
				
				$algos = array(1 => 'Scrypt', 2 => 'Scrypt-N', 3 => 'SHA-256', 4 => 'X11');
				
				$template->render("cryptos/show",array('crypto' => $crypto, 'algo' => $algos[$crypto->algo], 'ltcdiff' => $ltcdiff, 'btcdiff' => $btcdiff, 'active' => $crypto->id, 'exchange' => $exchanges[$crypto->exchange], 'lite_price' => $lite_price, 'bit_price' => $bit_price));
			}
			else
			{
				$template->render("access");
			}
		}
		
		function auth()
		{
			global $template, $router, $core;
			$errors = array();
			if ($_SERVER["REQUEST_METHOD"] == "POST")
			{
				if (empty($_POST["email"]))
				{
					$errors[] = "Email is empty...";
				}
				else if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL))
				{
					$errors[] = "Email is wrong...";
				}
				
				if (empty($_POST["password"]))
				{
					$errors[] = "Password is empty...";
				}
				
				if (count($errors) == 0)
				{
					$user = User::find_by_login($_POST["email"]);
					//     $user = User::find_by_login_and_password($_POST["email"], md5($_POST["password"]));
					//      if (empty($user->id))
					//		var_dump($user->salt);
					//		if(crypt($_POST["password"], $user->salt) != $user->password)
					if(!$core->check_password($user->password, $_POST["password"]))
					{
						$errors[] = "Login or password incorrect";
					}
					else
					{
						session_write_close();
						session_id($core->generate_session_id($user));
						session_start();
						$_SESSION["logged_in"] = 1;
						$_SESSION["user_id"] = $user->id;
						setcookie("sanasol", "sanasol", time()+time(), "/", ".sancrypto.info");
						$router->redirect('');
					}
				}
				
				$template->render("auth", array("errors" => $errors));
			}
			else
			{
				$template->render("auth");
			}
		}
		
		function logout()
		{
			global $router;
			unset($_SESSION["logged_in"], $_SESSION["user_id"]);
			$router->redirect('');
		}
		
		
		function register()
		{
			global $template, $router, $core;
			$errors = array();
			if($_SERVER["REQUEST_METHOD"] == "POST")
			{
				// Сначала проверим mail
				if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) 
				{
					$errors[] = "Bad E-mail";
				}
				
				$_POST['email'] = mb_strtolower($_POST['email']);  // Переведем в нижний регистр
				
				// Проверим mail в базе
				$user = User::find_by_login($_POST['email']);
				if(!empty($user->id))
				{
					$errors[] = "E-mail already exists";
				}
				
				if(count($errors))
				{
					$template->render("register/index", array("errors" => $errors));
				}
				else
				{
					// Сгенерируем пароль
					$passwd	= $core->generateRandomString(11);
					$hash	= $core->generateScryptBlowfish($passwd);
					$key	= $core->generateRandomString(32);
					
					// Запишем в базу
					$new_user = new User();
					$new_user->login = $mail;
					$new_user->password = $hash;
					$new_user->key = $key;
					
					if($new_user->save())
					{
						mail($mail, "Access", "Password: {$passwd}");
						$router->redirect('/', true, array("msg" => "register_success"));
					}
					else
					{
						$errors[] = "MySQL error";
						$template->render("register/index", array("errors" => $errors));
					}
				}
			}
			else
			{
				$template->render("register/index");
			}
			
		}
		
		function get_btc_diff()
		{
			global $bcMath,$memcache_obj, $get;
			$cache_file = PATH_CACHE.'btc_difficulty.cache';
			$cache = $memcache_obj->get($cache_file);
			if ($cache['time'] < (time() - 3600 )) 
			{
				$curl = new Curl();
				$curl->get('https://bitcoinwisdom.com/bitcoin/difficulty');
				if(!$curl->error)
				{
					preg_match("/Difficulty:<\/td>\s*<td>(.*?)</", $curl->response, $diff);
					$ltcdiff = str_replace(',','',$diff[1]);
					if(!empty($ltcdiff))
					{
						$ltcdiff = $ltcdiff;
					}
					else
					{
						$ltcdiff = 0;
					}
				}
				else
				{
					//echo $curl->error_message;
				}
				$cache = array('time'=> time(), 'info' => $ltcdiff);
				$memcache_obj->set($cache_file, $cache, MEMCACHE_COMPRESSED , 0);
			}
			return $cache['info'];
		}
		
		function get_ltc_diff()
		{
			global $bcMath,$memcache_obj, $get;
			$cache_file = PATH_CACHE.'ltc_difficulty.cache';
			$cache = $memcache_obj->get($cache_file);
			if ($cache['time'] < (time() - 3600 )) 
			{
				$curl = new Curl();
				$curl->get('https://bitcoinwisdom.com/litecoin/difficulty');
				if(!$curl->error)
				{
					preg_match("/Difficulty:<\/td>\s*<td>(.*?)</", $curl->response, $diff);
					$ltcdiff = str_replace(',','',$diff[1]);
					if(!empty($ltcdiff))
					{
						$ltcdiff = $ltcdiff;
					}
					else
					{
						$ltcdiff = 0;
					}
				}
				else
				{
					//echo $curl->error_message;
				}
				$cache = array('time'=> time(), 'info' => $ltcdiff);
				$memcache_obj->set($cache_file, $cache, MEMCACHE_COMPRESSED , 0);
			}
			return $cache['info'];
		}
	}
?>