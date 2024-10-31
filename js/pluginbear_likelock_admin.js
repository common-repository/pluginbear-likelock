jQuery(function(){
	jQuery("#text_color").ColorPicker({
		onBeforeShow: function () {
			jQuery(this).ColorPickerSetColor(this.value);
		},
		onShow: function (colpkr) {
			jQuery(colpkr).fadeIn(500);
			return false;
		},
		onHide: function (colpkr) {
			jQuery(colpkr).fadeOut(500);
			return false;
		},
		onChange: function (hsb, hex, rgb, colpkr) {
			jQuery("#text_color").val('#'+hex);
			jQuery(".likelock_text").css("color","#"+hex);
			jQuery("#text_color_snippet").text("#"+hex);
		}
	});
	
	jQuery("#bg_color").ColorPicker({
		onBeforeShow: function () {
			jQuery(this).ColorPickerSetColor(this.value);
		},
		onShow: function (colpkr) {
			jQuery(colpkr).fadeIn(500);
			return false;
		},
		onHide: function (colpkr) {
			jQuery(colpkr).fadeOut(500);
			return false;
		},
		onChange: function (hsb, hex, rgb, colpkr) {
			jQuery("#bg_color").val('#'+hex);
			jQuery("#bg_color_snippet").text("#"+hex);
			R = hexToR("#"+hex);
			G = hexToG("#"+hex);
			B = hexToB("#"+hex);
			A = jQuery("#opacity").val();
			jQuery(".likelock_container").css("background","rgba("+R+","+G+","+B+","+A+")");
		}
	});
	
	jQuery("#likelock_slider").slider({
		value:jQuery("#opacity").val(),
		min:0,
		max:1,
		step:0.05,
		slide: function( event, ui ) {
			hex = jQuery("#bg_color").val().replace("#","");
			R = hexToR("#"+hex);
			G = hexToG("#"+hex);
			B = hexToB("#"+hex);
			A = ui.value;
			
			jQuery("#opacity_snippet").text(A);
			jQuery("#opacity").val(A);
			jQuery(".likelock_container").css("background","rgba("+R+","+G+","+B+","+A+")");
		}
	});
	
	jQuery("#like_text").live("keyup",function(){
		jQuery("#like_text_preview,#like_text_snippet").text(jQuery(this).val());
	});
	
	jQuery("#custom_url").live("keyup",function(){
		jQuery("#custom_url_preview").text(jQuery(this).val());
	});
	
	function hexToR(h) {return parseInt((cutHex(h)).substring(0,2),16)}
	function hexToG(h) {return parseInt((cutHex(h)).substring(2,4),16)}
	function hexToB(h) {return parseInt((cutHex(h)).substring(4,6),16)}
	function cutHex(h) {return (h.charAt(0)=="#") ? h.substring(1,7):h}
});