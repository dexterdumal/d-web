<?php
	session_start();
	require_once "config.php";
	define("fileRoot", $config['system']['fileroot']);
	define("args", $_SERVER['REQUEST_URI']);
	$basePath = $config['system']['basepath'];
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
	
	$classe = ucfirst((isset($_POST['classe'])?$_POST['classe']:""));
	$metodo = (isset($_POST['metodo'])?$_POST['metodo']:"");
	$params = (isset($_POST['params'])?$_POST['params']:"");
	$retorno = "";
	
	if(($classe != "") && ($metodo != "")){
		$reflectionMethod = new ReflectionMethod($classe, "AJAX_".$metodo);
		print $reflectionMethod->invoke(new $classe(), $params);
		//print "<br />rodando...";
		//print_r($params);
	}else{
		print "ocorreu algum erro aqui..";
	}
?>