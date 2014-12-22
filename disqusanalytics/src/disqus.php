<?php
ini_set('display_errors', 'On');

/**
 * Disqus Analytics Lite
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
require_once(__ROOT__.'/src/medoo.php');

class Disqus {
	
	protected $_config;
	
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
		$this->_config['dqs_API_public_key'] = $config['dqs_API_public_key'];
		$this->_config['dqs_version'] = $config['dqs_version'];
		$this->_config['dqs_format'] = $config['dqs_format'];
		$this->_config['dqs_forum_name'] = $config['dqs_forum_name'];

	}
	
	//check if the public key exists
	public function dqs_validate()
	{
		if(!isset($this->_config['API_key']) || !isset($this->_config['forum_name'])) return false;
		return true;
	}

	//set up api link
	public function dqs_get_data($category, $resource, $parameters=null)
	{
		$dqs_url = 'http://disqus.com';
		if($parameters) $parameters = $this->_get_query_string($parameters);
		$path = '/api/'.$this->_config['dqs_version'].'/'.$category.'/'.$resource.'.'.$this->_config['dqs_format'].'?api_key='.$this->_config['dqs_API_public_key'].'&'.$parameters;
		$raw_data = $this->_curl($dqs_url.$path);
		$_data = @json_decode($raw_data, true);
		
		echo 'API Path: '.$dqs_url.$path.'<br>';
		
		return $_data;
	}
	
	//disqus code check
	public function code_check($code)
	{
		if($code == 0) return true;
		else return $code;
	}
		
	/********************
	*
	* HELPER FUNCTIONS
	*
	*********************/
	
	//return a query string
	function _get_query_string($parameters) {
		$_str = '';
		
		if($parameters) {
			foreach($parameters as $key=>$value) {
				if (!is_array($value)) {
					$_str .= urlencode($key) . '=' . urlencode($value) . '&';
				} else {
					foreach($value as $multipleValue) 
					{
						$_str .= urlencode($key) . '=' . urlencode($multipleValue) . '&';
					}
				}
			}
		}
		
		return $_str;
	}
	
	//cURL
	public function _curl($link)
	{      
		$_curl = curl_init();
		curl_setopt($_curl, CURLOPT_HEADER, FALSE);
		curl_setopt($_curl, CURLOPT_URL, $link);
		curl_setopt($_curl, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($_curl, CURLOPT_FRESH_CONNECT, TRUE);
		curl_setopt($_curl, CURLOPT_FORBID_REUSE, TRUE);
		$_json_response = curl_exec($_curl);
		curl_close($_curl);
		
		return $_json_response;
	}
}

class DisqusDataCollection extends Disqus{
	
	private $_database;
		
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
		parent::__construct(); 
		
		global $config;
		$this->_config['db_username'] = $config['db_username'];
		$this->_config['db_password'] = $config['db_password'];
		$this->_config['db_server'] = $config['db_server'];
		$this->_config['db_name'] = $config['db_name'];
		$this->_config['db_port'] = $config['db_port'];
		$this->_config['db_type'] = $config['db_type'];
		$this->_config['db_charset'] = $config['db_charset'];

		//using database lib from medoo.in for now
		$this->_database = new  medoo([
			//required
			'database_type' => $this->_config['db_type'],
			'database_name' => $this->_config['db_name'],
			'server' => $this->_config['db_server'],
			'username' => $this->_config['db_username'],
			'password' => $this->_config['db_password'],
			//optional
			'port' => $this->_config['db_port'],
			'charset' => $this->_config['db_charset']
		]);
	}
	
	//perform database operations
	public function _process_data($operation, $table, $columns = null, $join=null, $data = null, $where = null)
	{
		switch($opetation)
		{
			case 'select':
				$response = $_database->select($table, $join, $columns, $where);
				break;
			case 'insert': 
				$response = $_database->insert($table, $data);
				break;
			case 'update':
				$response = $_database->update($table, $data, $where); 
				break;
			case 'delete': 
				$response = $_database->delete($table, $where);
				break;
		}
		
		return $response;
	}
	
	
	public function dqs_select($table, $columns=null, $where=null, $join=null)
	{
		if(!isset($columns) && !isset($where) && !isset($join))
			return $_database->select($table, '*');
		else if (isset($join) && isset($columns) && isset($where))
			return $_database->select($table, $join, $columns, $where);
		else
			return $_database->select($table, $columns, $where);
	}
	
	public function dqs_insert($table, $data)
	{
		$response_id = $this->_database->insert($table, $data);
		
		return $response_id;
	}
	
	public function dqs_update($table, $data, $where)
	{
		$this->_database->update($table, $data, $where);
	}
	
	public function dqs_delete($table, $data)
	{
		$this->_database->delect($table, $data);
	}
	
	public function dqs_is_existed($table, $where, $join = null)
	{
		return $this->_database->has($table, $where);
	}
	
	public function dqs_collect()
	{
		//collect Thread
		$threads = Disqus::dqs_get_data('threads', 'list', array('forum'=> $this->_config['dqs_forum_name']));
		if($threads['code'] == 0)
		{
			$_thread = [];
			foreach($threads['response'] as &$thread)
			{
				
				if(!$this->dqs_is_existed('dqs_threads', ['trd_id' => $thread['id']]) || !empty($thread['isDeleted']))
				{
					array_push($_thread, [
						'title' => $thread['clean_title'],
						'author_id' => $thread['author'],
						'num_comments' => $thread['posts'],
						'num_likes' => $thread['likes'],
						'trd_id' => $thread['id']
					]);
				}
			}

			if(!empty($_thread)) 
			{
				$res = $this->_database->insert('dqs_threads', $_thread);
			}
			unset($_thread);
		}
		else
		{
			echo $thread->code;
		}
		
	}
}

class Analytics extends DisqusDataCollection{
	
	public function 
	
}