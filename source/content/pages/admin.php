<?php
	global $args;
	//print_r($args);
	$core = new Core();
	$optPage = (isset($args[1])?$args[1]:'null');
	$paramPage = (isset($args[2])?$args[2]:'null');
	switch($optPage){
		case 'edit':
		case 'add':
		case 'list':
			print $core->newGetContent('admin-'.$optPage.'-'.$paramPage.'');
			break;
		case 'null':
			print $core->newGetContent('admin-list-'.$paramPage.'');
			break;
		default:
			print "<h2>Bem vindo à administração</h2 >";
			print $core->newGetContent('nao-encontrado');
			break;
	}

?>