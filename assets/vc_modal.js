jQuery(document).ready(function(){
  jQuery('.expand_vc_modal').click(function(){
    jQuery(this).find('.modal').toggleClass('hide_modal');
  });
  jQuery('.modal-content').click(function(event) {
    if ( !( jQuery(event.target).hasClass('close') ) ) {
      event.stopPropagation();
    }
  });
});