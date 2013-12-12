<?php //print_r($output);?>
<?php $dados = $output['data'];?>
<?php	if($output['numRows']>0): ?>
	<table id="<?php print $id?>">
		<thead>
			<tr>
				<th>campo 1</th>
				<th>campo 2</th>
				<th>campo 3</th>
				<th>campo 4</th>
				<th>campo 5</th>
				<th>campo 6</th>
			</tr>
		</thead>
		<tbody>
			<?php
				$classBg = "alter";
				foreach($dados as $dado){
					if($classBg == "alter"){ $classBg = ""; } else { $classBg = "alter"; }
					print "<tr class='$classBg'>";
					print "	<td>"."</td>";
					print "	<td>"."</td>";
					print "	<td>"."</td>";
					print "	<td>"."</td>";
					print "	<td>"."</td>";
					print "	<td>"."</td>";
					print "</tr>";
				}
			?>
		</tbody>
	</table>
<?php endif;?>