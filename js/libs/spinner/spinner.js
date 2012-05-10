// Scroll to Top on DataTables pagination
jQuery('body.page-template-page-template-listings-php .dataTables_paginate span a, body.page-template-page-template-listings-php .dataTables_paginate a.paginate_button').live('click', function (){
  $('html, body').animate({scrollTop: '440px'}, 300);
});

jQuery(document).ready(function() {
  var spinningBars = '<div id="spinner"><div class="bar1"></div><div class="bar2"></div><div class="bar3"></div><div class="bar4"></div><div class="bar5"></div><div class="bar6"></div><div class="bar7"></div><div class="bar8"></div></div>';
  $('.dataTables_processing').html(spinningBars);
});
