<?php
class Connection {

	public $hostname;
	public $username;
	public $password;
	public $database;

		
	function __construct(){
		/*$conexao = mysql_connect('localhost','root','');
		if(!$conexao) { die('Não foi possível conectar'.mysql_error()); }
		$tabela = mysql_select_db('db_faubai',$conexao);
		mysql_query("SET NAMES 'utf8'");*/
	}	
	
	function __set($var, $val){
        $this->$var = $val;    
    }
	
	function connect(){
		global $config;
		$this->_database_connection = mysql_pconnect($config['database']['host'],$config['database']['user'],$config['database']['pass']) or trigger_error(mysql_error(),E_USER_ERROR);
		$this->_database_connection_select = mysql_select_db($config['database']['table'],$this->_database_connection);
		mysql_query("SET NAMES 'utf8'");
		return $this->_database_connection;
	}
}
?>