<?php
	global $msg;
	$security = new Security();
	if(isset($_POST['btnEnviar'])){ //enviando o form completo
		print $_POST['formId'];
		//print "<pre>";print_r($_POST);print "</pre>";
		$nomesCampos = Array("nome_projeto"=>"Nome do projeto","departamento"=>"Departamento","assunto"=>"Assunto","descricao"=>"Descrição","vagas_disponiveis"=>"Vagas Disponíveis");
		$dadosProjeto = $_POST['dados_projeto'][0];
		$dadosProfessor = isset($_POST['dados_professor'])?$_POST['dados_professor']:Array();
		$dadosFormProfessor = Array("nome_professor"=>"","email"=>"","telefone"=>"","id_lattes"=>"");
		$msg = $security->validarPreenchimento($nomesCampos,$dadosProjeto);
		if($msg['status']=='true'){
			$pinc = new Pinc();
			$novoProjeto = $pinc->cadastrarProjeto($dadosProjeto);
			if($novoProjeto['statusMsg']['status']=='true'){
				$novoProfessor = $pinc->cadastrarProfessor($dadosProfessor, $novoProjeto['insertId']);
				if($novoProfessor['statusMsg']['status']=='true'){
					$msg = $novoProfessor['statusMsg'];
					$dadosProjeto = Array("nome_projeto"=>"","departamento"=>"","assunto"=>"","descricao"=>"","vagas_disponiveis"=>""); 
					$dadosProfessor = Array();
				}
			}
		}
	}elseif(isset($_POST['btnAddProfessor'])){ //adicionando e removendo professores, antes de salvar o projeto
		//print "<pre>";print_r($_POST);print "</pre>";
		$nomesCampos = Array("nome_professor"=>"Nome do professor","email"=>"E-mail","telefone"=>"Telefone","id_lattes"=>"ID Lattes");
		$dadosProjeto = $_POST['dados_projeto'][0];
		$dadosProfessor = isset($_POST['dados_professor'])?$_POST['dados_professor']:Array();
		$dadosFormProfessor = $_POST['dados_form_professor'];
		$msg = $security->validarPreenchimento($nomesCampos,$dadosFormProfessor);
		if($msg['status']=='true'){
			$dadosProfessor[] = $dadosFormProfessor;
			$dadosFormProfessor = Array("nome_professor"=>"","email"=>"","telefone"=>"","id_lattes"=>"");
			$msg['msg'] = "Professor foi adicionado ao projeto.<br />Para confirmar esta inclusão, não esqueça de clicar em 'salvar' no final desta página.";
		}
	}else{
		$dadosProjeto = Array("nome_projeto"=>"","departamento"=>"","assunto"=>"","descricao"=>"","vagas_disponiveis"=>"");
		$dadosProfessor = Array();
		$dadosFormProfessor = Array("nome_professor"=>"","email"=>"","telefone"=>"","id_lattes"=>"");
	}
	extract($dadosProjeto);
	extract($dadosFormProfessor);
?>
<h2 class="titulo-internas">Novo Projeto</h2>
<?php if($msg['msg']!=""):?>
	<div id="msg" class="<?php print $msg['class'];?>"><?php print $msg['msg'];?></div>
