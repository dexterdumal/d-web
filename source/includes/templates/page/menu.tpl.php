<?php
global $pageURL;
$paramsURL = "";
$paramsURL = $config['system']['serverurl'].$pageURL;
$pos = 0;
$classPos = "";
$bullet = "•";
$item = "";
?>
<ul id="<?php print $id;?>">
	<?php
	foreach($output['data'] as $menuItem){
		if($pos == 0){$classPos="first"; $pos++;}else if($pos == ($output['numRows'])-1){$classPos="last"; $bullet = "";}else{ $classPos=""; $pos++;}
		echo "<li class='item $classPos'><a href='".basePath.$menuItem['link']."' title='".$menuItem['nome_item']."' class='menu ".(($menuItem['idSuperior']!=NULL)?"sub-item":"")." ".(((basePath.$menuItem['link'])==($paramsURL))?"active":"")."'>".$menuItem['nome_item']."</a>$bullet</li>";
	}
	?>
</ul>
