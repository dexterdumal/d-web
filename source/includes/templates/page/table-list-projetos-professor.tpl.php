<?php //print_r($output);?>
<?php $dados = $output['data'];?>
<?php	if($output['numRows']>0): ?>
	<table id="<?php print $id?>" class="tabela-simples">
		<thead>
			<tr>
				<th>Titulo do Projeto</th>
				<th>Professor</th>
				<th>Departamento</th>
				<th>Assunto</th>
				<th>Vagas</th>
				<th>detalhes</th>
				<th>editar</th>
			</tr>
		</thead>
		<tbody>
			<?php
				$classBg = "alter";
				foreach($dados as $dado){
					if($classBg == "alter"){ $classBg = ""; } else { $classBg = "alter"; }
					print "<tr class='$classBg' rel='".$dado['id']."'>";
					print "	<td>".$dado['nome_projeto']."</td>";
					print "	<td>".$dado['nome_professor']."</td>";
					print "	<td>".$dado['departamento']."</td>";
					print "	<td>".$dado['assunto']."</td>";
					print "	<td>".$dado['vagas_disponiveis']."</td>";					
					print "	<td><a href='#' class='view' title='ver detalhes deste projeto'></a></td>";
					print "	<td><a href='".basePath."admin/edit/projeto/".$dado['id']."' class='edit' title='editar este projeto'></a></td>";
					print "</tr>";
				}
			?>
		</tbody>
	</table>
<?php else:?>
	<p>Nenhum projeto cadastrado até o momento</p>
<?php endif;?>