<?php endif;?>
<form id="add-projeto" method="post" action="<?php print args;?>">
	<fieldset id="dados-projeto">
		<legend>Dados do Projeto</legend>
		<p><label for="nome-projeto">Nome do Projeto</label><input type="text" name="dados_projeto[0][nome_projeto]" id="nome-projeto" size="80" maxlength="300" value="<?php print $nome_projeto; ?>"/></p>
		<p><label for="departamento">Departamento</label><input type="text" name="dados_projeto[0][departamento]" id="departamento" size="60" maxlength="100" value="<?php print $departamento; ?>"/><span class="legend">Digite os departamentos separando-os por vírgula</span></p>
		<p><label for="assunto">Assunto</label><input type="text" name="dados_projeto[0][assunto]" id="assunto" size="80" maxlength="200" value="<?php print $assunto; ?>"/></p>
		<p><label for="descricao">Descrição</label><textarea name="dados_projeto[0][descricao]" id="descricao" rows="3" cols="80" maxlength="500"><?php print $descricao; ?></textarea><span id="count-chars"><strong>500</strong> caracteres restantes</span><span class="legend">descreva seu projeto em até 500 caracteres</span></p>
		<p><label for="vagas-disponiveis">Vagas Disponíveis</label><input type="text" name="dados_projeto[0][vagas_disponiveis]" id="vagas-disponiveis" size="10" maxlength="4" value="<?php print $vagas_disponiveis; ?>"/></p>
	</fieldset>
	<fieldset id="dados-professor">
		<legend>Professores atuando neste projeto</legend>
		<table id="lista-professores-cadastrados" class="tabela-simples">
			<thead><tr><th>Nome</th><th>E-mail</th><th>Telefone</th><th>ID Lattes</th><th></th></tr></thead>
			<?php 
				if((isset($dadosProfessor)) && (count($dadosProfessor)>0)){
					$i = 1;
					print "<tbody>";
					foreach($dadosProfessor as $professor){
						print "<tr>";
						print "<td>".$professor['nome_professor']."<input type='hidden' name='dados_professor[$i][nome_professor]' value='".$professor['nome_professor']."'/></td>";
						print "<td>".$professor['email']."<input type='hidden' name='dados_professor[$i][email]' value='".$professor['email']."'/></td>";
						print "<td>".$professor['telefone']."<input type='hidden' name='dados_professor[$i][telefone]' value='".$professor['telefone']."'/></td>";
						print "<td>".$professor['id_lattes']."<input type='hidden' name='dados_professor[$i][id_lattes]' value='".$professor['id_lattes']."'/></td>";
						print "<td><a href='' title='remover este professor' class='link-remover-professor remove'></a></td>";
						print "</tr>";
						$i++;
					}
					print "</tbody>";
				}else{
					print "<tbody><tr><td colspan='5'>Nenhum professor cadastrado até o momento.</td></tr>";
				}				
			?>
		</table>
		<p><label for="nome-professor">Nome do Professor:</label><input type="text" name="dados_form_professor[nome_professor]" id="nome-professor" size="90" maxlength="100" class="required-field" value="<?php print $nome_professor;?>" /></p>
		<p class="inline"><label for="email">E-mail:</label><input type="text" name="dados_form_professor[email]" id="email" size="50" maxlength="60"  class="required-field" value="<?php print $email;?>" /></p>
		<p class="inline"><label for="telefone">Telefone:</label><input type="text" name="dados_form_professor[telefone]" id="telefone" size="15" maxlength="11" value="<?php print $telefone;?>"/></p>
		<p><label for="id_lattes">ID Lattes:</label><input type="text" name="dados_form_professor[id_lattes]" id="id_lattes" size="20" maxlength="16" class="required-field" value="<?php print $id_lattes;?>"/></p>
		<p><input type="submit" name="btnAddProfessor" id="btn-add-professor" class="btn submit" value="Adicionar Professor"/></p>
	</fieldset>
	<p>
		<input type="hidden" name="formId" value="<?php print md5(date('hms'));?>"/>
		<input type="submit" name="btnEnviar" id="btn-submit-form" class="btn submit<?php print (count($dadosProfessor)<1?" disabled":"");?>" value="salvar"/>
	</p>
</form>
<script type="text/javascript"> 
//<![CDATA[
	$(document).ready(function(){
		var numProfessores = $('#lista-professores-cadastrados tbody tr').length;
		var emptyRowColspan = $('#lista-professores-cadastrados tbody tr td:eq(0)').attr('colspan');
		var btnAction = 0;
		$("#add-projeto").validationEngine();
		$("#add-projeto").submit(function(e){ //alert(btnAction);
			if((btnAction==2) && (numProfessores==0 || emptyRowColspan == 5)){
				$("#btn-submit-form").validationEngine('showPrompt', 'Adicione, pelo menos um professor neste projeto', 'error');
				$("#dados-professor").effect("highlight", {}, 3000);
			}else{
			   return; 
			}
			// For IE:
			if ($.browser.msie) e.returnValue = false;
			// Otherwise: 
			if(e.preventDefault) e.preventDefault();
		});

		$("#btn-add-professor").click(function(){
			btnAction = 1
		});
		
		$("#btn-submit-form").click(function(){
			btnAction = 2
		});
		
		$("#lista-professores-cadastrados tbody .link-remover-professor").live('click', function(e){
			e.preventDefault();
			$(this).parent().parent().remove();
			numProfessores--;
			if(numProfessores==0){$("#btn-submit-form").addClass("disabled");}
		});
	});
//]]>
</script>