<?php //print_r($output);?>
<?php $dadosAlunos = $output['data'];
	if($output['numRows']>0):	
		print "<ul>";
		foreach($dadosAlunos as $aluno){
			print "<li>&raquo; ".$aluno['nome']."</li>";
		}
		print "</ul>";
	else:?>
	<p>Nenhum aluno inscrito até o momento</p>
<?php endif;?>