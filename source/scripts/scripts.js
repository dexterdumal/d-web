var retorno;
$(document).ready(function() {
	
	$("td").mouseover(function(){
		$(this).parent().css("background-color","#b8d4ea");
	});
	$("td").mouseout(function(){
		$(this).parent().css("background-color","#FFFFFF");
	});
	$("div#overlay").click(function(){
		lightbox('hide');
	});
	
	$(".scroll").click(function(event){
		//prevent the default action for the click event
		event.preventDefault();

		//get the full url - like mysitecom/index.htm#home
		var full_url = this.href;

		//split the url by # and get the anchor target name - home in mysitecom/index.htm#home
		var parts = full_url.split("#");
		var trgt = parts[1];

		//get the top offset of the target anchor
		var target_offset = $("#"+trgt).position();
		var target_top = target_offset.top;

		//goto that anchor by setting the body scroll top to anchor top
		$('html, body').animate({scrollTop:target_top}, 'slow');
	});	
	
	$("div#detalhes #faixa-topo #link-fechar").mouseover(function(){
		$(this).css("background-position","left -18px");
	});
	$("div#detalhes #faixa-topo #link-fechar").mouseout(function(){
		$(this).css("background-position","left top");
	});
	
	$("#link-fechar").click(function(){
		lightbox('hide');
	});
	
	$("#frm-filtro-busca #btn-enviar").click(function(e){
		e.preventDefault();
		var params = new Object();
		params.tipoFiltro = $("#sel-tipo-filtro option:selected").val();
		params.termoBusca = $("#txt-termo").val();
		rodarFuncaoAjax("pinc","listarProjetos", params, this, "#box-resultados");
		delete params;
	});
	
	$("#list-projetos tbody tr td").live('click', function(){
		var params = new Object();
		params.idProjeto = $(this).parent().attr("rel");
		rodarFuncaoAjax("pinc","obterDetalhesProjeto", params, this, "#detalhes #detalhes-content");
		delete params;
		lightbox('show');
	});	
	
	$("#list-projetos-professor tbody tr td a.view").live('click', function(){
		var params = new Object();
		params.idProjeto = $(this).parent().parent().attr("rel");
		rodarFuncaoAjax("pinc","obterDetalhesProjeto", params, this, "#detalhes #detalhes-content");
		delete params;
		lightbox('show');
	});
	
	$("#add-projeto textarea, #edit-projeto textarea").keyup(function(){
		if($(this).val().length < $(this).attr('maxlength')){
			$("#count-chars strong").html($(this).attr('maxlength')  - $(this).val().length);
		}else{
			$(this).val($(this).val().substring(0,$(this).attr('maxlength')));
			$("#count-chars strong").html('0');
		}		
	});
	
	$("#abas-areas li").click(function(){
		$("#abas-areas li").removeClass("active");
		$(this).addClass("active");
		$(".aba").hide();
		$("#"+$(this).attr("rel")).show();
	});
	/*$("#add-professor #btn-add-professor").click(function(){
		var params = new Object();
		params.idProjeto = $("#add-professor #idProjeto").val();
		params.nomeProfessor = $("#add-professor #nome-professor").val();
		params.email = $("#add-professor #email").val();
		params.telefone = $("#add-professor #telefone").val();
		params.lattes = $("#add-professor #lattes").val();
		rodarFuncaoAjax("pinc","adicionarProfessor", params, this, null);
		delete params;
	});*/
	
});

function validarPreenchimento(idFormulario){
	var erro = 0;
	$('#'+idFormulario+" input.text").each(function(){
		if($(this).val()==""){
			$(this).next("span").remove();
			$(this).after("<span class='erro'>campo obrigatório</span>");
			//$(this).find("span").text("campo obrigatório");
			$(this).addClass("required");
			erro++;
		}else{
			$(this).removeClass("required");
			$(this).next("span").remove();
		}
	});
	if(erro==0){$('#'+idFormulario).submit();}
	return true;
}
function destacarCamposNaoPreenchidos(idFormulario){
	$('#'+idFormulario+' .required-field').removeClass('required-highlight');
	$('#'+idFormulario+' input, #'+idFormulario+' select, #'+idFormulario+' textarea').each(function(index) {
		if($(this).hasClass('required-field') && $(this).val()==""){
			//$(this).css("border","2px solid #ff3333");
			$(this).addClass('required-highlight');
		}
	});
	return true;
}

function lightbox(acao){
	if(acao=="show"){
		//$('html, body').animate({scrollTop:0}, 'slow');
		//$('#detalhes').css('left',($('html').width()/2)-($('#detalhes').width()/2));
		centraliza('#detalhes', 'html');
		$("div#overlay, div#detalhes").fadeIn();
	}else if(acao=="hide"){
		$("div#overlay, div#detalhes").fadeOut();
	}
	return true;
}

function centraliza(elem, elemRef){
	$(elem).css('left',($(elemRef).width()/2)-($(elem).width()/2));
}

function dateToString(date) {
    var month = date.getMonth() + 1;
    var day = date.getDate();
    var dateOfString = (("" + day).length < 2 ? "0" : "") + day + "/";
    dateOfString += (("" + month).length < 2 ? "0" : "") + month + "/";
    dateOfString += date.getFullYear();
    return dateOfString;
}