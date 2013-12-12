<?php
	header("Content-type: text/html; charset=utf-8");
	header("Cache-Control: no-cache, must-revalidate");
	//header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	/*CARREGA AS CONFIGURAÇÕES E VARIÁVEIS QUE SERÃO UTILIZADAS POR TODO O SISTEMA*/
	//ini_set('include_path', 'includes');
	ini_set('session.save_handler', 'files');
	ini_set('display_errors', '1');
	error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
	//error_reporting(E_ERROR | E_WARNING | E_PARSE);
	session_start();
	
	$arrayRetorno = Array();
	$retornoSimples = "";
	$statusRetorno = array("status"=>"", "msg"=>"", "class"=>"");
	$etapaInscricao;

	$classMsg = "";
	$msg = array("status"=>"", "msg"=>"", "class"=>"");
	$config = Array();
	$estrutura = Array();
	require_once "config.php";
	
	//função recursiva que carrega as libs nativas e especificas do sistema
	function loadLibsRecursive($libsPath){
		$libs = array_diff(scandir($libsPath), array('..', '.'));
		foreach($libs as $lib){
			if(is_dir($libsPath.$lib)){
				loadLibsRecursive($libsPath.$lib);
			}else{
				//print "$libsPath/$lib<br />";
				require_once "$libsPath/$lib";
			}
		}
	}
	loadLibsRecursive($config['system']['fileroot']."includes/lib/");
	
	//carrega as libs de terceiros
	require_once $config['system']['fileroot']."includes/swift/lib/swift_required.php";
	
	$args = explode('/',$_GET['q']);
	$args = Util::limpaArray($args);
	$stringArgs = implode('/',$args);
	$postVars['argsData'] = $args;
	$postVars['postData'] = $_POST;
	define("basePath", $config['system']['basepath']);
	define("serverUrl", $config['system']['serverurl']);
	define("fileRoot", $config['system']['fileroot']);
	define("home", $config['system']['home']);
	define("homeAdmin", $config['system']['homeAdmin']);
	define("args", $_SERVER['REQUEST_URI']);	
	define("strArgs", $stringArgs);	
	define("contentTypes", implode("|",$config['content']['type']));
	
	$pageTitle = (isset($args[0])?$args[0]:"home");
	$pageURL = $_SERVER['REQUEST_URI'];
	
	ob_start();
	//ob_start("ob_gzhandler");	
	
	/*tratamento de validação para login e controle de acesso*/
	if(isset($_POST['acao']) && $_POST['acao']=='login'){
		$security = new Security();
		if($security->autenticarUsuario($_POST['login'], $_POST['senha'])){
			if((isset($_SESSION['perfil']))&&($_SESSION['perfil']==2)){
				header("Location: ".basePath.homeAdmin);
			}else{
				header("Location: ".basePath.home);
			}
		}else{
			$msg['class'] = "erro";
			$msg['msg'] = "Usuário ou senha incorretos";
		}
	}
	if($args[0] == 'logout'){
		$security = new Security();
		$security->logout($pagDestino=basePath.home);
	}
	
	$core = new Core();
	Core::initialize($args);
	if($args[0]==="index"){
		$estrutura['content'] = $core->newGetContent($config['system']['home']);
	}
	if(!isset($_SESSION['logado']) && ($estrutura['pageAttr']['perfil'] != 0)){
		$estrutura['content'] = $core->newGetContent('nao-autorizado');
	}
	if((isset($_SESSION['perfil']))&&($estrutura['pageAttr']['perfil'] > $_SESSION['perfil'])){
		$estrutura['content'] = $core->newGetContent('nao-autorizado');
	}
	
	//print $estrutura['pageAttr']['perfil']."---".$_SESSION['perfil'];
	/*fim controle de acesso*/
	
	
	if(isset($_SESSION['usuarioAdmin'])==1){
		$estrutura['menuAdmin'] = $core->menuGen('menu_admin',((isset($_SESSION['perfil']))?$_SESSION['perfil']:0));		
	}
	//print_r($_GET);
	//print_r($args);
	//print_r($estrutura);
	//print_r($estrutura['pageAttr']);
	extract($estrutura);
	
	if(file_exists(realpath("includes/templates/page/page-".$args[0].".tpl.php"))) {
		require("includes/templates/page/page-".$args[0].".tpl.php");
	} else {
		require_once "includes/templates/page/page.tpl.php";
	}
	ob_flush();
?>
