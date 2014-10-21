<?php

class Core
{
	public function is_logged()
	{
		global $router;
		if (empty($_SESSION['logged_in']))
		{
			return false;
		}
		else
		{
			$user = User::find($_SESSION["user_id"]);
			if (session_id() == $this->generate_session_id($user))
			{
				return $_SESSION["user_id"];
			}
			else
			{
				$router->redirect('/index/logout/');
			}
		}
	}

	public function check_password($hash, $password) {  
		// первые 29 символов хеша, включая алгоритм, «силу замедления» и оригинальную «соль» поместим в переменную  $full_salt
		$full_salt = substr($hash, 0, 29);   
		// выполним хеш-функцию для переменной $password  
		$new_hash = crypt($password, $full_salt);   
		// возвращаем результат («истина» или «ложь»)  
		return ($hash == $new_hash); 
	}
	
	public function get_user()
	{
		if ($user_id = $this->is_logged())
		{
			return User::find($user_id);
		}
		else
		{
			return false;
		}
	}

	public function is_ajax()
	{
		if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	public function generate_session_id($user)
	{
		return md5($user->id . $user->password . $user->login . $_SERVER['HTTP_USER_AGENT'] . $_SERVER['REMOTE_ADDR']);
	}
	
	public function generateRandomString($length) 
	{
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$randomString = '';	
		for ($i = 0; $i < $length; $i++) 
		{	
			$randomString .= $characters[mt_rand(0, strlen($characters) - 1)];
		}
		return $randomString;
	}
	
	public function generateScryptBlowfish($value){
		$salt = '$2a$10$'.substr(str_replace('+', '.', base64_encode(pack('N4', mt_rand(), mt_rand(), mt_rand(),mt_rand()))), 0, 22).'$';
		return crypt($value, $salt); // соль будет сгенерирована автоматически
	}

}
?>