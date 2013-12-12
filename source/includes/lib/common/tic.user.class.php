<?php
class User {
	
	function __construct(){
	}	
	
	function __set($var, $val){
        $this->$var = $val;    
    }
	
	function obterDadosUsuarioPorCpf($cpf){
		$sql = "SELECT * FROM `usuario` WHERE `login` = '$cpf';";
		$db = new DBExecutor();
		return $db->db_get($sql);
	}
}
?>