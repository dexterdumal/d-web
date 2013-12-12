$(document).ready(function() {
	/*
	// Este código afeta toda requisição ajax que for feita usando jQuery
	// não é necessário replicar esta operação em nenhuma outra parte
	$("#loading").live('ajaxStart', function(){
	//Quando a requisição começar, Exibe a DIV
	   $(this).show();
	});
	$("#loading").live('ajaxStop', function(){
	//Quando a requisição parar, Esconde a DIV
	   $(this).hide();
	   $('#loading').remove();
	});
	*/
	//$('.dateField').datepicker( $.datepicker.regional[ "br" ] );
		
	// Este código afeta toda requisição ajax que for feita usando jQuery
	// não é necessário replicar esta operação em nenhuma outra parte
	$("#loading").ajaxStart(function(){
	//Quando a requisição começar, Exibe a DIV
	   $(this).show();
	});
	$("#loading").ajaxStop(function(){
	//Quando a requisição parar, Esconde a DIV
	   $(this).hide();
	   $('#loading').remove();
	});

	$('a.externo').live('click', function(){
		$(this).attr('target','_blank');
	});
});