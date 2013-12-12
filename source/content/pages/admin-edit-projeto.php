<?php
	global $msg;
	global $args;
	$showForm = true;
	$idProjeto = $args[3];
	$pinc = new Pinc();
	$security = new Security();
	$camposOpcionaisProfessor = Array("telefone");
	$camposOpcionaisAluno = Array("email_aluno");
	$nomesCamposProjeto = Array("nome_projeto"=>"Nome do projeto","departamento"=>"Departamento","assunto"=>"Assunto","descricao"=>"Descrição","vagas_disponiveis"=>"Vagas Disponíveis");
	$nomesCamposProfessor = Array("nome_professor"=>"Nome do professor","email"=>"E-mail","telefone"=>"Telefone","id_lattes"=>"ID Lattes");
	$nomesCamposAluno = Array("nome_aluno"=>"Nome do Aluno","email_aluno"=>"E-mail do Aluno");
	$dadosFormProjeto = Array("nome_projeto"=>"","departamento"=>"","assunto"=>"","descricao"=>"","vagas_disponiveis"=>"");
	$dadosFormProfessor = Array("nome_professor"=>"","email"=>"","telefone"=>"","id_lattes"=>"");
	$dadosFormAlunos = Array("nome_aluno"=>"","email_aluno"=>"");
	
	if(isset($_POST['btnEnviar'])){
		$_POST = Util::change_case_recursive($_POST);
		$validaProjeto = is_array($_POST['dados_projeto'])?$security->validarPreenchimento($nomesCamposProjeto,$_POST['dados_projeto']):$validaProjeto['status']=='false';
		$validaProfessores = is_array($_POST['dados_professor'])?$security->validarPreenchimentoMultivalue($nomesCamposProfessor,$_POST['dados_professor'],$camposOpcionaisProfessor):$validaProjeto['status']=='false';
		$validaAlunos = is_array($_POST['dados_aluno'])?$security->validarPreenchimentoMultivalue($nomesCamposAluno,$_POST['dados_aluno'], $camposOpcionaisAluno):$validaProjeto['status']=='false';
		print "-".$validaProjeto['status'].$validaProfessores['status'].$validaAlunos['status']."-";
		
		if(($validaProjeto['status']=='true') && ($validaProfessores['status']=='true') && ($validaAlunos['status']=='true')){
			$editProjeto = $pinc->editarProjeto($_POST['dados_projeto']);
			print $editProjeto;
			if(is_array($_POST['dados_professor'])){
				$editProfessores = $pinc->editarProfessores($_POST['dados_professor'], $idProjeto);
				print $editProfessores;
			}
			if(is_array($_POST['dados_aluno'])){
				$editAlunos = $pinc->editarAlunos($_POST['dados_aluno'], $idProjeto);
				print $editAlunos;
			}
			if(($editProjeto==true)&&($editProfessores==true)){
				$msg = array('msg'=>'Atualizado com sucesso!','class'=>'sucesso');
			}
		}else{
			$msg['msg'] = "Ocorreu um erro ao tentar atualizar as informações.<br />".$validaProjeto['msg'].$validaProfessores['msg'].$validaAlunos['msg'];
			$msg['class'] = "erro";
		}
	}
	
	$detalhesProjeto = $pinc->obterDetalhesProjetoEdit($idProjeto);
	$listaProfessores = $pinc->listarProfessoresProjeto($idProjeto);
	$listaAlunos = $pinc->listarAlunosProjeto($idProjeto);
	
	if($detalhesProjeto['numRows']>0){
		$dadosFormProjeto = $detalhesProjeto['data'][0];
	}else{
		$msg = array("status"=>"false", "msg"=>"Projeto não encontrado", "class"=>"erro");
		$showForm = false;		
	}
	extract($dadosFormProjeto);
	extract($dadosFormProfessor);
?>
<h2 class="titulo-internas">Editar Projeto</h2>
<?php if($msg['msg']!=""):?>
	<div id="msg" class="<?php print $msg['class'];?>"><?php print $msg['msg'];?></div>
