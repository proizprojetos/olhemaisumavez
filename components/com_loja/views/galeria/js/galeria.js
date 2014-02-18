jQuery(function($){
	$(".tab-galeria").tabs();
	
	$("#tab-fotos .jcarousel .item img").click(function() {
       //$("#corpo_slider").fadeTo( "slow");
	   var categoria = $(this).data("id");
	   $('#tab-fotos .thumbs li').each(function (index){
	      
	       var id = $(this).data('cat');
	       if(categoria == id) {
	           //$(this).fadeOut("slow",function() {
	               $(this).removeClass("item-ocultar").addClass("item-mostrar");
	           //});   
	       }else {
	           //$(this).fadeOut("slow",function() {
	               $(this).addClass("item-ocultar").removeClass("item-mostrar");
	          // });               
	       }
	   });
	   $('#tab-fotos .thumbs li').each(function (index){
	       if($(this).hasClass('item-mostrar')) {
	           var posicao = $(this).find('a').attr('href');
               $.galleriffic.gotoImage(posicao);
               return false;
	       }
	   });
	   //var posicao = $('.thumbs li').first().find('a').attr('href');
	   //$.galleriffic.gotoImage('#'+tam);
	   $('#tab-fotos .slider-fotos .album_titulo div').each(function (index){
           var id = $(this).data('cat');
           if(categoria == id) {
              // $(this).fadeOut("slow",function() {
                   $(this).removeClass("item-ocultar").addClass("item-mostrar");
              // });   
           }else {
              // $(this).fadeOut("slow",function() {
                   $(this).addClass("item-ocultar").removeClass("item-mostrar");
              // });               
           }
       });  
       //$("#corpo_slider").show('slow');
	    
	});
	
	
	$('.video iframe').attr("src",$('#tab-videos .thumbs li').first().find('a').attr('href'));
	
	$('.item-slider-video a').click(function() {
        //alert($(this.attr('href')));
        //Pega o url o item clicado
        var url =  $(this).attr('href');
        //alert(a);
        
        $('.video iframe').attr("src",url+"?rel=0&autoplay=1");
        /*
         $("#frame").attr("src", "http://www.youtube.com/embed/"+ $("input#videoUrl").val() +"?rel=0&autoplay=1");
            });
         * */
        return false;                               
    });
    
    
    $("#tab-videos .jcarousel .item img").click(function() {
      // $("#corpo_video").hide('slow'); 
        
       var categoria = $(this).data("id");
       $('#tab-videos .thumbs li').each(function (index){
          
           var id = $(this).data('cat');
           if(categoria == id) {
               //$(this).fadeOut("slow",function() {
                   $(this).removeClass("item-ocultar").addClass("item-mostrar");
               //});   
           }else {
               //$(this).fadeOut("slow",function() {
                   $(this).addClass("item-ocultar").removeClass("item-mostrar");
              // });               
           }
       });
       $('#tab-videos .thumbs li').each(function (index){
           if($(this).hasClass('item-mostrar')) {
               var posicao = $(this).find('a').attr('href');
               $('.video iframe').attr("src",posicao);
               //$.galleriffic.gotoImage(posicao);
               //return false;
           }
       });
       //var posicao = $('.thumbs li').first().find('a').attr('href');
       //$.galleriffic.gotoImage('#'+tam);
       $('#tab-videos .album_titulo div').each(function (index){
           var id = $(this).data('cat');
           if(categoria == id) {
              // $(this).fadeOut("slow",function() {
                   $(this).removeClass("item-ocultar").addClass("item-mostrar");
              // });   
           }else {
              // $(this).fadeOut("slow",function() {
                   $(this).addClass("item-ocultar").removeClass("item-mostrar");
              // });               
           }
       });  
       
       //$("#corpo_video").show('slow');
        
    });
    
});

$(function() {
    $('.jcarousel').jcarousel({
    });
    $('.jcarousel-prev').jcarouselControl({
        target: '-=1'
    });
    $('.jcarousel-next').jcarouselControl({
        target: '+=1'
    });
});

$(document).ready(function() {     
            
    // Initialize Minimal Galleriffic Gallery
    $('#thumbs').galleriffic({
        imageContainerSel:      '#slideshow',
        controlsContainerSel:   '#controls',
        prevLinkText:              '<',
        nextLinkText:              '>',
        playLinkText:              '',
        pauseLinkText:             '',
        captionContainerSel:       '#caption'
    });
});