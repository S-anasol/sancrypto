<?php
	date_default_timezone_set('Europe/Moscow');
	ini_set('precision',8);
	//var_dump($_GET);
	//die();
	$_GET['route'] = '/index/cron/';
	session_start();
	
	$base = "";
	define('PATH_BASE', '');
	
	define('PATH_LOCAL', dirname(__FILE__).'/');
	define('PATH_CLASSES', PATH_LOCAL . 'classes'.DIRECTORY_SEPARATOR);
	define('PATH_VIEWS', PATH_LOCAL . 'views'.DIRECTORY_SEPARATOR);
	define('PATH_MODELS', PATH_LOCAL . 'models'.DIRECTORY_SEPARATOR);
	define('PATH_CONTROLLERS', PATH_LOCAL . 'controllers'.DIRECTORY_SEPARATOR);
	define('PATH_CACHE', PATH_LOCAL . 'cache'.DIRECTORY_SEPARATOR);
	define('PATH_LOCALES', PATH_LOCAL . 'locales'.DIRECTORY_SEPARATOR);
	
	//Core
	require_once(PATH_CLASSES.DIRECTORY_SEPARATOR."core.php");
	//Router
	require_once(PATH_CLASSES.DIRECTORY_SEPARATOR."router.php");
	//Templating
	require_once(PATH_CLASSES.DIRECTORY_SEPARATOR."template.php");
	//Controller Base
	require_once(PATH_CLASSES.DIRECTORY_SEPARATOR."controller_base.php");
	//jsonRPCClient
	require_once(PATH_CLASSES.DIRECTORY_SEPARATOR."jsonRPCClient.php");
	//Curl
	require_once(PATH_CLASSES.DIRECTORY_SEPARATOR."Curl.php");
	//BC math
	require_once(PATH_CLASSES.DIRECTORY_SEPARATOR."math/BcMath.php");
	
	// AR 8e32bae12ef48eb1df73644fafb74417c8e8ac6c
	require_once PATH_CLASSES . 'ActiveRecord/ActiveRecord.php';
	// Twig 1.14.2
	require_once PATH_CLASSES . 'Twig/Autoloader.php';
	
	try
	{
		Twig_Autoloader::register();
		
		$loader = new Twig_Loader_Filesystem(PATH_VIEWS);
		$twig = new Twig_Environment($loader, array('auto_reload' => true, 'cache' => PATH_CACHE,/**/ 'debug' => false));
		$twig->addExtension(new Twig_Extension_I18n());
		ActiveRecord\Config::initialize(function($cfg)
		{
			$cfg->set_model_directory(PATH_MODELS);
			$cfg->set_connections(
				array(
					'development' => 'mysql://mysql_user:mysql_password@localhost/database'
				)
			);
		});
		$memcache_obj = new Memcache;
		$memcache_obj->connect('localhost', 11711) or die("mcd die");
		
		$crypto_menu = Crypto::find('all', array('order'=>'name asc', 'conditions' => array('status = 1')));
		$i = $z = 0;
		foreach($crypto_menu as $crypto)
		{
			if($i==ceil(count($crypto_menu)/4))
			{
				$z++;
				$i = 0;
			}
			$menu[$z][] = $crypto;
			$i++;
		}
		
		$bcMath = new BcMath(BcMath_PositiveNumber::create(10));
		$core = new Core();
		$template = new Template();
		$router = new Router();
		$router->delegate();
	}
	catch(Exception $e)
	{
		echo get_class($e) . ": " . $e->getMessage();
	}
?>