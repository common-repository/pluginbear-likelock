jQuery(function(){
	jQuery(".likelock_text").each(function(){
		var thisContainer = jQuery(this);
		var thisParent = thisContainer.parent();
		thisContainer.css("top",Math.max(0,((thisParent.height()-thisContainer.outerHeight())/2)+thisParent.scrollTop())+"px");
	});
});