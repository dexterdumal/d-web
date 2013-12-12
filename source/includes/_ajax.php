<?php
	session_start();
	require_once "config.php";
	$basePath = $config['system']['basepath'];
	require_once "lib/common/tic.core.class.php";
	require_once "lib/common/tic.calendar.class.php";
	require_once "lib/common/tic.connection.class.php";
	require_once "lib/common/tic.db.class.php";
	require_once "lib/common/tic.security.class.php";
	require_once "lib/tic.responsavel.class.php";
	require_once "lib/tic.crianca.class.php";
	require_once "lib/tic.eei.class.php";
	
	$retornoHtml = "";
	switch($_POST['act']){
		case 'detalhesCrianca':
			$crianca = new Crianca();
			$retorno =  $crianca->obterDetalhesCrianca($_POST['idCrianca'],$_SESSION['cpf']);
			if($retorno['numRows'] > 0){
				$dadosCrianca = $retorno['data'][0];
				$retornoHtml = "<form id='frm-edit' action='criancas' method='post'>";
				$retornoHtml .= "<label for='nome_crianca'>Nome da Criança</label><input type='text' name='nome_crianca' size='60' maxlength='100' class='text' value='".$dadosCrianca['nome_crianca']."'/><br />";
				$retornoHtml .= "<label for='data_nascimento'>Nascimento</label><input type='text' name='data_nascimento' size='60' maxlength='10' class='text' value='".Calendar::acertaDataExibicao($dadosCrianca['data_nascimento'])."'/><br />";
				$retornoHtml .= "<label for='nome_pai'>Nome do Pai</label><input type='text' name='nome_pai' size='60' maxlength='100' class='text' value='".$dadosCrianca['nome_pai']."'/><br />";
				$retornoHtml .= "<label for='nome_mae'>Nome da Mãe</label><input type='text' name='nome_mae' size='60' maxlength='100' class='text' value='".$dadosCrianca['nome_mae']."'/><br />";
				$retornoHtml .= "<label for='registro'>Lugar de Registro (Cartório / Circunscrição)</label><input type='text' name='registro' size='60' maxlength='30' class='text' value='".$dadosCrianca['registro']."'/><br />";
				$retornoHtml .= "<label for='livro' class='flutuante'>Livro</label><input type='text' name='livro' size='15' maxlength='10' class='text' value='".$dadosCrianca['livro']."'/>";
				$retornoHtml .= "<label for='folha' class='flutuante'>Folha</label><input type='text' name='folha' size='10' maxlength='6' class='text' value='".$dadosCrianca['folha']."'/>";
				$retornoHtml .= "<label for='termo' class='flutuante'>Termo</label><input type='text' name='termo' size='10' maxlength='9' class='text' value='".$dadosCrianca['termo']."'/><br />";
				$retornoHtml .= "<label for='cpf_responsavel'>CPF do Responsável</label><input type='text' name='cpf_responsavel' value='".$dadosCrianca['cpf_responsavel']."' disabled='disabled' class='text'/><a class='link-menor' href='".$basePath."responsavel/edit/".$dadosCrianca['cpf_responsavel']."' title='Editar informações do responsável'>[editar informações do responsável]</a><br />";
				$retornoHtml .= "<label for='id_grupo'>Grupo</label><input type='hidden' name='id_grupo' value='".$dadosCrianca['id_grupo']."'/>".Crianca::nomeGrupo($dadosCrianca['id_grupo'])."<br />";
				//$retornoHtml .= "<label for='numero'>Número</label><input type='hidden' name='numero' value='".$dadosCrianca['numero']."'/>".$dadosCrianca['numero']."<br />";
				$retornoHtml .= "<input type='hidden' name='id_crianca' value='".$_POST['idCrianca']."'/>";
				$retornoHtml .= "<input type='hidden' name='acao' value='update'/>";
				$retornoHtml .= "<p class='centralizado'><input type='button' name='btnEnviar' id='btnEnviar' value='salvar' class='btn'/></p>";
				$retornoHtml .= "</form>";
			}else{
				$retornoHtml .= "<p>Nenhuma criança encontrada com os critérios selecionados</p>";
			}
			
			/*
			<label for='nome-crianca'>Nome da Criança:</label><input type='text' id='nome-crianca' name='nome_crianca' size='80' maxlength='100' class='text'/>
		<label for='diaNasc'>Data de Nascimento:</label>
			<select name='dataNasc[dia]' id='diaNasc' class='validate[required]'><option value=''>--dia--</option><?php print Core::listaNumerica(1, 30);?></select>
			<select name='dataNasc[mes]' id='mesNasc' class='validate[required]'><option value=''>--mês--</option><?php print Core::listaMeses(null, 1);?></select>
			<select name='dataNasc[ano]' id='anoNasc' class='validate[required]'><option value=''>--ano--</option><?php print Core::listaAnoDecrescente(2012,2007, null);?></select>
		<!--input type='text' id='data-nascimento' name='data_nascimento' size='15' maxlength='15' class='text' value='<?php print $data_nascimento;?>'/-->
		<label for='nome-pai'>Nome do Pai:</label><input type='text' id='nome-pai' name='nome_pai' size='80' maxlength='100' class='text'/>
		<label for='nome-mae'>Nome da Mãe:</label><input type='text' id='nome-mae' name='nome_mae' size='80' maxlength='100' class='text'/>
	</fieldset>
	<fieldset id='dados-certidao'>
		<legend>Dados da Certidão de Nascimento</legend>
		<label for='registro'>Lugar de Registro (Cartório / Circunscrição):</label><input type='text' id='registro' name='registro' size='60' maxlength='30' class='text'/>
		<label for='livro'>Livro:</label><input type='text' id='livro' name='livro' size='15' maxlength='10' class='text'/>
		<label for='folha'>Folha:</label><input type='text' id='folha' name='folha' size='10' maxlength='6' class='text'/>
		<label for='termo'>Termo:</label><input type='text' id='termo' name='termo' size='10' maxlength='9' class='text'/>
		<input type='hidden' name='acao' value='preview'/>
			*/
			break;
		case 'obterNomeSorteio':
			$crianca = new Crianca();
			$retorno =  $crianca->obterCriancaSorteio($_POST['idGrupo'], $_POST['numeroCrianca']);
			if($retorno['numRows'] > 0){
				$dadosCrianca = "<h1>".$retorno['data'][0]['nome_crianca']."</h1>";
				$dadosCrianca .= "<input type='hidden' name='id_crianca_sorteada' id='id_crianca_sorteada' value='".$retorno['data'][0]['id']."'/>";
				$dadosCrianca .= "<p><input type='radio' name='rb_status_vaga' class='rb_status_vaga' id='vaga_efetiva' value='1'/><label for='vaga_efetiva'>Vaga Efetiva</label>";
				$dadosCrianca .= "<input type='radio' name='rb_status_vaga' class='rb_status_vaga' id='vaga_espera' value='2'/><label for='vaga_espera'>Lista de Espera</label></p>";
				$dadosCrianca .= "<p><a href='#' title='confirmar seleção' class='btn' id='btn-confirma-sorteado'>confirmar seleção</a></p>";
			}else{
				$dadosCrianca = "<p>Nenhuma criança encontrada com os critérios selecionados</p>";
			}
			$retornoHtml = $dadosCrianca;
			break;
		case 'confirmarSelecao':
			$eei = new EEI();
			$idCrianca = $_POST['id_crianca'];
			$status = $_POST['status_vaga'];
			$sorteado = $eei->confirmarSelecao($idCrianca, $status);
			$retornoHtml = $sorteado;
			break;
		case 'adicionarNomeNaLista':
			$crianca = new Crianca();
			$retorno =  $crianca->obterCriancaSorteioPorID($_POST['id_crianca']);
			$retornoHtml = "<div class='grupo-".$retorno['data'][0]['id_grupo']." status".$_POST['status_vaga']."' style='display:none;'>".Crianca::nomeGrupo($retorno['data'][0]['id_grupo'])." - ".$retorno['data'][0]['numero']." | ".$retorno['data'][0]['nome_crianca']."</div>";
			break;
		default:
			$retornoHtml = 'nada selecionado';
	}
	
	print $retornoHtml;
?>