jQuery(document).ready(function ($)
{
    //data-lity
    $(document)
        .on('click', '.js-open-menu', function (event) {
            event.preventDefault();
            $(this).parent().next('.nav-wrapper').addClass('is-opened');
        })
        .on('click', '.js-close-menu', function (event) {
            event.preventDefault();
            $(this).parent().removeClass('is-opened');
        })
        .on('click', "a[href*='youtube']", function (e) {
            e.preventDefault();
            lity($(this).attr('href'));
        });
});
