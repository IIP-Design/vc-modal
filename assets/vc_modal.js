jQuery(document).ready(function(){
  jQuery('.expand_vc_modal').click(function(){
    if ( !( jQuery(event.target).is( "a" ) ) )
      jQuery(this).find('.modal').toggleClass('hide_modal');
  });
  jQuery('.modal-content').click(function(event) {
    if ( !( jQuery(event.target).hasClass('close') || jQuery(event.target).is( "a" ) ) ) {
      event.stopPropagation();
    }
  });
});