$('.img-container').imgLiquid({fill:false});

$('.thumb-single').imgLiquid({fill:false});
$('.searchbox2 form').addClass('form-inline').removeClass('navbar-form');

$('#user_login').addClass('form-userid');
$('#user_login').addClass('form-control');
$('#user_pass').addClass('form-control').addClass('form-password');
$('.modal-body #wp-submit').addClass('btn').addClass('btn-save').addClass('btn-login');

//comment modal alteration
$('#reply-title').hide();
$('.logged-in-as').hide();
$('.crfp-field label').hide();
$('.comment-form-comment label').hide();
$('.comment-form-comment textarea').attr('placeholder','Review Comment');
$('.crfp-field').addClass('review-star').addClass("align-center");
$('textarea#comment').addClass('form-control');
$('p.form-submit .submit').addClass('btn').addClass('btn-primary').addClass('btn-save').css('float','right');
$('.book-meta a p').trunk8();


//slick plugin settings
$(document).ready(function(){
	var w = $(window).width();
    var x = 480;
    var z = 1000;
    if (w > x) {
	    
	    //画面幅480px以上の場合
        $('.slick').slick({
		    infinite:true,
		    slidesToShow: 4,
		    slidesToScroll: 1,
		    variableWidth:true
		  });
        
        
        
    }else{
	    
		//画面幅480px以下の場合
        $('.slick').slick({
		    infinite:true,
		    slidesToShow: 2,
		    slidesToScroll: 1,
		    variableWidth:false
		  });
	
	}
		
	//モバイル用サーチボックス幅修正
	var origWidth = $(window).width();
	origWidth = origWidth-48;
	$('input','.searchbox2').width(origWidth);
	
	
	//artileの行数省略
	$('.archive-listing article .review_contents').trunk8({
		lines:6
	});
  
  
  //slick css override
  $(".slick-prev").html('<i class="fa fa-lg fa-chevron-left"></i>');
  $(".slick-next").html('<i class="fa fa-lg fa-chevron-right"></i>');
  
});

