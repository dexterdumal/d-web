<?php
class Security {
	
	function __construct(){
	}	
	
	function __set($var, $val){
        $this->$var = $val;    
    }
	
	static function antiInjection($campo){
		$campo = trim($campo);
		$campo = strip_tags($campo);
		$campo = addslashes($campo);
		return $campo;
	}
	
	function antiInjectionArray($arrayDados){
		$arrayLimpo = array();
		foreach($arrayDados as $chave=>$valor){
			$arrayLimpo[$chave] = Security::antiInjection($arrayDados[$chave]);
		}
		return $arrayLimpo;
	}
	
	static function trata($dado){
		$correcao = $dado;
		$correcao = str_replace("'","\'",$correcao);
		$correcao = str_replace(".","",$correcao);
		$correcao = str_replace("-","",$correcao);
		$correcao = str_replace("(","",$correcao);
		$correcao = str_replace(")","",$correcao);
		return $correcao;
	}
	
	static function geraSenha(){
		$numero = rand(100,999); //numero randomico entre 100 e 999 para ter sempre 3 caracteres
		$numero = date("s").$numero; //Segundos, com zero preenchendo à esquerda
		$senha = $numero;
		//$_SESSION["senha"] = $senha;
		return $senha;
	}

	function autenticarUsuario($cpf, $senha){
		$cpf = Security::limpaEspeciais(Security::antiInjection($cpf));
		$senha = Security::antiInjection($senha);
		$sql = "SELECT u.`id`, u.`cpf`, u.`email`, u.`nome`, u.`perfil` FROM `usuarios` u WHERE `cpf` = '$cpf' AND `senha` = sha1('$senha');";
		$db = new DBExecutor();
		$resultado = $db->db_get($sql);
		
		if($resultado['numRows']>'0'){
			$_SESSION['cpf'] = $resultado['data'][0]['cpf'];
			$_SESSION['perfil'] = $resultado['data'][0]['perfil'];
			$_SESSION['idUsuario'] = $resultado['data'][0]['id'];
			$_SESSION['nomeUsuario'] = $resultado['data'][0]['nome'];
			$_SESSION['logado'] = "1";
			//$db->db_exec("UPDATE `usuarios` SET `ultimoLogin` = NOW() WHERE `idUsuario` = '".$resultado['data'][0]['idUsuario']."'");
			return true;
		}else{
			return false;
		}
	}

	function mudarSenha($senha_atual, $senha_nova){
		$id = (int) $_SESSION['idUsuario'];
		$senha_atual = sha1($senha_atual);
		$senha_nova = sha1($senha_nova);
		$db = new DBExecutor();
		$resultado = $db->db_exec("UPDATE usuarios SET senha = '$senha_nova' WHERE senha = '$senha_atual' AND id = $id",true);
		return $resultado['affectedRows'] > 0;
	}
	
	function mudarSenhaPerdida($login, $senha_nova){
		$id = (int) $_SESSION['idUsuario'];
		//$senha_atual = sha1($senha_atual);
		$senha_nova = sha1($senha_nova);
		$db = new DBExecutor();
		return $db->db_exec("UPDATE `usuarios` SET `senha` = '$senha_nova' WHERE `login` = '$login' AND `id` = $id",true);		
	}

	function loginUncrypt($hash){
		$security = new Security();
		$hash = $security->antiInjection($hash);
		$sql = "SELECT * FROM `usuarios` WHERE SHA1(CONCAT(cpf,senha)) = '$hash';";
		$exec = new DBExecutor();
		$login = $exec->db_get($sql);
		if($login['numRows']>0){ 
			$_SESSION['idUsuario'] = $login['data'][0]['id'];
			$_SESSION['cpf'] = $login['data'][0]['cpf'];
		}
		return $login;
	}
	
	function logout($pagDestino=home){
		global $estrutura;
		global $args;
		session_unset();
		session_destroy(); 
		if($pagDestino!=null){ header("Location: ".$pagDestino); }
	}
	
	static function validaLogin($papel){
		$_SESSION['papel'] = (isset($_SESSION['papel'])?$_SESSION['papel']:"0");
		$perm = 0;
		$papeis = explode("|",$papel);
		//$urlAtual = substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);
		foreach($papeis as $papel){
			if($papel == $_SESSION['papel']){ $perm = 1; }
		}
		if($perm == 1){
			return true;
		}else{ 
			return false;
		}
	}
	
	function validarPreenchimento($nomesCampos,$campos,$camposOpcionais=array()){
		$retorno = "";
		$status = "true";
		//return ($campo==""||$campo===null)?false:true;
		foreach($campos as $campo=>$valor){
			if(!(in_array($campo, $camposOpcionais))&&(($valor=="")||($valor=="--"))){
				$retorno .= $nomesCampos[$campo]." é obrigatório<br />"; $status = "false";
			}
		}
		$arrayRetorno = Array("status"=>$status,"msg"=>$retorno,"class"=>(($retorno!="")?"erro":"sucesso"));
		return $arrayRetorno;
	}
	
