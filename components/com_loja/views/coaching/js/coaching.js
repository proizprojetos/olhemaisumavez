
$(function() {
    $( "#dialog, #dialog_curriculo" ).dialog({
      autoOpen: false,
	  modal: true,
	  width: 900,
	  closeText: "FECHAR",
	  position: 'top',
      show: {
        effect: "fade",
        duration: 800
      },
      hide: {
        effect: "fade",
        duration: 400
      }
    });
 
    $( "#opener" ).click(function() {
      $( "#dialog" ).dialog( "open" );
    });
    $( "#opener_curriculo" ).click(function() {
      $( "#dialog_curriculo" ).dialog( "open" );
    });
    
    //Efeito slider de texto
    $('#slider-id').liquidSlider({
        autoSlide:true,
        continuous:true,
        keyboardNavigation: true        
    });
    
 });

$(function(){
	$('#formulario_coaching').submit(function() {
		var dados = jQuery( this ).serialize();
		jQuery.ajax({
			type: 'POST',
			url: '<?php echo JURI::base() . "index.php?option=com_popstil&format=raw"?>',
			data: {
				option: 'com_loja',
		        task: 'enviar_email',
		        param: dados
			},
			success: function( data ) {
				alert('Seu email foi enviado com sucesso!');
			},
			error:function(){
                alert('Erro ao enviar email, tente novamente!');
                //$('#errors').html('<p class="error"><strong>Oops!</strong> Try that again in a few moments.</p>');
            }
		});
		$(this).find(':input').each(function () {
			switch(this.type) {
			case 'text':
			case 'textarea':
				$(this).val('');
				break;
			}
			
		});
		
		return false;
	})
});