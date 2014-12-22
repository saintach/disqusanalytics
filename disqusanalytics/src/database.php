<?php
/**
 * Disqus Analytics
 *
 * http://disqus.com/api/
 *
 * @author		saintayechanhtoon
 * @link		http://saintayechanhtoon.me/
 * @package		disqusanalytics
 * @version		0.0.1
 *
 */
 
 require_once(__ROOT__.'/src/config.php');

class Database {
	
	private $_config;
	
	public static function getInstance()
    {
        static $instance = null;
        if (null === $instance) {
            $instance = new static();
        }

        return $instance;
    }
	
	function __construct()
	{
		global $config;
		$this->_config['db_username'] = $config['db_username'];
		$this->_config['db_password'] = $config['db_password'];
		$this->_config['db_hostname'] = $config['db_hostname'];
		$this->_config['db_name'] = $config['db_name'];
		
		$db_handle = mysql_connect($config['db_hostname'], $config['db_username'], $config['db_password']) or die("Unable to Connect to MySql");
		
		$selected = mysql_select_db($config['db_name'],$db_handle) or die("Could not select mentioned database");
		
		
	}
	
}