	function validarPreenchimentoMultivalue($nomesCampos,$arrayCampos,$camposOpcionais=array()){
		$retorno = "";
		$status = "true";
		foreach($arrayCampos as $campos){
			if(is_array($campos)){
				foreach($campos as $campo=>$valor){
					if(!(in_array($campo, $camposOpcionais))&&(($valor=="")||($valor=="--"))){
						$retorno .= $nomesCampos[$campo]." é obrigatório<br />"; $status = "false";
					}
				}
			}
		}
		$arrayRetorno = Array("status"=>$status,"msg"=>$retorno,"class"=>(($retorno!="")?"erro":"sucesso"));
		return $arrayRetorno;
		/*foreach($campos as $campo=>$valor){
			if(!(in_array($campo, $camposOpcionais))&&(($valor=="")||($valor=="--"))){
				$retorno .= $nomesCampos[$campo]." é obrigatório<br />"; $status = "false";
			}
		}
		$arrayRetorno = Array("status"=>$status,"msg"=>$retorno,"class"=>(($retorno!="")?"erro":"sucesso"));
		return $arrayRetorno;*/
	}
	
	static function validaEmail($email){
		return !filter_var($email, FILTER_VALIDATE_EMAIL);
	}
	
	static function validaInscricao($dadosFormulario){
		$qtdErros = 0;
		foreach($dadosFormulario as $dados){
			if($dados==""){$qtdErros++;}
		}
		
		if($dadosFormulario["txtDre"]=="" && $dadosFormulario["tipoInscricao"]!="3"){$qtdErros--;}
		if($dadosFormulario["txtNomeMae"]==""){$qtdErros--;}
		if($dadosFormulario["txtNomePai"]==""){$qtdErros--;}
		if($dadosFormulario["txtTelefoneAlter"]==""){$qtdErros--;}
		return $qtdErros;
	}
	
	static function validaDV($dre){
		if($dre){
			$dreInformado = substr($dre, 0, strlen($dre)-1);
			$dvInformado = $dre[strlen($dre)-1];
			$multip = 1;
			$totalChars = 0;
			for($i=0;$i<strlen($dreInformado);$i++){
				$totalChars +=($dreInformado[$i])*$multip;
				$multip++;
			}
			$dvCalculado = (string)$totalChars;
			$dvCalculado = $dvCalculado[strlen($totalChars)-1];
			
			//if($dvInformado==$dvCalculado){return true}else{return false};
			return ($dvInformado==$dvCalculado?true:false);
		}else{ return false; }
	}
	
	
	static function validaCPF($cpf){
		// Verifica se o número digitado contém todos os digitos
		$cpf = str_pad(preg_replace('[^0-9]', '', $cpf), 11, '0', STR_PAD_LEFT);
		
		// Verifica se nenhuma das sequências abaixo foi digitada, caso seja, retorna falso
		if (strlen($cpf) != 11 || $cpf == '00000000000' || $cpf == '11111111111' || $cpf == '22222222222' || $cpf == '33333333333' || $cpf == '44444444444' || $cpf == '55555555555' || $cpf == '66666666666' || $cpf == '77777777777' || $cpf == '88888888888' || $cpf == '99999999999')
		{
		return false;
		}
		else
		{   // Calcula os números para verificar se o CPF é verdadeiro
			for ($t = 9; $t < 11; $t++) {
				for ($d = 0, $c = 0; $c < $t; $c++) {
					$d += $cpf{$c} * (($t + 1) - $c);
				}

				$d = ((10 * $d) % 11) % 10;

				if ($cpf{$c} != $d) {
					return false;
				}
			}

			return true;
		}
	}
	
	static function existeCPF($cpf){
		$sql = "SELECT * FROM `usuarios` u where u.`login`='".Security::trata($cpf)."'";
		$db = new DBExecutor();
		$arrayRetorno = $db->db_get($sql);
		if($arrayRetorno["numRows"]>"0"){return true;}else{return false;}	
	}