<?php endif;?>
<?php if($showForm==true):?>
	<form id="edit-projeto" method="post" action="<?php print args;?>">
		<ul id="abas-areas">
			<li class="active" rel="dados-projeto">Dados do Projeto</li>
			<li rel="dados-professor">Professores Participantes</li>
			<li rel="dados-alunos">Alunos Inscritos</li>
		</ul>
		
		<!--DADOS DO PROJETO-->
		<fieldset id="dados-projeto" class="aba first">
			<p><label for="nome-projeto">Nome do Projeto</label><input type="text" name="dados_projeto[nome_projeto]" id="nome-projeto" size="80" maxlength="300" value="<?php print $nome_projeto; ?>"/></p>
			<p><label for="departamento">Departamento</label><input type="text" name="dados_projeto[departamento]" id="departamento" size="60" maxlength="100" value="<?php print $departamento; ?>"/></p>
			<p><label for="assunto">Assunto</label><input type="text" name="dados_projeto[assunto]" id="assunto" size="80" maxlength="200" value="<?php print $assunto; ?>"/></p>
			<p><label for="descricao">Descrição</label><textarea name="dados_projeto[descricao]" id="descricao" rows="3" cols="80" maxlength="500"><?php print $descricao; ?></textarea><span id="count-chars"><strong>500</strong> caracteres restantes</span><span class="legend">descreva seu projeto em até 500 caracteres</span></p>
			<p><label for="vagas-disponiveis">Vagas Disponíveis</label><input type="text" name="dados_projeto[vagas_disponiveis]" id="vagas-disponiveis" size="10" maxlength="4" value="<?php print $vagas_disponiveis; ?>"/></p>
		</fieldset>
		
		<!--DADOS DO PROFESSOR-->
		<fieldset id="dados-professor" class="aba">
			<table id="lista-professores-cadastrados" class="tabela-simples">
				<thead><tr><th>Nome</th><th>E-mail</th><th>Telefone</th><th>ID Lattes</th><th>excluir</th></tr></thead>
				<?php 
					if((isset($listaProfessores)) && (count($listaProfessores)>0)){
						$i = 1;
						print "<tbody>";
						foreach($listaProfessores['data'] as $professor){
							print "<tr>";
							print "<td>".$professor['nome']."<input type='hidden' name='dados_professor[$i][nome_professor]' value='".$professor['nome']."'/></td>";
							print "<td>".$professor['email']."<input type='hidden' name='dados_professor[$i][email]' value='".$professor['email']."'/></td>";
							print "<td>".$professor['telefone']."<input type='hidden' name='dados_professor[$i][telefone]' value='".$professor['telefone']."'/></td>";
							print "<td>".$professor['id_lattes']."<input type='hidden' name='dados_professor[$i][id_lattes]' value='".$professor['id_lattes']."'/></td>";
							print "<td><a href='' title='remover este professor' class='link-remover-professor remove' rel='professor'></a></td>";
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
			<p><input type="button" name="btnAddProfessor" id="btn-add-professor" class="btn submit" value="Adicionar Professor"/></p>
		</fieldset>
		
		<!--DADOS DO ALUNO-->
		<fieldset id="dados-alunos" class="aba">
			<table id="lista-alunos-cadastrados" class="tabela-simples">
				<thead><tr><th>Nome</th><th>E-mail</th><th>Data Cadastro</th><th>excluir</th></tr></thead>
				<?php 
					if((isset($listaAlunos)) && ($listaAlunos['numRows']>0)){
						$i = 1;
						print "<tbody>";
						foreach($listaAlunos['data'] as $aluno){
							print "<tr>";
							print "<td>".$aluno['nome']."<input type='hidden' name='dados_aluno[$i][nome_aluno]' value='".$aluno['nome']."'/></td>";
							print "<td>".$aluno['email']."<input type='hidden' name='dados_aluno[$i][email_aluno]' value='".$aluno['email']."'/></td>";
							print "<td>".Calendar::acertaDataExibicao($aluno['data_cadastro'],true)."<input type='hidden' name='dados_aluno[$i][data_cadastro]' value='".$aluno['data_cadastro']."'/></td>";
							print "<td><a href='' title='remover este aluno' class='link-remover-aluno remove' rel='aluno'></a></td>";
							print "</tr>";
							$i++;
						}
						print "</tbody>";
					}else{
						print "<tbody><tr><td colspan='4'>Nenhum aluno cadastrado até o momento.</td></tr>";
					}				
				?>
			</table>
			<p><label for="nome-aluno">Nome do Aluno:</label><input type="text" name="dados_form_aluno[nome_aluno]" id="nome-aluno" value=""  size="90" maxlength="100" class="required-field"/></p>
			<p><label for="email-aluno">E-mail:</label><input type="text" name="dados_form_aluno[email_aluno]" id="email-aluno" size="50" maxlength="60"/></p>
			<p><input type="button" name="btnAddAluno" id="btn-add-aluno" class="btn submit" value="Adicionar Aluno"/></p>
		</fieldset>
		<p>
			<input type="hidden" name="dados_projeto[id_projeto]" value="<?php print $id;?>"/>
			<input type="submit" name="btnEnviar" id="btn-submit-form" class="btn submit" value="salvar alterações"/>
		</p>
	</form>
	<script type="text/javascript"> 
	//<![CDATA[
		$(document).ready(function(){
			var indexArrayProfessores = 0;
			var numProfessores = $('#lista-professores-cadastrados tbody tr').length;
			
			var indexArrayAlunos = 0;
			var numAlunos = $('#lista-alunos-cadastrados tbody tr').length;
			
			$("#edit-projeto").validationEngine();
			$("#edit-projeto").submit(function(e){ //alert(btnAction);
				if((numProfessores==0 || emptyRowColspan == 5)){
					$("#abas-areas li:eq(1)").click();
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
				var emptyRowColspanProfessores = $('#lista-professores-cadastrados tbody tr td:eq(0)').attr('colspan');
				var nome_professor = $("#dados-professor #nome-professor").val();
				var email = $("#dados-professor #email").val();
				var telefone = $("#dados-professor #telefone").val();
				var id_lattes = $("#dados-professor #id_lattes").val();
				var htmlRetorno = "<tr>";
				if((nome_professor=="")||(email=="")||(id_lattes=="")){
					destacarCamposNaoPreenchidos("edit-projeto #dados-professor");
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
			
			$("#btn-add-aluno").click(function(){
				var emptyRowColspanAlunos = $('#lista-alunos-cadastrados tbody tr td:eq(0)').attr('colspan');
				var nome_aluno = $("#dados-alunos #nome-aluno").val();
				var email_aluno = $("#dados-alunos #email-aluno").val();
				var htmlRetorno = "<tr>";
				if(nome_aluno==""){
					destacarCamposNaoPreenchidos("edit-projeto #dados-alunos");
					$(this).validationEngine('showPrompt', 'Os campos em vermelho devem ser informados', 'error');
					//alert('Todos os campos em vermelho deverão ser informados');
				}else{
					var currentdate = new Date(); 
					var datetime = dateToString(currentdate);  
					datetime +=	" "+ currentdate.getHours() + ":" + currentdate.getMinutes() + ":" + currentdate.getSeconds();
					
					htmlRetorno += "<td>"+nome_aluno+"<input type='hidden' name='dados_aluno["+indexArrayAlunos+"][nome_aluno]' value='"+nome_aluno+"'/></td>";
					htmlRetorno += "<td>"+email_aluno+"<input type='hidden' name='dados_aluno["+indexArrayAlunos+"][email_aluno]' value='"+email_aluno+"'/></td>";
					htmlRetorno += "<td>"+datetime+"<input type='hidden' name='dados_aluno["+indexArrayAlunos+"][data_cadastro]' value='"+datetime+"'/></td>";
					htmlRetorno += "<td><a href='' title='remover este professor' class='link-remover-professor remove'></a></td>";
					htmlRetorno += "</tr>";
					if((numAlunos==0)||(emptyRowColspanAlunos==4)){
					   $("#lista-alunos-cadastrados tbody").html(htmlRetorno);
					   //$("#btn-submit-form").removeClass("disabled");
					}else{
					   $("#lista-alunos-cadastrados tbody").append(htmlRetorno);
					}
					$('#dados-alunos .required-field').removeClass('required-highlight');
					numAlunos++;
					indexArrayAlunos++;
				}
			});

			$("#lista-professores-cadastrados tbody .link-remover-professor, #lista-alunos-cadastrados tbody .link-remover-aluno").live('click', function(e){
				e.preventDefault();
				$(this).parent().parent().remove();
				if($(this).attr("rel")=='professor'){ numProfessores--;}
				else if($(this).attr("rel")=='aluno'){ numAlunos--;}
				if(numProfessores==0){$("#btn-submit-form").addClass("disabled");}
			});
		});
	//]]>
	</script>
<?php endif;?>