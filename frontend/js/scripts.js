jQuery( document ).ready(function( $ ) {
  $('.topbar').on('click', '.js-open-menu', function(event){
    event.preventDefault();
    $(this).parent().next('.nav-wrapper').addClass('is-opened');
  });
  $('.topbar').on('click', '.js-close-menu', function(event){
    event.preventDefault();
    $(this).parent().removeClass('is-opened');
  });

  $('input[name=Country]').each(function(){
      var input = $(this);

      var select = $('<select name="Country"></select>');

      $.each(COUNTRIES.sk, function(code, name) {
          select.append('<option value="' + code + '"' + (code == 'SK' ? ' selected="selected"' : '') + '>' + name + '</option>');
      });

      input.replaceWith(select);
  });
  $('.teasers-list').masonry({
  // options
    itemSelector: '.grid-item',
    columnWidth: '.grid-sizer'
  });
});
