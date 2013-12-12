<?php
class Core {
	
	function __construct(){
	}	
	
	function __set($var, $val){
        $this->$var = $val;    
    }
	
	function config(){
		print "<pre>";
		print_r($config);
		print "</pre>";
		return true;
	}
	
	static function initialize($args){
		global $config;
		global $pageTitle;
		global $estrutura;
		flush();
		date_default_timezone_set($config['system']['timezone']);
		$core = new Core();
		$estrutura['siteName'] = $config['page']['siteName'];
		$estrutura['pageAttr'] = $core->getPageAttr(strArgs);
		$pageTitle = $estrutura['pageAttr']['titulo'];
		$estrutura['head'] = $core->headGen();
		$estrutura['box_login'] = $core->newGetContent('box-login');
		$estrutura['breadCrumb'] = $core->breadCrumb();
		$arrayMenus = Core::getMenus((isset($_SESSION['perfil'])?$_SESSION['perfil']:0));
		if($arrayMenus['numRows']>0){
			foreach($arrayMenus['data'] as $menu){
				$estrutura[$menu['nome_maquina']] = $core->menuGen($menu['nome_maquina'],((isset($_SESSION['perfil']))?$_SESSION['perfil']:0));
			}
		}
		$estrutura['pageTitle'] = $pageTitle;
		$estrutura['content'] = $core->newGetContent($args[0]);
		$estrutura['footer'] = $core->footerGen($config['page']['baseTitle']);
	}
	
	function getPageAttr($pathAlias){
		$sql = "SELECT * FROM `system_page` WHERE `url` = '$pathAlias';";
		$db = new DBExecutor();
		$attr = $db->db_get($sql);
		if($attr['numRows']>0){ return $attr['data'][0];}else{ return 0;}
	}
	
	function headGen(){
		global $config; 
		global $htmlReturn;
		global $pageTitle;
		$htmlReturn = "";
		$htmlReturn .= "<title>".$pageTitle." | ".$config['page']['siteName']."</title>\n";
		$htmlReturn .= "<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />\n";
		foreach($config['script'] as $script){
			$htmlReturn .= "<script type='text/javascript' src='".basePath."scripts/$script'></script>\n";
		}
		foreach($config['style'] as $style){
			$htmlReturn.= "<link href='".basePath."estilos/$style' rel='stylesheet' type='text/css' media='screen' />\n";
		}
		$htmlReturn .= "<script type='text/javascript'> var basePath = '".basePath."'</script>\n";
		$htmlReturn .= "<link rel='icon' type='image/png' href='".basePath."imagens/favicon.png' />\n";
		
		return $htmlReturn;
	}
	
	static function getMenus($papeis=0){
		//$sqlMenu = "SELECT m.id, m.nome, m.nome_maquina FROM `menu` m WHERE m.`ativo` = 1 AND (SELECT count(mi.id) from menu_itens mi where mi.idMenu=m.id) > 0 AND m.`papel` IN (0,$papeis);";
		$sqlMenu = "SELECT m.id, m.nome, m.nome_maquina FROM `system_menu` m WHERE m.`ativo` = 1 AND m.`papel` IN (0,$papeis);";
		$db = new DBExecutor();
		$menuData = $db->db_get($sqlMenu);
		return $menuData;
	}
	
	function menuGen($nomeMenu, $papeis=0){
		global $htmlReturn;
		global $config;
		//global $pageURL;
		//$paramsURL = "";
		//paramsURL = $config['system']['serverurl'].$pageURL;
		$htmlReturn = "";
		$sqlMenu = "SELECT m.id, m.nome, m.nome_maquina, mi.id, mi.nome as nome_item, mi.link, mi.attr, mi.target, mi.idSuperior, mi.`papel` FROM `system_menu` m left join `system_menu_itens` mi on mi.idMenu = m.id WHERE  m.`nome_maquina` = '$nomeMenu' AND mi.`ativo` = 1 AND mi.`papel` IN (0,".(isset($_SESSION['logado']) && ($_SESSION['logado']=='1')?"1,":"")."$papeis) ORDER BY mi.`peso`;";
		$db = new DBExecutor();
		$menuData = $db->db_get($sqlMenu);
		
		$htmlReturn = $this->applyTemplate($menuData, "menu", Util::stylize($nomeMenu));
		return $htmlReturn;
	}
	
	function footerGen($msg){
		global $htmlReturn;
		$htmlReturn = "";
		include "includes/footer.php";
		return $htmlReturn;
	}
	
