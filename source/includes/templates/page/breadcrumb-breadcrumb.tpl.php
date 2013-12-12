<?php
global $args;
global $htmlReturn;
$url = "";
if(count($args)>1){
	foreach($args as $key=>$arg){
		$url .= "/".$arg;
		if($key<(count($args)-1)){ $htmlReturn .= "$arg &#187; "; }
		else{ $htmlReturn .= $arg; }
	}
}
print $htmlReturn;
?>