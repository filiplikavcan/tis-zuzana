jQuery( document ).ready(function( $ ) {
  $('.topbar').on('click', '.js-open-menu', function(event){
    event.preventDefault();
    $(this).parent().next('.nav-wrapper').addClass('is-opened');
  });
  $('.topbar').on('click', '.js-close-menu', function(event){
    event.preventDefault();
    $(this).parent().removeClass('is-opened');
  });
  if(!$("#teasers-list").length){
    return;
  }
  $('.teasers-list').masonry({
  // options
    itemSelector: '.grid-item',
    columnWidth: '.grid-sizer'
  });
});