	static function limpaEspeciais($texto){
		//preg_replace($pattern, $replacement, $string)
		$correcao = $texto;
		//$correcao = str_replace("@","", $correcao);
		//$correcao = str_replace(":","", $correcao);
		//$correcao = str_replace("/","", $correcao);
		$correcao = str_replace("[","", $correcao);
		$correcao = str_replace("]","", $correcao);
		$correcao = str_replace("{","", $correcao);
		$correcao = str_replace("}","", $correcao);
		$correcao = str_replace(")","", $correcao);
		$correcao = str_replace("(","", $correcao);
		$correcao = str_replace("#","", $correcao);
		$correcao = str_replace("$","", $correcao);
		$correcao = str_replace("%","", $correcao);
		$correcao = str_replace("&","", $correcao);
		$correcao = str_replace("*","", $correcao);
		$correcao = str_replace("+","", $correcao);
		$correcao = str_replace("!","", $correcao);
		$correcao = str_replace("?","", $correcao);
		$correcao = str_replace(">","", $correcao);
		$correcao = str_replace("<","", $correcao);
		$correcao = str_replace(";","", $correcao);
		$correcao = str_replace(",","", $correcao);
		$correcao = str_replace("¨","", $correcao);
		$correcao = str_replace("`","", $correcao);
		$correcao = str_replace("^","", $correcao);	
		$correcao = str_replace("~","", $correcao);
		$correcao = str_replace("=","", $correcao);
		$correcao = str_replace("§","", $correcao);
		$correcao = str_replace("_","", $correcao);
		$correcao = str_replace("'","", $correcao);
		$correcao = str_replace("\"","", $correcao);
		$correcao = str_replace("-","", $correcao);
		$correcao = str_replace(".","", $correcao);
		
		return $correcao;
	}
	
	static function limpaEspeciaisArray(&$item,$key){
		if(is_string($item)){
			//print $key."::".$item;
			//preg_replace($pattern, $replacement, $string)
			$correcao = $item;
			//$correcao = str_replace("@","", $correcao);
			//$correcao = str_replace(":","", $correcao);
			//$correcao = str_replace("/","", $correcao);
			$correcao = str_replace("[","", $correcao);
			$correcao = str_replace("]","", $correcao);
			$correcao = str_replace("{","", $correcao);
			$correcao = str_replace("}","", $correcao);
			$correcao = str_replace(")","", $correcao);
			$correcao = str_replace("(","", $correcao);
			$correcao = str_replace("#","", $correcao);
			$correcao = str_replace("$","", $correcao);
			$correcao = str_replace("%","", $correcao);
			$correcao = str_replace("&","", $correcao);
			$correcao = str_replace("*","", $correcao);
			$correcao = str_replace("+","", $correcao);
			$correcao = str_replace("!","", $correcao);
			$correcao = str_replace("?","", $correcao);
			$correcao = str_replace(">","", $correcao);
			$correcao = str_replace("<","", $correcao);
			$correcao = str_replace(";","", $correcao);
			//$correcao = str_replace(",","", $correcao);
			$correcao = str_replace("¨","", $correcao);
			$correcao = str_replace("`","", $correcao);
			$correcao = str_replace("^","", $correcao);	
			$correcao = str_replace("~","", $correcao);
			$correcao = str_replace("=","", $correcao);
			$correcao = str_replace("§","", $correcao);
			$correcao = str_replace("_","", $correcao);
			$correcao = str_replace("'","", $correcao);
			$correcao = str_replace("\"","", $correcao);
			$correcao = str_replace("-","", $correcao);
			$correcao = str_replace(".","", $correcao);
			
			$item = trim($correcao);
		}
	}
	
	static function verifyExtension($filename, $extVerify){
		//print substr(strtolower($filename), -3, 3);
		return (substr(strtolower($filename), -3, 3)==$extVerify)?true:false;
	}
	
	static function gravaLog($msg, $usuario){
		$sql = "INSERT INTO `usuario_log` (`mensagem`, `usuario`) VALUES('".(Security::antiInjection($msg))."', $usuario);";
		$db = new DBExecutor();
		return $db->db_exec($sql);
	}
	
	static function lembrarSenhaPorCpf($cpf){
		if(Security::existeCPF($cpf)){
			$user = new User();
			$dadosUsuario = $user->obterDadosUsuarioPorCpf($cpf);
			$to['nome'] = $dadosUsuario['data'][0]['nome'];
			$to['email'] = $dadosUsuario['data'][0]['email'];
			$subject = "Lembrete de Senha";
			$body = "<p><strong>Você solicitou a recuperação de sua senha de acesso PIBIC, através do site.</strong><br />";
			$body .= "Clique no link abaixo para ir até a página onde será criada sua nova senha</p><p></p>";
			$body .= "<p><a href='".basePath."nova-senha/".sha1($dadosUsuario['data'][0]['login'].$dadosUsuario['data'][0]['senha'])."'><img alt='' src='".basePath."imagens/btn-recuperar-senha.png'/></a></p>";
			
			Mailer::sendMail($to,$subject,$body);
			return Array("msg"=>"Um email foi enviado com as instruções para criação de uma nova senha. Caso não receba, entre em contato com <a href='mailto:pibic@pr2.ufrj.br'>pibic@pr2.ufrj.br</a>","class"=>"sucesso");
		}else{
			return Array("msg"=>"Cpf Não Existente","class"=>"erro");
		}
	}
}
?>
