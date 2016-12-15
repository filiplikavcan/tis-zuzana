jQuery(document).ready(function($) {

	var last_supporter_id = $('.people-list li:last').data('id');

	function loadSupporters() {
		var button = $(this);

		if(!button.hasClass('disabled')) {
			button.addClass('disabled').text(button.data('loading'));

			$.getJSON(button.attr('href'), {
				i: last_supporter_id
			}).always(function(response) {
				button.attr('href', response.Supporters.LoadMoreLink);
				button.removeClass('disabled').text(button.data('loaded'));

				var count = response.Supporters.Supporters.length;

				if(count < response.Supporters.PageSize) {
					button.remove();
				}

				if(count > 0) {
					var last_id, html, list = $('.people-list:first');

					$.each(response.Supporters.Supporters, function(i, supporter) {
						html = '<li><p><strong>' + supporter.Name + '</strong>';

						if(supporter.Country) {
							html += ', ' + supporter.Country;
						}

						last_supporter_id = supporter.ID;

						list.append(html);
					});
				}
			});
		}
	}

	$('body')
		.on('click', '.js-open-menu', function(event) {
			event.preventDefault();
			$(this).parent().next('.nav-wrapper').addClass('is-opened');
		})
		.on('click', '.js-close-menu', function(event) {
			event.preventDefault();
			$(this).parent().removeClass('is-opened');
		}).on('click', '.show-more-supporters', function(event) {
			event.preventDefault();
			loadSupporters.call(this);
		}).on('click', '.menu-main a', function(){
		  $(this).closest('.nav-wrapper').removeClass('is-opened');
		})
        .on('click', "a[href*='youtube']", function (e) {
            e.preventDefault();
            lity($(this).attr('href'));
        });

	$('.counter').each(function() {
		var counter = $(this);

        $.getJSON(counter.data('url'), { for_hp: 'true' }).always(function(response) {
			var strings = $.map(response.Supporters.Supporters, function(supporter) {
                return '<strong>' + supporter.Name + '</strong>';
			});

			if (strings.length > 0)
			{
                counter.css({
                    visibility: 'visible'
                });

				$(".counter span").typed({
                    strings: strings,
                    typeSpeed: 0,
                    loop: true,
                    backDelay: 2000,
                    backSpeed: -50
                });
            }
        });
	});

	$('input[name=Country]').each(function() {
		var input = $(this);
		var value = input.val();


		var select = $('<select name="Country"></select>');

		$.each(COUNTRIES.sk, function(code, name) {
			select.append('<option value="' + code + '"' + (code == value ? ' selected="selected"' : '') + '>' + name + '</option>');
		});

		input.replaceWith(select);
	});

	if(!$("#teasers-list").length) {
		return;
	}

	$('.teasers-list').masonry({
		// options
		itemSelector: '.grid-item',
		columnWidth: '.grid-sizer'
	});
});
