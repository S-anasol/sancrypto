<?php
class Price extends ActiveRecord\Model
{
	static $belongs_to = array(
        array('crypto'));
}
?>