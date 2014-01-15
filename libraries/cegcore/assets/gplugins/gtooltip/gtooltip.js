jQuery(document).ready(function($){
	var GTooltip = {
		'tipclass' : 'gtooltip',
	};

	$.fn.gtooltip = function(option){
		var $this = $(this);
		if($.type(option) == 'string'){
			if(option == 'show' && $this.next('.gtooltip').length < 1){
				var $tip = $('<div class="'+GTooltip.tipclass+'">'+($this.data('content') ? $this.data('content') : $this.prop('title'))+'<div class="gtooltip-arrow-border"></div><div class="gtooltip-arrow"></div></div>');
				var $offset = $this.offset();
				var $position = $this.position();
				//console.log($offset);
				//console.log($position);
				$this.after($tip);
				//$tip.css('top', - $tip.outerHeight() - $tip.find('.gtooltip-arrow-border').outerHeight()/2);
				$tip.css('top', $position.top - $tip.outerHeight() - $tip.find('.gtooltip-arrow-border').outerHeight()/2);
				//$tip.css('left', $this.outerWidth()/2 - 30 - 10); //element width - tooltip arrow left shift - arrow's border width
				$tip.css('left', $position.left + $this.outerWidth()/2 - 30 - 10);
			}
			if(option == 'destroy'){
				$this.next('.gtooltip').remove();
			}
		}else if($.type(option) == 'object'){
			$.each(option, function(k, v){
				GTooltip[k] = v;
			});
		}
	}
});