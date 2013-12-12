<?php $dadosProjeto = $output['projeto']['data'][0];?>
<?php $dadosAlunosProjeto = $output['alunos'];?>	
<?php	if($output['projeto']['numRows']>0): ?>
	<form id="edit-projeto" method="post" action="<?php print args;?>">
		<p><label for="nome-projeto">Nome do Projeto</label><input type="text" name="nome_projeto" id="nome-projeto" value="<?php print ($dadosProjeto['nome_projeto'])?$dadosProjeto['nome_projeto']:""; ?>"/></p>
		<p><label for="departamento">Departamento</label><input type="text" name="departamento" id="departamento" value="<?php print ($dadosProjeto['departamento'])?$dadosProjeto['departamento']:""; ?>"/></p>
		<p><label for="assunto">Assunto</label><input type="text" name="assunto" id="assunto" value="<?php print ($dadosProjeto['assunto'])?$dadosProjeto['assunto']:""; ?>"/></p>
		<p><label for="descricao">Descrição</label><textarea name="descricao" id="descricao" rows="3" cols="80"><?php print ($dadosProjeto['descricao'])?$dadosProjeto['descricao']:""; ?></textarea></p>
		<p><label for="vagas-disponiveis">Vagas Disponíveis</label><input type="text" name="vagas_disponiveis" id="vagas-disponiveis" value="<?php print ($dadosProjeto['vagas_disponiveis'])?$dadosProjeto['vagas_disponiveis']:""; ?>"/></p>
		<fieldset class="lista-alunos-projeto">
			<legend>Alunos(<?php print $output['alunos']['numRows']."/".$dadosProjeto['vagas_disponiveis'];?>)</legend>
			<?php 
				$core = new Core();
				print $core->applyTemplate($dadosAlunosProjeto, "list", "alunos");
			?>	
		</fieldset>
		<p>
			<input type="hidden" name="id_projeto" id="id-projeto" value="<?php print $dadosProjeto['id'];?>"/>
			<input type="submit" name="btnEnviar" class="btn submit" value="salvar alterações"/>
		</p>
	
	
	[data_inclusao_projeto] => 2013-11-13 15:53:08 

	[id_projeto] => 7 
	[nome] => Marcello 
	[id_lattes] => 24242424242424 
	[email] => testse@teste.com 
	[telefone] => 987654321 )
	</form>
<?php else:?>
	<p>Projeto não encontrado</p>
<?php endif;?>