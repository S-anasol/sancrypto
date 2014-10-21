<?php
class User extends ActiveRecord\Model
{
	static $before_create = array('create_time'); 
	
	public function create_time() 
	{
		$this->create_time = time();
	}
}
?>