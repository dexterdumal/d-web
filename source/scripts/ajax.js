function rodarFuncaoAjax(classe, metodo, params, elementoOrigem, elementoDestino){
	$(elementoOrigem).after("<span id='loading'></span>");
	$.ajax({
		url: basePath+"includes/ajax.php",
		//url: "ajax.php",
		type: "POST",
		data: {
			classe: classe,
			metodo: metodo,
			params: params
			}
	}).done(function( msg ) {
		if(elementoDestino!=null){
			$(elementoDestino).html(msg);
		}
		$('#loading').remove();
	});
	return true;
}