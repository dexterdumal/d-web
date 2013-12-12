<?php
	global $msg;
	$etapaForm = 0;
	if(isset($_POST['btnEnviar'])){
		$dadosForm = $_POST;
		if($_POST['actForm']=="add-projeto"){
			$nomesCampos = Array("nome_projeto"=>"Nome do projeto","departamento"=>"Departamento","assunto"=>"Assunto","descricao"=>"Descrição","vagas_disponiveis"=>"Vagas Disponíveis");
			$security = new Security();
			$msg = $security->validarPreenchimento($nomesCampos,$dadosForm);
			if($msg['status']=='true'){
				$pinc = new Pinc();
				$dadosFormLimpo = Security::antiInjectionArray($dadosForm);
				$novoProjeto = $pinc->cadastrarProjeto($dadosFormLimpo);
				$msg = $novoProjeto['statusMsg'];
				$idProjeto = $novoProjeto['insertId'];
				if($msg['status']==='true'){
					//$dadosForm = Array("nome_projeto"=>"","departamento"=>"","assunto"=>"","descricao"=>"","vagas_disponiveis"=>"");
					$msg['msg'] .= "<br/>Adicione abaixo os professores deste projeto";
					$etapaForm = 1;
				}
			}
		}elseif($_POST['actForm']=="add-professor"){
			print "<pre>";print_r($_POST);print "</pre>";
			$nomesCampos = Array("nome_projeto"=>"Nome do projeto","email"=>"E-mail","telefone"=>"Telefone","lattes"=>"Lattes"); 
			//$security = new Security();
			//$msg = $security->validarPreenchimento($nomesCampos,$dadosForm['dados_professor']);
			//if($msg['status']=='true'){
				$pinc = new Pinc();
				$dadosFormLimpo = Security::antiInjectionArray($dadosForm);
				$novoProjeto = $pinc->cadastrarProfessor($dadosFormLimpo);
				$msg = $novoProjeto['statusMsg'];
				$idProjeto = $novoProjeto['insertId'];
				if($msg['status']==='true'){
					//$dadosForm = Array("nome_projeto"=>"","departamento"=>"","assunto"=>"","descricao"=>"","vagas_disponiveis"=>"");
					$msg['msg'] .= "<br/>Adicione abaixo os professores deste projeto";
				}
				$etapaForm = 1;
			//}
		}
	}else{
		$dadosForm = Array("nome_projeto"=>"","departamento"=>"","assunto"=>"","descricao"=>"","vagas_disponiveis"=>""); 
	}
	print $etapaForm;
	extract($dadosForm);
?>
<h2 class="titulo-internas">Novo Projeto</h2>
<?php if($msg['msg']!=""):?>
	<div id="msg" class="<?php print $msg['class'];?>"><?php print $msg['msg'];?></div>
