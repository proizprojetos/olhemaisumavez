
 $(document).ready(function() {
    $( "#dialog" ).dialog({
      autoOpen: false,
	  modal: true,
	  width: 900,
	  closeText: "FECHAR" ,
      show: {
        effect: "fade",
        duration: 800
      },
      hide: {
        effect: "fade",
        duration: 400
      }
    });
    
    $( "#palestrante_info" ).click(function() {
      $( "#dialog" ).dialog( "open" );
    });
    
    
    //Efeito slider de texto
    $('#slider-id').liquidSlider({
        autoSlide:true,
        continuous:true,
        keyboardNavigation: true        
    });
    
 });
 
 
 $(document).ready(function() {
	$( ".grupo a" ).click(function() {
		//alert($(this).attr('href'));
		var grupo = $(this).attr('href');
		//var conteudo = $(grupo).html();
		//$(grupo).show('200');
		/*$(grupo).flippy({
			color_target: "#4aa0dc",
			duration: "500",
			verso: conteudo,
			onStart: function () {
				$(grupo).show('200');	
			}
		 });*/
		//$(grupo).flip({
		//	direction:'lr',
		//	onAnimation: function(){
					$(grupo).fadeIn('slow');
		//	},
		//})
		//alert(a);
		if(grupo.contains('#')) {
			return false;
		}
	}); 
	
	$('.grupo_info .bt_voltar_palestras a').click(function() {
		$('.grupo_info').fadeOut('slow');	
		//$(".grupo_info").flippyReverse();
		/*alert('a');
		$('.grupo_info').flippy({
			duration: "500",
			onStart: function () {
				
				$('.grupo_info').css('display: none !important');	
			}
		});*/
		return false;
	});
 });
 

$(document).ready(function() {
	var form = $("#formulario_oficina");
	var submit = $("#submit");
	
	form.on('submit', function(e) {
		e.preventDefault();		
		$.ajax({
			async: false,
			url: '<?php echo JURI::base() . "index.php?option=com_popstilblog&format=xml"?>',
			type: 'POST', // form submit method get/post
      		dataType: 'html', // request type html/json/xml
      		data: {
				option: 'com_loja',
				task: 'enviarEmail',
				param: form.serialize()	
			},
			success: function( data ) {
				alert("deu certo:"+data);	
			}
		});
	});		
});