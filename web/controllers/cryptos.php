<?php
	
	Class Controller_Cryptos Extends Controller_Base
	{
		
		function index()
		{
			global $router;
			$router->redirect('');
		}
		
		function manage()
		{
			global $template, $core, $router;
			$errors = array();
			if(!$core->is_logged())
			{
				$template->render("auth");
			}
			else
			{
				$data = Crypto::find('all',array('order' => 'status desc'));
				$template->render("cryptos/manage", array('subtitle' => "Крипты ебта", 'data' => $data));
			}
		}
		
		function edit()
		{
			global $template, $core, $router, $get;
			$errors = array();
			if(!$core->is_logged())
			{
				$template->render("auth");
			}
			else
			{
				$get[0] = (int)$get[0];
				$data_ = Crypto::find($get[0]);
				if(!empty($data_->id))
				{
					$data = array();
					$error = false;
					if(!empty($_POST))
					{
						$data['name'] = $_POST["name"];
						$data['short'] = $_POST["short"];
						$data['cpb'] = $_POST["cpb"];
						$data['exchange'] = $_POST["exchange"];
						$data['explorer'] = $_POST["explorer"];
						$data['logo'] = $_POST["logo"];
						$data['bttalk'] = $_POST["bttalk"];
						$data['exid'] = $_POST["exid"];
						$data['algo'] = $_POST["algo"];
						
						if(!$error)
						{
							$data_->name = $data['name'];
							$data_->short = $data['short'];
							$data_->cpb = $data['cpb'];
							$data_->exchange = $data['exchange'];
							$data_->explorer = $data['explorer'];
							$data_->logo = $data['logo'];
							$data_->bttalk = $data['bttalk'];
							$data_->exid = $data['exid'];
							$data_->algo = $data['algo'];
							if($data_->save())
							{
								$router->redirect('cryptos/manage/', true);
							}
						}
					}
					
					$template->render("cryptos/add", array('subtitle' => "Изменить мир", 'data' => $data_));
				}
				else
				{
					$router->redirect('cryptos/manage/', true);
				}
			}
		}
		
		function add_crypto()
		{
			global $template, $core, $router;
			$errors = array();
			if(!$core->is_logged())
			{
				$template->render("auth");
			}
			else
			{
				$data = array();
				$error = false;
				if(!empty($_POST))
				{
					$data['name'] = $_POST["name"];
					$data['short'] = $_POST["short"];
					$data['cpb'] = $_POST["cpb"];
					$data['exchange'] = $_POST["exchange"];
					$data['explorer'] = $_POST["explorer"];
					$data['logo'] = $_POST["logo"];
					$data['bttalk'] = $_POST["bttalk"];
					$data['exid'] = $_POST["exid"];
					$data['algo'] = $_POST["algo"];
					
					if(!$error)
					{
						$site = new Crypto();
						$site->name = $data['name'];
						$site->short = $data['short'];
						$site->cpb = $data['cpb'];
						$site->exchange = $data['exchange'];
						$site->explorer = $data['explorer'];
						$site->logo = $data['logo'];
						$site->bttalk = $data['bttalk'];
						$site->exid = $data['exid'];
						$site->algo = $data['algo'];
						if($site->save())
						{
							$router->redirect('cryptos/index/success/', true);
						}
					}
				}
				
				$template->render("cryptos/add", array('subtitle' => "Добавить", 'data' => $data, 'alerts' => $errors));
			}
		}
		
		
		
		function getblockcount()
		{
			global $template, $core, $router, $get;
			$curl = new Curl();
			if($core->is_ajax())
			{
				try {
					$crypto = Crypto::find($get[0]);
				}
				catch(Exception $e)
				{
				}
				if(!empty($crypto->id))
				{
					echo $crypto->getblockcount();
				}
				else
				{
					$template->render("access");
				}
			}
			else
			{
				$template->render("access");
			}
		}
		
		function totalbc()
		{
			global $template, $core, $router, $get;
			if($core->is_ajax())
			{
				try {
					$crypto = Crypto::find($get[0]);
				}
				catch(Exception $e)
				{
				}
				if(!empty($crypto->id))
				{
					
					echo $crypto->totalbc();
				}
				else
				{
					$template->render("access");
				}
			}
			else
			{
				$template->render("access");
			}
		}
		
		function diff()
		{
			global $template, $core, $router, $get;
			$curl = new Curl();
			if($core->is_ajax())
			{
				try {
					$crypto = Crypto::find($get[0]);
				}
				catch(Exception $e)
				{
				}
				if(!empty($crypto->id))
				{
					echo $crypto->diff();
				}
				else
				{
					$template->render("access");
				}
			}
			else
			{
				$template->render("access");
			}
		}
		
		function btcltc()
		{
			global $template, $core, $router, $get;
			if($core->is_ajax())
			{
				try {
					$crypto = Crypto::find($get[0]);
				}
				catch(Exception $e)
				{
				}
				if(!empty($crypto->id))
				{
					echo $crypto->btcltc();
				}
				else
				{
					$template->render("access");
				}
			}
			else
			{
				$template->render("access");
			}
		}
		
		
		function chart()
		{
			global $template, $core, $router, $get;
			//	if($core->is_ajax())
			//	{
			try {
				$crypto = Crypto::find($get[0]);
			}
			catch(Exception $e)
			{
			}
			if(!empty($crypto->id))
			{
				echo $crypto->chart();
			}
			else
			{
				$template->render("access");
			}
			//}
			//	else
			//	{
			//		$template->render("access");
			//	}
		}
		
		function btc()
		{
			global $template, $core, $router, $get;
			if($core->is_ajax())
			{
				try {
					$crypto = Crypto::find($get[0]);
				}
				catch(Exception $e)
				{
				}
				if(!empty($crypto->id))
				{
					echo $crypto->btc();
				}
				else
				{
					$template->render("access");
				}
			}
			else
			{
				$template->render("access");
			}
		}
		
		function profit_chart()
		{
			global $template, $core, $router, $get, $memcache_obj;
			
			if($core->is_ajax())
			{
				try {
					$crypto = Crypto::find($get[0]);
				}
				catch(Exception $e)
				{
				}
				if(!empty($crypto->id))
				{
					$cache_file = PATH_CACHE.$crypto->id.'_profit_chart.cache';
					$cache = $memcache_obj->get($cache_file);
					//echo "13";
					if ($cache['time'] < (time() - 60*10) || empty($cache['info'])) 
					{
						$info = '';
						ob_start();
						$profits = array();
						$crypto_profits = Profit::find('all', array('select' => 'avg(percent) as percent,time,FROM_UNIXTIME(time, "%m.%d.%Y %H:00:00") as date', 'conditions' => array('crypto_id = ?', $crypto->id),'group' => 'date asc', 'order' => 'date asc'));
						foreach($crypto_profits as $profit)
						{
							$profits[] = "[{$profit->time}000,{$profit->percent}]";
						}
						echo '$("#profit'.$crypto->id.'").highcharts({navigation:{buttonOptions:{enabled:false}},legend:{enabled:false},chart:{type:"line",backgroundColor:"transparent"},title:{text:"'.$crypto->name.' vs LTC Profitability Chart"},xAxis:{type: "datetime",gridLineWidth: 0,lineWidth: 0, minorGridLineWidth: 0, lineColor: "transparent",minorTickLength: 0, tickLength: 0,labels: {enabled: true}},yAxis:{lineWidth: 0, gridLineWidth: 0,minorGridLineWidth: 0, lineColor: "transparent",minorTickLength: 0, tickLength: 0,labels: {enabled: false},title:{text:""},opposite: true},plotOptions:{line:{marker:{enabled:false}}, series: {compare: "percent"}},series:[{name:"profit",data:['.implode(',',$profits).']}],tooltip:{shared: false,formatter: function() {
						if(this.series.name == "profit")
						{
						return Highcharts.dateFormat("%m/%d/%Y %H:%M", this.x)+": "+this.y+"%";
						}
						else
						{
						return Highcharts.dateFormat("%m/%d/%Y %H:%M", this.x)+": "+this.y*1+" BTC("+this.point.exchange+")";
						}
						}}});
						';
						unset($profits);
						$prices = array();
						$exchanges = array('allcoin' => 'AllCoin.com', 'cryptorush' => 'CryptoRush.in', 'poloniex' => 'Poloniex.com', 'mintpal' => 'MintPal.com', 'cryptsy' => 'Cryptsy.com', 'bter' => 'Bter.com', 'xnigma' => 'Xnigma.com', 'cryptoaltex' => 'CryptoALTeX.com', 'btce' => 'BTC-e.com', 'bittrex' => 'Bittrex.com', 'bleutrade' => 'Bleutrade.com');
						$crypto_prices = Price::find('all', array('select' => 'avg(price) as price,time, exchange,FROM_UNIXTIME(time, "%m.%d.%Y %H:00:00") as date', 'conditions' => array('crypto_id = ?', $crypto->id),'group' => 'date asc', 'order' => 'date asc'));
						foreach($crypto_prices as $price)
						{
							$prices[] = "{x: {$price->time}000,y: ".number_format($price->price,10,'.','').", exchange: '{$exchanges[$price->exchange]}'}";
						}
						echo '
						$("#price'.$crypto->id.'").highcharts({navigation:{buttonOptions:{enabled:false}},legend:{enabled:false},chart:{type:"line",backgroundColor:"transparent"},title:{text:"'.$crypto->name.' Price Chart"},xAxis:{type: "datetime",gridLineWidth: 0,lineWidth: 0, minorGridLineWidth: 0, lineColor: "transparent",minorTickLength: 0, tickLength: 0,labels: {enabled: true}},yAxis:{allowDecimals: true,lineWidth: 0, gridLineWidth: 0,minorGridLineWidth: 0, lineColor: "transparent",minorTickLength: 0, tickLength: 0,labels: {enabled: false},title:{text:""}},plotOptions:{line:{marker:{enabled:false}}, series: {compare: "percent"}},series:[{name:"price",data:['.implode(',',$prices).']}],tooltip:{shared: false,formatter: function() {
						if(this.series.name == "profit")
						{
						return Highcharts.dateFormat("%m/%d/%Y %H:%M", this.x)+": "+this.y+"%";
						}
						else
						{
						return Highcharts.dateFormat("%m/%d/%Y %H:%M", this.x)+": "+Highcharts.numberFormat(this.y,10,\'.\',\'\')+" BTC("+this.point.exchange+")";
						}
						}}});
						';
						unset($prices);
						$info = ob_get_contents();
						ob_end_clean();
						
						$cache = array('time'=> time(), 'info' => $info);
						$memcache_obj->set($cache_file, $cache, MEMCACHE_COMPRESSED , 0);
					}
					
					echo $cache['info'];
				}
				else
				{
					$template->render("access");
				}
			}
			else
			{
				$template->render("access");
			}
		}
	}
?>