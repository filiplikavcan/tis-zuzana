jQuery(document).ready(function($) {
	'use strict';
	if(!$("#js-scrollspy").length) {
		return;
	}
	console.log('intit scrollspy');
	// Cache selectors
	var lastId,
		scrollspy = $("#js-scrollspy"),
		scrollspyHeight = scrollspy.outerHeight(),
		scrollspyOffset = scrollspy.offset().top,
		scrollspyFixed = false,
		menuHeight = $('#js-scrollspy').innerHeight(),
		// All list items
		menuItems = scrollspy.find('a[href=""]'),
		// // Anchors corresponding to menu items
		scrollItems = menuItems.map(function() {
			// var item = $($(this).attr("href"));
			var item = $($(this).attr("href"));
			if(item.length) {
				return item;
			}
		});
	console.log('all');

	// Bind click handler to menu items
	// so we can get a fancy scroll animation
	menuItems.click(function(e) {
		var href = $(this).attr("href"),
			offsetTop = href === "#" ? 0 : $(href).offset().top - scrollspyHeight + 1 - menuHeight;
		$('html, body').stop().animate({
			scrollTop: offsetTop
		}, 1000);
		// e.preventDefault();
	});

	// Bind to scroll
	$(window).scroll(function() {
		// Get container scroll position
		var fromTop = $(this).scrollTop();

		// fix scrollspy nav
		// if(!scrollspyFixed && fromTop >= scrollspyOffset - menuHeight ) {
		//   scrollspyFixed = true;
		//   scrollspy.addClass('fixed');
		// } else if (scrollspyFixed  && fromTop < scrollspyOffset - menuHeight ) {
		//   scrollspy.removeClass('fixed');
		//   scrollspyFixed = false;
		// }
		console.log(fromTop);

		// Get id of current scroll item
		var cur = scrollItems.map(function() {
			if($(this).offset().top < fromTop + scrollspyHeight + menuHeight + 15)
				return this;
		});
		// Get the id of the current element
		cur = cur[cur.length - 1];
		var id = cur && cur.length ? cur[0].id : "";

		if(lastId !== id) {
			lastId = id;
			// Set/remove active class
			menuItems
				.parent().removeClass("active")
				.end().filter("[href='#" + id + "']").parent().addClass("active");
		}
	});
});
