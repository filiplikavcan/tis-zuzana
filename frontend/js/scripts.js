jQuery( document ).ready(function( $ ) {
  $('.topbar').on('click', '.js-open-menu', function(event){
    event.preventDefault();
    $(this).next().addClass('is-opened');
  });
  $('.topbar').on('click', '.js-close-menu', function(event){
    event.preventDefault();
    $(this).parent().removeClass('is-opened');
  });
});
