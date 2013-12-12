<?php
class DBExecutor {
	
	public $arrayRetorno = array();
	public $conexao = "";
	
	function __construct(){
		$conexao = new Connection();
		$con = $conexao->connect();
	}
	
	function db_exec($sql,$exibe=false){
		$conexao = new Connection();
		$con = $conexao->connect();
		$result = mysql_query($sql,$con);
		$arrayRetorno['query'] = $sql;
		if($result){
			$arrayRetorno['statusMsg'] = array("status" => "true", "msg" => "Operação efetuada com sucesso", "class"=>"sucesso");
			$arrayRetorno['affectedRows'] = mysql_affected_rows();
			$arrayRetorno['insertId'] = mysql_insert_id();
		}
		else{
			$arrayRetorno['statusMsg'] = array("status" => "false", "msg" => "Ocorreu um erro ao tentar efetuar a operação.","msgDebug" => mysql_error(),"class"=>"erro");
			$arrayRetorno['affectedRows'] = 0;
		}
		if($exibe){print"<pre>"; print_r($arrayRetorno); print "</pre>";}
		return $arrayRetorno;
	}

	function db_get($sql,$exibe=false){
		$conexao = new Connection();
		$con = $conexao->connect();
		$result = mysql_query($sql,$con);

		$arrayRetorno['query'] = $sql;
		if($result){
			$arrayRetorno['statusMsg'] = array("status" => "true", "msg" => "Operação efetuada com sucesso", "class"=>"sucesso");
			$arrayRetorno['numRows'] = mysql_num_rows($result);
			if($arrayRetorno['numRows']<2){
				$arrayRetorno['data'][0] = mysql_fetch_assoc($result);
			}else{
				$pos=0;
				while($linha = mysql_fetch_assoc($result)){
					$arrayRetorno['data'][$pos] = $linha;
					$pos++;
				}
			}
		}
		else{
			$arrayRetorno['statusMsg'] = array("status" => "false", "msg" => "Ocorreu um erro ao tentar efetuar a operação. ".mysql_error(), "class"=>"erro");
			$arrayRetorno['numRows'] = 0;
		}
		if($exibe){print"<pre>"; print_r($arrayRetorno); print "</pre>";}
		return $arrayRetorno;
	}
	
	function verify_multiaction($actions){
		$status = 0;
		foreach($actions as $action=>$return){
			if($return == 'false'){ $status++;}
		}
		return $return;
	}

	function db_fk_array($from, $nulo = '', $valor='nome', $id = 'id'){
		$conexao = new Connection();
		$con = $conexao->connect();
		$result = mysql_query("SELECT $id AS chave, $valor AS valor FROM $from", $con);
		$fks = array();
		if ($nulo){
			$fks['NULL'] = $nulo;
		}
		if($result){
			while($linha = mysql_fetch_assoc($result)){
				$fks[$linha['chave']] = $linha['valor'];
			}
		}
		return $fks;
	}

	function db_field($sql,$exibe=false){
		$conexao = new Connection();
		$con = $conexao->connect();
		$result = mysql_query($sql,$con);
		$arrayRetorno['query'] = $sql;
		if($result){
			$arrayRetorno['statusMsg'] = array("status" => "true", "msg" => "Operação efetuada com sucesso", "class"=>"sucesso");
			$arrayRetorno['numRows'] = mysql_num_rows($result);
			$arrayRetorno['data'] = mysql_result($result, 0, 0);
		} else {
			$arrayRetorno['statusMsg'] = array("status" => "false", "msg" => "Ocorreu um erro ao tentar efetuar a operação. ".mysql_error(), "class"=>"erro");
			$arrayRetorno['numRows'] = 0;
		}
		if($exibe){print"<pre>"; print_r($arrayRetorno); print "</pre>";}
		return $arrayRetorno['data'];
	}

}
?>
