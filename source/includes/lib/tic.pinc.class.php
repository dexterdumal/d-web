<?php
class Pinc {
	
	function __construct(){
	}	
	
	function __set($var, $val){
        $this->$var = $val;    
    }
	
	/*métodos utilizados em chamadas via AJAX*/
	function AJAX_listarProjetos($params){
		$listaProjetos = $this->listarProjetos($params['tipoFiltro'], $params['termoBusca']);
		$core = new Core();
		return $core->applyTemplate($listaProjetos, "table", "list-projetos");
	}
	
	function AJAX_obterDetalhesProjeto($params){
		$detalhesProjeto['projeto'] = $this->obterDetalhesProjeto($params['idProjeto']);
		$detalhesProjeto['professores'] = $this->listarProfessoresProjeto($params['idProjeto']);
		$detalhesProjeto['alunos'] = $this->listarAlunosProjeto($params['idProjeto']);
		$core = new Core();
		return $core->applyTemplate($detalhesProjeto, "table", "detalhes-projeto");
	}	

	/*métodos chamados naturalmente pelo sistema*/
	function listarProjetos($tipoFiltro, $termoBusca){
		$campoFiltro = "todos";
		switch($tipoFiltro){
			case "titulo_projeto":
				$campoFiltro = "p.`nome_projeto`";
				break;
			case "professor":
				$campoFiltro = "dp.`nome`";
				break;
			case "departamento":
				$campoFiltro = "p.`departamento`";
				break;
			case "assunto":
				$campoFiltro = "p.`assunto`";
				break;
		}
		$sql = "SELECT p.`id`, p.`nome_projeto`, p.`departamento`,p.`assunto`, p.`descricao`, p.`vagas_disponiveis`, dp.`nome` as nome_professor "
				."FROM `projetos` p LEFT OUTER JOIN `dados_professor` dp ON dp.`id_projeto` = p.`id` ";
		if($campoFiltro != "todos"){
			$sql .= "WHERE $campoFiltro like '%".$termoBusca."%' ";
		}
		$sql .= "ORDER BY p.`data_inclusao_projeto` DESC;";
		$db = new DBExecutor();
		return $db->db_get($sql);
	}
	
	function obterDetalhesProjeto($idProjeto){
		//$sql = "SELECT p.*, dp.* "
		//		."FROM `projetos` p LEFT JOIN `dados_professor` dp ON dp.`id_projeto` = p.`id` "
		//		."WHERE p.`id` = $idProjeto;";
		$sql = "SELECT p.* FROM `projetos` p WHERE p.`id` = $idProjeto;";
		$db = new DBExecutor();
		return $db->db_get($sql);
	}
	
	function obterDetalhesProjetoEdit($idProjeto){
		//$sql = "SELECT p.*, dp.* "
		//		."FROM `projetos` p LEFT JOIN `dados_professor` dp ON dp.`id_projeto` = p.`id` "
		//		."WHERE p.`id` = $idProjeto AND p.`id_usuario_criador` = ".$_SESSION['idUsuario'].";";
		$sql = "SELECT p.* FROM `projetos` p WHERE p.`id` = $idProjeto AND p.`id_usuario_criador` = ".$_SESSION['idUsuario'].";";
		$db = new DBExecutor();
		return $db->db_get($sql);
	}
	
	function listarProfessoresProjeto($idProjeto){
		$sql = "SELECT * FROM `dados_professor` WHERE `id_projeto` = $idProjeto ORDER BY `nome`;";
		$db = new DBExecutor();
		return $db->db_get($sql);
	}
	
	function listarAlunosProjeto($idProjeto){
		$sql = "SELECT * FROM `alunos` WHERE `id_projeto` = $idProjeto ORDER BY `nome`;";
		$db = new DBExecutor();
		return $db->db_get($sql);
	}
	
