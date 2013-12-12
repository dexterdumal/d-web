<?php

/*Arquivo de configurações gerais do sistema de transferência*/
/*By Felipe Meirelles (Dexter)*/

/*
/*Conexão com banco de dados desenv*/
$config['database']['host'] = "localhost";
$config['database']['user'] = "root";
$config['database']['pass'] = "root";
$config['database']['table'] = "pinc";

/*Conexão com banco de dados teste
$config['database']['host'] = "localhost";
$config['database']['user'] = "pr4_eei";
$config['database']['pass'] = "L9pgNFr0w0tsLs14T82m";
$config['database']['table'] = "pr4_eei";*/

/*Configuração dos dados para envio de email*/
$config['email']['server'] = 'correio.tic.ufrj.br';
$config['email']['port'] = '587';
$config['email']['protocol'] = 'tls';
$config['email']['user'] = 'postmasterweb@tic.ufrj.br';
$config['email']['pass'] = '#tic$carteiroMestre#TIC$610711';
$config['email']['senderMail'] = 'postmasterweb@tic.ufrj.br';
$config['email']['senderName'] = 'PINC';

/*Scripts e folhas de estilo que serão carregadas no header*/
$config['script'][] = "jquery/jquery-1.8.0.min.js";
$config['script'][] = "jquery/jquery.maskedinput-1.3.min.js";
$config['script'][] = "jquery/languages/jquery.validationEngine-pt.js";
$config['script'][] = "jquery/jquery.validationEngine.js";
$config['script'][] = "jquery/jquery.popupWindow.js";
$config['script'][] = "jquery/jquery.tablesorter.min.js";
$config['script'][] = "jquery/jquery.form.js";
$config['script'][] = "jquery/ui/jquery-ui-1.8.23.custom.min.js";
$config['script'][] = "jquery/ui/jquery.ui.datepicker-br.js";
$config['script'][] = "eei.init.js";
$config['script'][] = "scripts.js";
$config['script'][] = "scripts-admin.js";
$config['script'][] = "scripts-ui.js";
$config['script'][] = "ajax.js";
$config['script'][] = "fonts/cufon-yui.js";
$config['script'][] = "fonts/Asenine_400.font.js";

$config['style'][] = "css/reset.css";
$config['style'][] = "css/blue/style.css";
$config['style'][] = "css/estilos.css";
$config['style'][] = "css/ui_themes/jquery.ui.all.css";
$config['style'][] = "css/validationEngine.jquery.css";

/*Configurações do sistema*/
$config['system']['basepath'] = "http://127.0.0.1:81/pinc/";
$config['system']['serverurl'] = "http://127.0.0.1:81/";
$config['system']['fileroot'] = $_SERVER['DOCUMENT_ROOT']."/pinc/";
$config['system']['home'] = "list-projeto";
$config['system']['homeAdmin'] = "admin/list/projeto";

/*configuração de timezone para formatação das datas e complemento ao módulo de envio de emails*/
$config['system']['timezone'] = "Brazil/East";

$config['areas']['header'] = "";
$config['areas']['top'] = "";
$config['areas']['left'] = "";
$config['areas']['content'] = "";
$config['areas']['right'] = "";
$config['areas']['footer'] = "";

$config['page']['siteName'] = "PINC";
$config['page']['baseTitle'] = "Universidade Federal do Rio de Janeiro - PINC";
$config['page']['footerMessage'] = "Universidade Federal do Rio de Janeiro - PINC";

/*configurações de conteudo (futuramente serão gravadas em banco de dados)*/
$config['content']['type'][] = "projeto";
$config['content']['type'][] = "responsavel";
$config['content']['type'][] = "aluno";
$config['content']['type'][] = "usuario";

/*configurações do processo*/
$config['inscricao']['semestre'] = "1";
$config['inscricao']['ano'] = "2014";
$config['inscricao']['dataAberturaInscricao'] = "2013-11-08";
$config['inscricao']['dataEncerramentoInscricao'] = "2013-12-03";
$config['inscricao']['idadeMinima'] = "1";
$config['inscricao']['idadeMaxima'] = "5";
$config['inscricao']['nascMinimo'] = "01/04/2008";
$config['inscricao']['nascMaximo'] = "30/11/2013";

$config['perfil'][0]  = "usuario anonimo";
$config['perfil'][1]  = "usuario autenticado";
$config['perfil'][2]  = "usuario administrador";
//ini_set('default_charset', 'UTF-8');
?>
