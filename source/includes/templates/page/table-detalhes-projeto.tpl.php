<?php $dadosProjeto = $output['projeto']['data'][0];?>
<?php $dadosProfessoresProjeto = $output['professores']['data'];?>
<?php $dadosAlunosProjeto = $output['alunos'];?>	
<pre><?php //print_r($dadosProfessoresProjeto);?></pre>
<?php	if($output['projeto']['numRows']>0): ?>
	<h2><?php print $dadosProjeto['nome_projeto'];?></h2>
	<p><span class="legend">Descrição:</span><?php print $dadosProjeto['descricao'];?></p>
	<fieldset class="lista-professores-projeto">
		<legend>Professores participantes deste projeto</legend>
		<?php foreach($dadosProfessoresProjeto as $professor){?>
			<h3><?php print $professor['nome'];?></h3>
			<p><span class="legend">Currículo Lattes:</span><a href="http://lattes.cnpq.br/<?php print $professor['id_lattes'];?>" class="externo">http://lattes.cnpq.br/<?php print $professor['id_lattes'];?></a></p>
			<p class="inline first"><span class="legend">Email:</span><a href="mailto:<?php print $professor['email'];?>"><?php print $professor['email'];?></a></p>
			<p class="inline"><span class="legend">Telefone</span><?php print $professor['telefone'];?></p>
			<hr/>
		<?php }?>
	</fieldset>
	<fieldset class="lista-alunos-projeto">
		<legend>Alunos(<?php print $output['alunos']['numRows']."/".$dadosProjeto['vagas_disponiveis'];?>)</legend>
		<?php 
			$core = new Core();
			print $core->applyTemplate($dadosAlunosProjeto, "list", "alunos");
		?>	
	</fieldset>
<?php else:?>
	<p>Nenhum projeto cadastrado até o momento</p>
<?php endif;?>