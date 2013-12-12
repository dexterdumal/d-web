$(document).ready(function() {
	$("#lista-inscritos td").click(function(){
		if(!$(this).hasClass("subtitulo")){
			getDetalhes($(this).parent().find('td.first').text());
		}
	});
	$("td").live('mouseover',function(){
		$(this).parent().css("background-color","#b8d4ea");
	});
	$("td").live('mouseout',function(){
		$(this).parent().css("background-color","#FFFFFF");
	});
	$("#lista.cursos td").live('click',function(){
		//var url = 'editar-curso.php?id='+$(this).find('input').val();
		//$('iframe#frm-editar').attr('src','editar-curso.php?id='+$(this).parent().find('td.first input.cod-curso').val());
		montaFormEditarCurso($(this).parent().find('td.first input.cod-curso').val());
		$('#frm-editar').animate({ height: '365px' });
	});
	$("div#frm-editar .btnFechar").live('click', function(){
		$('#frm-editar').animate({ height: '0px' });
	});
	$('form#frm-edit-curso input.submit').live('click',function(event){
		event.preventDefault();
		atualizaDadosCurso();
	});
	/*$("#lista.cursos td").mouseout(function(){
		$('iframe#frm-editar').slideDown();
	});*/
	$("div#overlay").click(function(){
		$("div#overlay").fadeOut(),
		$("div#detalhes").fadeOut(),
		$("div#conteudo-sobre").fadeOut();
	});
	$("a.link-remover").click(function(){
		var full_url = this.href;
		var parts = full_url.split("#");
		var trgt = parts[1];
		$("#detalhes").html("<p>Deseja realmente remover este usuário?</p><p><a href='adm-usuarios.php?op=remover&id="+trgt+"'>SIM</a>&nbsp;|&nbsp;<a href='"+parts[0]+"' class='optNao'>NÃO</a></p>");
		var winH = $(window).height();
		var winW = $(window).width();
		$("#detalhes").css('top',  winH/2-$("div#conteudo-sobre").height()/2);
		$("#detalhes").css('left', winW/2-$("div#conteudo-sobre").width()/2);
		$("div#overlay").fadeIn();
		$("#detalhes").fadeIn();
	});
	$(".optNao").click(function(){
		fecharLghtbx();
	});
});

function getDetalhes(idCandidato){
	$.ajax({
	   type: "GET",
	   url: "detalhes-ajax.php",
	   data: "idCandidato="+idCandidato,
	   success: function(msg){
		$("#detalhes").html(msg);
		//armazena a largura e a altura da janela
		var winH = $(window).height();
		var winW = $(window).width();
		$("#detalhes").css('top',  winH/2-$("div#conteudo-sobre").height()/2);
		$("#detalhes").css('left', winW/2-$("div#conteudo-sobre").width()/2);
		$("div#overlay").fadeIn();
		$("#detalhes").fadeIn();
		},
	   error: function(msg){
		 $('#detalhes').html(msg);
 		 //$('#detalhes').fadeIn();
		}
	 });
}