	function listarProjetosProfessor($idProfessor){
		$sql = "SELECT p.`id`, p.`nome_projeto`, p.`departamento`,p.`assunto`, p.`descricao`, p.`vagas_disponiveis`, dp.`nome` as nome_professor "
				."FROM `projetos` p LEFT JOIN `dados_professor` dp ON dp.`id_projeto` = p.`id` "
				."WHERE p.`id_usuario_criador` = $idProfessor "
				."ORDER BY p.`data_inclusao_projeto` DESC;";
		$db = new DBExecutor();
		return $db->db_get($sql);
	}
	
	function cadastrarProjeto($dadosProjeto){
		$sql = "INSERT INTO `projetos` (`nome_projeto`, `departamento`, `assunto`, `descricao`, `vagas_disponiveis`, `data_inclusao_projeto`, id_usuario_criador) "
				."VALUES('".$dadosProjeto['nome_projeto']."', '".$dadosProjeto['departamento']."', '".$dadosProjeto['assunto']."', '".$dadosProjeto['descricao']."', '".$dadosProjeto['vagas_disponiveis']."', CURRENT_TIMESTAMP, ".$_SESSION['idUsuario'].");";
		$db = new DBExecutor();
		$novoProjeto = $db->db_exec($sql);
		return $novoProjeto;
	}
	
	function editarProjeto($dadosProjeto){
		$sql = "UPDATE `projetos` SET"
				."`nome_projeto` = '".$dadosProjeto['nome_projeto']."' "
				.",`departamento` = '".$dadosProjeto['departamento']."' "
				.",`assunto` = '".$dadosProjeto['assunto']."' "
				.",`descricao` = '".$dadosProjeto['descricao']."' "
				.",`vagas_disponiveis` = '".$dadosProjeto['vagas_disponiveis']."' "
				."WHERE `id` = ".$dadosProjeto['id_projeto'].";";
		$db = new DBExecutor();
		$novoProjeto = $db->db_exec($sql);
		return ($novoProjeto['statusMsg']['status']=='true'?true:false) ;
	}
	
	function cadastrarProfessor($arrayProfessores, $idProjeto){
		$sql = "INSERT INTO dados_professor (id_projeto, nome, id_lattes, email, telefone) VALUES ";
			$pos = 0;
			foreach($arrayProfessores as $professor){
				$professor = Util::preencheArray($professor);
				$sql .= "($idProjeto, '".$professor['nome_professor']."', '".$professor['id_lattes']."', '".$professor['email']."', ".$professor['telefone'].")";
				if($pos < (count($arrayProfessores)-1)){ $sql .= ", ";}
				$pos++;
			}
		$db = new DBExecutor();
		$professores = $db->db_exec($sql,true);
		return $professores;
	}
	
	function editarProfessores($arrayProfessores, $idProjeto){
		$sql = "DELETE FROM `dados_professor` WHERE `id_projeto` = $idProjeto";
		$db = new DBExecutor();
		$delete = $db->db_exec($sql);
		if($delete['statusMsg']['status']=='true'){
			$this->cadastrarProfessor($arrayProfessores, $idProjeto);
			return true;
		}else{
			return false;
		}
	}
	
	function cadastrarAlunos($arrayAlunos, $idProjeto){
		$sql = "INSERT INTO alunos (id_projeto, nome, email, data_cadastro) VALUES ";
		$pos = 0;
		foreach($arrayAlunos as $aluno){
			$aluno = Util::preencheArray($aluno);
			$sql .= "($idProjeto, '".$aluno['nome_aluno']."', '".$aluno['email_aluno']."', '".Calendar::acertaData($aluno['data_cadastro'],true)."')";
			if($pos < (count($arrayAlunos)-1)){ $sql .= ", ";}
			$pos++;
		}
		$db = new DBExecutor();
		$alunos = $db->db_exec($sql);
		return $alunos;
	}
	
	function editarAlunos($arrayAlunos, $idProjeto){
		$sql = "DELETE FROM `alunos` WHERE `id_projeto` = $idProjeto";
		$db = new DBExecutor();
		$delete = $db->db_exec($sql);
		if($delete['statusMsg']['status']=='true'){
			$this->cadastrarAlunos($arrayAlunos, $idProjeto);
			return true;
		}else{
			return false;
		}
	}
}
?>