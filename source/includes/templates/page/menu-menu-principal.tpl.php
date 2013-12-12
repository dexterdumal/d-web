<?php
$pos = 0;
$classPos = "";
$bullet = "";
$item = "";
$pageUrl = serverUrl.args;
?>
<?php if($output['numRows']>0):?>
<ul id="<?php print $id;?>">
	<?php
	foreach($output['data'] as $menuItem){
		if($pos == 0){$classPos="first";}else if($pos == ($output['numRows'])-1){$classPos="last"; $bullet = "";}else{ $classPos=""; }
		$pos++;
		echo "<li class='item $classPos' id='menu-item-$pos'><a href='".basePath.$menuItem['link']."' title='".$menuItem['nome_item']."' class='menu".(($menuItem['idSuperior']!=NULL)?" sub-item":"").(($menuItem['target']=='_blank')?" externo":"").((basePath.$menuItem['link']==$pageUrl)?" active":"")."'>$bullet".$menuItem['nome_item']."</a></li>";
	}
	?>
</ul>
<?php endif;?>
