jQuery( document ).ready(function( $ ) {
  $('.topbar').on('click', '.js-open-menu', function(event){
    event.preventDefault();
    $(this).parent().next('.nav-wrapper').addClass('is-opened');
  });
  $('.topbar').on('click', '.js-close-menu', function(event){
    event.preventDefault();
    $(this).parent().removeClass('is-opened');
  });
  $('.support-form').on('click', '[type="submit"]', function(event){
    event.preventDefault();
    $(this).closest('.support-form').addClass('is-closed');
  })
});
