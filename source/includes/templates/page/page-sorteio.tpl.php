<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<?php print $head;?>
		<link rel="icon" type="image/png" href="<?php print basePath;?>/imagens/favicon.png" />
	</head>
	<body class="sorteio">
		<div id="principal">
			<div id="conteudo">
				<div id="content">
					<?php $core = new Core();?>
					<?php print $core->getContent($args[0],$args);?>
				</div>
				<div id="lista-sorteados">
					<fieldset>
						<legend>Crianças Sorteadas</legend>
						<div>
						
						</div>
					</fieldset>
				</div>
			</div>
			<div id="rodape">
				<?php print $footer;?>
			</div>
		</div>
		<div id="overlay"></div>
		<div id="detalhes">
			<div id="faixa-topo">
				<h2></h2>
				<span id="link-fechar"></span>
			</div>
			<div id="detalhes-content"></div>
		</div>
		<script type="text/javascript">
			$('#topo h1 a').css('font-size','41px');
			Cufon.replace('h1'); // Works without a selector engine
			Cufon.replace('#topo h1 a'); // Requires a selector engine for IE 6-7, see above
			Cufon.now();
		</script>
		<script type="text/javascript">
		jQuery(function($){
		   $(".date").mask("99/99/9999");
		   $(".phone").mask("(999) 999-9999");
		   $(".tin").mask("99-9999999");
		   $(".ssn").mask("999-99-9999");
		});
		</script>
	</body>
</html>