<?php
class Log extends ActiveRecord\Model
{
    static $has_many = array(
        array('logs', 'foreign_key' => 'id'));
}
?>