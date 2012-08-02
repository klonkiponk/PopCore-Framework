$(document).ready(function() {
    $(".imageZoom").fancybox({
    	openEffect	: 'elastic',
    	closeEffect	: 'elastic',
    	closeClick	: true,
    	helpers : {
    		overlay : {
	    		speedIn  : 900,
	    		speedOut : 20,
	    		opacity  : 0.8,
	    		css : {
		    		'background-color':'#fff'
	    		}
	    	}
    	}
    });
});