jQuery(function($){
	$(".tabs_oficinas").tabs();	
});

$(function(){
	
	$('#formulario_oficina').submit(function() {
		alert('iniciou');
		var dados = jQuery( this ).serialize();
		alert(dados);
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