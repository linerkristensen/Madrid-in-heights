$(document).ready(function(){
	$('#menuphotos').click(function(){
		$('html,body').animate({
			scrollTop: $('#photos').offset().top - 85
		}, 500);
	});
	$('#menuinfo').click(function(){
		$('html,body').animate({
			scrollTop: $('#info').offset().top - 115
		}, 500);
	});
	$('#menurooftops').click(function(){
		$('html,body').animate({
			scrollTop: $('#rooftops').offset().top + 20
		}, 500);
	});
	$('#menumap').click(function(){
		$('html,body').animate({
			scrollTop: $('#map').offset().top + 20
		}, 500);
	});
	$('#menusocial').click(function(){
		$('html,body').animate({
			scrollTop: $('#social').offset().top - 85
		}, 500);
	});
	$('#menulogo').click(function(){
		$('html,body').animate({
			scrollTop: $('#top').offset().top - 0
		}, 500);
	});
	$('#menulogin').click(function(){
		$('#logindboks').fadeIn(function(){
			// Åbner logind boksen
		});
	});
	$('#menujoin').click(function(){
		$('#joinboks').fadeIn(function(){
			// Åbner logind boksen
		});
	});
	$('#menuupload').click(function(){
		$('#uploadboks').fadeIn(function(){
			// Åbner logind boksen
		});
	});
	
		$('#menulogin2').click(function(){
		$('#logindboks').fadeIn(function(){
			// Åbner logind boksen
		});
			$('#mobilmenu').fadeOut("slow");
	});
	$('#menujoin2').click(function(){
		$('#joinboks').fadeIn(function(){
			// Åbner logind boksen
		});
		$('#mobilmenu').fadeOut("slow");
	});
	$('#menuupload2').click(function(){
		$('#uploadboks').fadeIn(function(){
			// Åbner logind boksen
		});
		$('#mobilmenu').fadeOut("slow");
	});
	$('.popupbokscontainer').click(function(e){
		if(e.target === this){
			$('.popupbokscontainer').fadeOut("slow");
		}
	});
	
	$('#redigerknap').click(function(){
		$('.redigerboks').slideToggle("slow");
	});
	
	$('#burgermenu').click(function(){
		$('#mobilmenu').fadeToggle("slow");
	});
	
	$('#mobilmenu').click(function(e){
		if(e.target === this){
			$('#mobilmenu').fadeOut("slow");
		}
	});
	
	$('#mobilphotos').click(function(){
		$('html,body').animate({
			scrollTop: $('#photos').offset().top - 85
		}, 500);
		$('#mobilmenu').fadeOut("slow");
	});
	$('#mobilinfo').click(function(){
		$('html,body').animate({
			scrollTop: $('#info').offset().top - 115
		}, 500);
		$('#mobilmenu').fadeOut("slow");
	});
	$('#mobilroof').click(function(){
		$('html,body').animate({
			scrollTop: $('#rooftops').offset().top + 20
		}, 500);
		$('#mobilmenu').fadeOut("slow");
	});
	$('#mobilmap').click(function(){
		$('html,body').animate({
			scrollTop: $('#map').offset().top + 20
		}, 500);
		$('#mobilmenu').fadeOut("slow");
	});
	$('#mobilsocial').click(function(){
		$('html,body').animate({
			scrollTop: $('#social').offset().top - 85
		}, 500);
		$('#mobilmenu').fadeOut("slow");
	});
});