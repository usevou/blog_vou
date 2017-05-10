$('nav').css('margin-left','calc(50% - '+( $('nav').width() / 2 + 92 )+'px)');
$('#opc_profile').css('min-width',($('#profile_topo').width())+'px');

$('#profile_topo').on('click',function(){
  $('#opc_profile').show();
  $('body').off('click');
  setTimeout(function(){
    $('body').on('click',function(){
      $('#opc_profile').hide();
    });
  },100);
});
