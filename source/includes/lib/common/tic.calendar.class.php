<?php
class Calendar {
	
	function __construct(){
	}	
	
	function __set($var, $val){
        $this->$var = $val;    
    }
	
	/*static function acertaData($data){
		$data = explode("/",$data);
		$dataCerta = $data[2]."-".$data[1]."-".$data[0];
		return $dataCerta;     
	}*/
	
	static function acertaData($data, $hora=false){
		$date = new DateTime($data);
		return $date->format('Y-d-m'.(($hora==true)?' H:i:s':''));     
	}
	
	static function acertaDataArray($arrayData){
		$dataCerta = $arrayData['ano']."-".$arrayData['mes']."-".$arrayData['dia'];
		return $dataCerta;     
	}

	/*static function acertaDataExibicao($data){
		$data = explode("-",$data);
		$dataCerta = $data[2]."/".$data[1]."/".$data[0];
		return $dataCerta;   
	}*/
	
	static function acertaDataExibicao($data, $hora=false){
		/*$data = explode("-",$data);
		$dataCerta = $data[2]." / ".$data[1]." / ".substr($data[0], -2);
		return $dataCerta;  */
		$date = new DateTime($data);
		return $date->format('d/m/Y'.(($hora==true)?' H:i:s':'')); 
	}
	
	static function listaMeses($selecionado=null, $compacto=0){
		$lista = "";
		for($i=1;$i<=12;$i++){
			$lista .="<option value='$i' ".(($i==$selecionado)?"selected='selected'":"").">".Calendar::obterNomeMes($i,$compacto)."</option>\n";
		}
		return $lista;	
	}
	
	static function listaMesesArray($selecionado=null, $compacto=0){
		$lista = Array();
		for($i=1;$i<=12;$i++){
			$lista[$i] = Calendar::obterNomeMes($i,$compacto);
		}
		return $lista;	
	}
	
	static function listaAnoDecrescente($anoInicial, $anoFinal, $selecionado=null){
		$lista = "";
		for($i=0; $i<=($anoInicial-$anoFinal); $i++){
			$lista .= "<option value='".(date("Y")-($i))."' ".(((date("Y")-($i))==$selecionado)?"selected='selected'":"").">".(date("Y")-($i))."</option>\n";
		}		
		return $lista;
	}
	
	static function listaSemanasDoMes($mes, $ano){
		$diasNoMes = date('t', mktime(1, 1, 1, $mes, 1, $ano));
		$listaSemanas = "";
		$numeroSemana = 1;
		$primeiroDomingo = 0;
		/*PRIMEIRO LOOP PARA MONTAR A SEMANA DE AJUSTE - TODAS AS SEGUINTES COMEÇAM A PARTIR DE 2ª FEIRA*/
		$dia=1;
		for($dia=1;$dia<=7;$dia++){
			if(date("N", mktime(1, 1, 1, $mes, $dia, $ano))==7){ $primeiroDomingo = $dia;} 
		}
		$listaSemanas .= '<a href="#" id="dia1">Semana 1 (<span> Dia 01 - 0'.($primeiroDomingo).' </span>)</a>';
		$numeroSemana++;
		for($dia=($primeiroDomingo+1);$dia<=$diasNoMes;$dia++){
			$diaInicio = 0;
			$diaFim = 0;
			if(date("N", mktime(1, 1, 1, $mes, $dia, $ano))==1){
				$diaInicio = (($dia<10)?'0'.$dia:$dia);
				$listaSemanas .= '<a href="#" id="dia'.$dia.'">Semana '.$numeroSemana.' (<span> Dia '.$diaInicio;
				$numeroSemana++;
			}elseif(date("N", mktime(1, 1, 1, $mes, $dia, $ano))==7){
				$diaFim = (($dia<10)?'0'.$dia:$dia);
				$listaSemanas .= ' - '.$diaFim.' </span>)</a>';
			}
			if(($dia==$diasNoMes)&&(date("N", mktime(1, 1, 1, $mes, $dia, $ano))!=7)){ 
				$diaFim = $dia;
				$listaSemanas .= ' - '.$diaFim.' </span>)</a>';
			}
		}
		return $listaSemanas;
	}
	
	static function inicioFimSemana($diaInicio, $mes, $ano){
		for($dia=$diaInicio;$dia<=($diaInicio+7);$dia++){
			if(date("N", mktime(1, 1, 1, $mes, $dia, $ano))==7){ $diaFim = $dia;} 
		}
		$diasSemana = array('inicio' => $diaInicio, 'fim' =>$diaFim);
		return $diasSemana;
	}
	
	static function listaDiasSemana($numeroSemana, $mes, $ano){
		$primeiroDomingo = ($numeroSemana+7);
		for($dia=$numeroSemana;$dia<=7;$dia++){
			if(date("N", mktime(1, 1, 1, $mes, $dia, $ano))==7){ $primeiroDomingo = ($dia+1);} 
		}
		$diasSemana = "";
		$diasNoMes = date('t', mktime(1, 1, 1, $mes, 1, $ano));
		for($dia = $numeroSemana; $dia<$primeiroDomingo; $dia++){
			if($dia<=$diasNoMes){
				$diasSemana .= '<a href="#" id="dia-'.$dia.'">'.Core::traduzDiaSemana(date('l',mktime(1, 1, 1, $mes, $dia, $ano))).' (<span> '.date('d / m / y',mktime(1, 1, 1, $mes, $dia, $ano)).'</span> )</a>';
			}
		}
		return $diasSemana;
	}
	
	static function traduzDiaSemana($nomeDia){
		switch (strtolower($nomeDia)) {
			case 'monday':
				$nomeMes =  "2ª feira";
				break;
			case 'tuesday':
				$nomeMes =  "3ª feira";
				break;
			case 'wednesday':
				$nomeMes =  "4ª feira";
				break;
			case 'thursday':
				$nomeMes =  "5ª feira";
				break;
			case 'friday':
				$nomeMes =  "6ª feira";
				break;
			case 'saturday':
				$nomeMes =  "Sáb.";
				break;
			default: //sunday
				$nomeMes =  "Dom.";
				break;
			
		}
		return $nomeMes;
	}
	
	static function obterNomeMes($numMes,$compacto=0){
		switch ($numMes) {
			case 1:
				$nomeMes =  "Janeiro";
				break;
			case 2:
				$nomeMes =  "Fevereiro";
				break;
			case 3:
				$nomeMes =  "Março";
				break;
			case 4:
				$nomeMes =  "Abril";
				break;
			case 5:
				$nomeMes =  "Maio";
				break;
			case 6:
				$nomeMes =  "Junho";
				break;
			case 7:
				$nomeMes =  "Julho";
				break;
			case 8:
				$nomeMes =  "Agosto";
				break;
			case 9:
				$nomeMes =  "Setembro";
				break;
			case 10:
				$nomeMes =  "Outubro";
				break;
			case 11:
				$nomeMes =  "Novembro";
				break;			
			default:
				$nomeMes =  "Dezembro";
				break;
		}
		if($compacto==1){ return substr($nomeMes,0,3); }
		else{ return $nomeMes; }
	}
	
	static function timeDiff($dataInicio, $dataFim){
		$to_time = strtotime($dataFim);
		$from_time = strtotime($dataInicio);
		return date("h",strtotime(round(abs($to_time - $from_time),2)));
	}
	
	static function timeAdd($hora, $acrescimo){
		$hora = date("H:m",$hora);
		return date("H",strtotime("$hora  + $acrescimo hours"));
	}
}
?>