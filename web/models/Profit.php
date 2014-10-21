<?php
class Profit extends ActiveRecord\Model
{
	static $belongs_to = array(
        array('crypto'));
}
?>