<?php endif;?>
<?php switch($etapaForm){ case 0:?>
		<form id="add-projeto" method="post" action="<?php print args;?>">
			<fieldset id="dados-projeto">
				<legend>Dados do Projeto</legend>
				<p><label for="nome-projeto">Nome do Projeto</label><input type="text" name="nome_projeto" id="nome-projeto" size="80" maxlength="300" value="<?php print $nome_projeto; ?>"/></p>
				<p><label for="departamento">Departamento</label><input type="text" name="departamento" id="departamento" size="60" maxlength="100" value="<?php print $departamento; ?>"/><span class="legend">Digite os departamentos separando-os por vírgula</span></p>
				<p><label for="assunto">Assunto</label><input type="text" name="assunto" id="assunto" size="80" maxlength="200" value="<?php print $assunto; ?>"/></p>
				<p><label for="descricao">Descrição</label><textarea name="descricao" id="descricao" rows="3" cols="80" maxlength="500"><?php print $descricao; ?></textarea><span id="count-chars"><strong>500</strong> caracteres restantes</span><span class="legend">descreva seu projeto em até 500 caracteres</span></p>
				<p><label for="vagas-disponiveis">Vagas Disponíveis</label><input type="text" name="vagas_disponiveis" id="vagas-disponiveis" size="10" maxlength="4" value="<?php print $vagas_disponiveis; ?>"/></p>
			</fieldset>
			<p>
				<input type="hidden" name="actForm" value="add-projeto"/>
				<input type="submit" name="btnEnviar" id="btn-add-projeto" class="btn submit" value="salvar alterações"/>
			</p>
		</form>
<?php break; case 1:?>
		<form id="add-professor" method="post" action="<?php print args;?>">
			<fieldset id="dados-professor">
				<legend>Professores atuando neste projeto</legend>
				<table id="lista-professores-cadastrados" class="tabela-simples">
					<thead><tr><th>Nome</th><th>E-mail</th><th>Telefone</th><th>ID Lattes</th><th></th></tr></thead>
					<tbody><tr><td colspan="5">Nenhum professor cadastrado até o momento.</td></tr></tbody>
				</table>
				<p><label for="nome-professor">Nome do Professor:</label><input type="text" name="nome_professor[]" id="nome-professor" size="90" maxlength="100" class="required-field" /></p>
				<p class="inline"><label for="email">E-mail:</label><input type="text" name="email[]" id="email" size="50" maxlength="60"  class="required-field" /></p>
				<p class="inline"><label for="telefone">Telefone:</label><input type="text" name="telefone[]" id="telefone" size="15" maxlength="11" /></p>
				<p><label for="lattes">ID Lattes:</label><input type="text" name="lattes[]" id="lattes" size="20" maxlength="16" class="required-field" /></p>
				<p><input type="button" name="btnAddProfessor" id="btn-add-professor" class="btn submit" value="Adicionar Professor"/></p>
			</fieldset>
			<p>
				<input type="hidden" name="actForm" value="add-professor"/>
				<input type="hidden" name="idProjeto" value="<?php print $idProjeto;?>"/>
				<input type="button" name="btnEnviar" id="btn-validate-form" class="btn submit disabled" value="salvar alterações"/>
				<input type="submit" name="btnEnviar" id="btn-submit-form" value="salvar"/>
			</p>
		</form>
		<script type="text/javascript"> 
		//<![CDATA[
			$(document).ready(function(){
				var numProfessores = 0;
				var indexArrayProfessores = 0;
				$("#add-professor").validationEngine();
				
				$("#add-professor #btn-validate-form").click(function(e){
					// For IE:
					//if ($.browser.msie) e.returnValue = false;
					// Otherwise: 
					//if(e.preventDefault) e.preventDefault();
					if(numProfessores==0){
						$(this).validationEngine('showPrompt', 'Adicione, pelo menos um professor neste projeto', 'error');
						$("#dados-professor").effect("highlight", {}, 3000);
						//numProfessores++;
					}else{
					   $("#btn-submit-form").click(); 
					}
				});
		
				$("#btn-add-professor").click(function(){
					var nome_professor = $("#dados-professor #nome-professor").val();
					var email = $("#dados-professor #email").val();
					var telefone = $("#dados-professor #telefone").val();
					var id_lattes = $("#dados-professor #lattes").val();
					var htmlRetorno = "<tr>";
					if((nome_professor=="")||(email=="")||(id_lattes=="")){
						destacarCamposNaoPreenchidos("add-professor #dados-professor");
						$(this).validationEngine('showPrompt', 'Os campos em vermelho devem ser informados', 'error');
						//alert('Todos os campos em vermelho deverão ser informados');
					}else{
						htmlRetorno += "<td>"+nome_professor+"<input type='hidden' name='dados_professor["+indexArrayProfessores+"][nome_professor]' value='"+nome_professor+"'/></td>";
						htmlRetorno += "<td>"+email+"<input type='hidden' name='dados_professor["+indexArrayProfessores+"][email]' value='"+email+"'/></td>";
						htmlRetorno += "<td>"+telefone+"<input type='hidden' name='dados_professor["+indexArrayProfessores+"][telefone]' value='"+telefone+"'/></td>";
						htmlRetorno += "<td>"+id_lattes+"<input type='hidden' name='dados_professor["+indexArrayProfessores+"][id_lattes]' value='"+id_lattes+"'/></td>";
						htmlRetorno += "<td><a href='' title='remover este professor' class='link-remover-professor remove'></a></td>";
						htmlRetorno += "</tr>";
						if(numProfessores==0){
						   $("#lista-professores-cadastrados tbody").html(htmlRetorno);
						   $("#btn-submit-form").removeClass("disabled");
						}else{
						   $("#lista-professores-cadastrados tbody").append(htmlRetorno);
						}
						$('#dados-professor .required-field').removeClass('required-highlight');
						numProfessores++;
						indexArrayProfessores++;
					}
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
<?php default: break; }?>