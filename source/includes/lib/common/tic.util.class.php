<?php
class Util {
	
	function __construct(){
	}	
	
	function __set($var, $val){
        $this->$var = $val;    
    }
	
	static function limpaArray($array){
		foreach($array as $chave=>$valor){
			if($valor==''){ unset($array[$chave]); }
		}
		return $array;
	}
	
	static function tokenize($value){
		$tokenized = strtolower($value);
		$tokenized = ereg_replace("[^A-Za-z0-9]", "", $tokenized );
		$tokenized = str_replace(" ", "_", $tokenized);
		return $tokenized;
	}
	
	static function untokenize($value){
		$untokenized = ucfirst($value);
		$untokenized = str_replace("_", " ", $untokenized);
		return $untokenized;
	}
	
	static function stylize($value){
		$stylized = str_replace("_", "-", $value);
		return $stylized;
	}
	
	static function listaNumerica($inicial, $final, $selecionado=null){
		$lista = "";
		for($i=$inicial;$i<=$final;$i++){
			$lista .="<option value='$i' ".(($i==$selecionado)?"selected='selected'":"").">$i</option>\n";
		}
		return $lista;
	}	
	
	static function clearFormCache($pageRedirect=args){
		header("Location: ".basePath."clear-form?return=$pageRedirect");
	}
	
	static function recursiveRemoveDirectory($path){   
		$dir = new RecursiveDirectoryIterator($path);
		#echo '<h3>'.$dir.'</h3>';

		//Remove all files

		foreach(new RecursiveIteratorIterator($dir) as $file)
		{
			unlink($file);
		}

		//Remove all subdirectories
		foreach($dir as $subDir)
		{
			//If a subdirectory can't be removed, it's because it has subdirectories, so recursiveRemoveDirectory is called again passing the subdirectory as path
			if(!@rmdir($subDir)) //@ suppress the warning message
			{
				recursiveRemoveDirectory($subDir);
			}
		}

		//Remove main directory
		//rmdir($path);
	}
	
	static function retiraAcentos($str, $enc = 'UTF-8'){ 
		$acentos = array(
			'A' => '/&Agrave;|&Aacute;|&Acirc;|&Atilde;|&Auml;|&Aring;/',
			'a' => '/&agrave;|&aacute;|&acirc;|&atilde;|&auml;|&aring;/',
			'C' => '/&Ccedil;/',
			'c' => '/&ccedil;/',
			'E' => '/&Egrave;|&Eacute;|&Ecirc;|&Euml;/',
			'e' => '/&egrave;|&eacute;|&ecirc;|&euml;/',
			'I' => '/&Igrave;|&Iacute;|&Icirc;|&Iuml;/',
			'i' => '/&igrave;|&iacute;|&icirc;|&iuml;/',
			'N' => '/&Ntilde;/',
			'n' => '/&ntilde;/',
			'O' => '/&Ograve;|&Oacute;|&Ocirc;|&Otilde;|&Ouml;/',
			'o' => '/&ograve;|&oacute;|&ocirc;|&otilde;|&ouml;/',
			'U' => '/&Ugrave;|&Uacute;|&Ucirc;|&Uuml;/',
			'u' => '/&ugrave;|&uacute;|&ucirc;|&uuml;/',
			'Y' => '/&Yacute;/',
			'y' => '/&yacute;|&yuml;/',
			'a.' => '/&ordf;/',
			'o.' => '/&ordm;/'
		);

		return preg_replace($acentos, array_keys($acentos), htmlentities($str,ENT_NOQUOTES, $enc));
	}
		
	function arrayToUpper($arrayData){
		foreach($arrayData as $key=>$value){ $arrayUpper[$key] = strtoupper($value); }
		return $arrayUpper;
	}
	
	function montaArrayCampos($strCampos){
		$arrayBruto = explode("}{", $strCampos);
		foreach($arrayBruto as $dadoBruto){
			$chaveValor = explode("::", $dadoBruto);
			$arrayCampos[$chaveValor[0]] = $chaveValor[1];
		}
		return $arrayCampos;
	}
	
	static function procuraDuplicidadeArray($array){
		$contagemValores = array_count_values($array);
		$duplicados = 0;
		foreach($contagemValores as $valores){
			if($valores>1){
				$duplicados++;
			}
		}
		return $duplicados;
	}
	
	static function procuraChaveVaziaArray($array){
		$vazios = 0;
		foreach($array as $valores){
			if($valores==""){
				$vazios++;
			}
		}
		return $vazios;
	}
	
	static function validaArrayNumerico($array){
		$naoNumericos = 0;
		foreach($array as $valores){
			if(!is_numeric($valores)){
				$naoNumericos++;
			}
		}
		return $naoNumericos;
	}
	
	static function textoLimitado($texto, $comprimento, $limitador = "..."){
		return (strlen($texto)>$comprimento?substr($texto,0,$comprimento).$limitador:$texto);
	}
	
	static function preencheArray($array){
		$arrayFull = array();
		foreach($array as $key=>$val){
			$arrayFull[$key] = ($val==null?"NULL":$val);
		}
		return $arrayFull;
	}
	
	static function esvaziaArray($array){
		$arrayEmpty = array();
		foreach($array as $key=>$val){
			if(is_array($val)){
				foreach($val as $pos=>$value){
					$arrayEmpty[$key][$pos] = ($value=="NULL"?"":$value);
				}
			}else{
				$arrayEmpty[$key] = ($val=="NULL"?"":$val);
			}
		}
		return $arrayEmpty;
	}
	
	static function change_case_recursive($arr){ 
		foreach ($arr as $key=>$val){ 
			if (!is_array($arr[$key])){ 
				$arr[$key]=mb_strtoupper($arr[$key]); 
			}else{ 
				$arr[$key]=Util::change_case_recursive($arr[$key]); 
			} 
		} 
		return $arr;    
	} 
}
?>