	function breadCrumb(){
		global $args;
		return $this->applyTemplate($args, "breadcrumb", 'breadcrumb');
	}
	
	function comboGen($itens, $id, $selecteditem = '', $vazio = ''){
		$nome = str_replace("-","_",$id);
		$htmlReturn = "<select name=\"$nome\" id=\"$id\"> \n";
		if ($vazio){
			$htmlReturn .= "<option>$vazio</option> \n";
		}
		foreach ($itens as $chave => $valor){
			if ($chave == $selecteditem){
				$htmlReturn .= "<option value=\"$chave\" selected=\"selected\">$valor</option> \n";
			} else {
				$htmlReturn .= "<option value=\"$chave\">$valor</option> \n";
			}
		}
		$htmlReturn .= "</select>";
		return $htmlReturn;
	}
	
	function getContent($page,$params=null){
		$file = "content/pages/".$page.".php";
		if(file_exists(realpath($file))) {
			require($file);
		} else {
			require("content/pages/nao-encontrado.php");
		}
	}
	
	function newGetContent($page){
		global $config;
		flush();
		ob_start();
		$file = fileRoot."content/pages/".$page.".php";
		//print $file;
		$content = "";
		if(file_exists(realpath($file))) {
			$content = file_get_contents($file);
		} else {
			$content = file_get_contents(fileRoot."content/pages/nao-encontrado.php"); 
		}
		
		$content = " ?>".$content."<?php ";
		eval($content);
		$withContent = ob_get_clean();
		return preg_replace("/<\?php|<\?|\?>/", "", $withContent);
	}
		
	function applyTemplate($output, $element, $id){
		global $config;
		flush();
		ob_start();
		//ob_flush();
		$file = fileRoot."includes/templates/page/".$element."-".$id.".tpl.php";
		//print $file;
		$template = "";
		if(file_exists(realpath($file))) {
			$template = file_get_contents($file); 
		} else {
			$template = file_get_contents(fileRoot."includes/templates/page/".$element.".tpl.php"); 
		}
		
		$template = " ?>".$template."<?php ";
		eval($template);
		$withTemplate = ob_get_clean();
		return preg_replace("/<\?php|<\?|\?>/", "", $withTemplate);
	}
	
	function applyTemplateEmail($body, $tipoEmail="mail"){
		global $config;
		flush();
		ob_start();
		//ob_flush();
		$file = fileRoot."includes/templates/mail/".$tipoEmail.".tpl.php";
		//print $file;
		$template = "";
		if(file_exists(realpath($file))) {
			$template = file_get_contents($file); 
		} else {
			$template = file_get_contents(fileRoot."includes/templates/mail/".$tipoEmail.".tpl.php"); 
		}
		
		$template = " ?>".$template."<?php ";
		eval($template);
		$withTemplate = ob_get_clean();
		return preg_replace("/<\?php|<\?|\?>/", "", $withTemplate);
	}
	
	static function basePath(){
		global $config;
		define("basePath", $config['system']['basepath']);
		return basePath;
	}
	
	static function getArgs(){
		define("args", $_SERVER['REQUEST_URI']);
		return args;
	}
	
	static function codErroUpload($cod){
		switch($cod){
			case 0:
				$msgRetornoUpload = "Upload concluído com sucesso.";
				break;
			case 1:
				$msgRetornoUpload = "O arquivo no upload é maior do que o limite definido.";
				break;
			case 2:
				$msgRetornoUpload = "O arquivo ultrapassa o limite de tamanho que foi especificado no formulário.";
				break;
			case 3:
				$msgRetornoUpload = "O upload do arquivo foi feito parcialmente.";
				break;
			case 4:
				$msgRetornoUpload = "Não foi feito o upload do arquivo.";
				break;
			default:
				$msgRetornoUpload = "";
				break;				
		}
		return $msgRetornoUpload;
	}
	/*function lista_conteudo($tipoConteudo, $fields=null, $filter=null, $order=null, $adminLinks=false){
		switch($tipoConteudo){
			case "jogador":
				$tipoConteudo = "jogadores";
				break;
			default:
				$tipoConteudo = "jogadores";
				break;				
		}
		
		if($fields){ $sqlFields = "*"; }
		else{ $sqlFields = "*"; }
		
		if($order){ $sqlOrder = " ORDER BY $order"; }
		
		$sql = "SELECT $sqlFields FROM $tipoConteudo $sqlOrder";
		$db = new DBExecutor();
		$conteudo = $db->db_get($sql,true);
		return $arrayConteudo;
	}*/
}
?>
