$(document).ready(function() {
	$("div#detalhes #faixa-topo").mousedown(function(){
		$('#detalhes').draggable({ 
			disabled: false, 
			grid: [ 10,10 ],
			snap: true,		
			containment: "body", scroll: false
		});
	}).mouseup(function(){
		$('#detalhes').draggable({ 
			disabled: true
		});
    });
});

//$("td").stop().animate({backgroundColor: '#b8d4ea'},2000);