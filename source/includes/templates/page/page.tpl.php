<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<?php print $head;?>
		<meta http-equiv="Cache-control" content="no-cache" />
		<meta http-equiv="Expires" content="-1" />
		<link type="text/css" rel="stylesheet" href="<?php print basePath;?>estilos/css/estilos-impressao.css" media="print" />
	</head>
	<body class="page" id="page-<?php print($args[0])?$args[0]:"";?>">
		<div id="principal">
			<div id="topo">
				<h1><a href="<?php print basePath.home;?>"><?php print $siteName;?></a></h1>
				<div id="box-login"><?php print $box_login; ?></div>
			</div>
			<div id="conteudo">
				<div id="content">
					<?php if(!$args[0]==home):?>
						<h2 class="titulo-internas"><?php print $pageTitle;?></h2>
					<?php endif;?>
					<?php if(isset($menu_professor)):?>
						<div id="box-menu-admin">
							<?php print $menu_professor;?>
						</div>
					<?php endif;?>
					<?php print $content;?>
				</div>
			</div>
			<div id="rodape">
				<?php print $footer;?>
			</div>
		</div>
		<div id="overlay"></div>
		<div id="detalhes">
			<div id="faixa-topo">
				<h2>.</h2>
				<span id="link-fechar"></span>
			</div>
			<div id="detalhes-content"></div>
		</div>
		<script type="text/javascript">
			function maskFields(){
			jQuery(function($){
			   $(".date").mask("99/99/9999");
			   $(".phone").mask("(999) 999-9999");
			   $(".tin").mask("99-9999999");
			   $(".ssn").mask("999-99-9999");
			   $(".cpf").mask("999.999.999-99");
			});
			}
			$("#loading, #overlay-update").bind("ajaxStart", function(){
				$(this).show();
			}).bind("ajaxStop", function(){
				$(this).fadeOut();
				$('#loading').remove();
			});
			
			$('#topo h1 a').css('font-size','35px').css('line-height','30px');
			Cufon.replace('h1'); // Works without a selector engine
			Cufon.replace('#topo h1 a'); // Requires a selector engine for IE 6-7, see above
			Cufon.now();
		</script>
	</body>
</html>