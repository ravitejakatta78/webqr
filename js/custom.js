

	$(document).ready(function() {
 
});

	// Show the first tab and hide the rest
$('#tab-nav .inner:first-child').addClass('active');
$('.tab-content').hide();
$('.tab-content:first').show();

// Click function
$('#tab-nav .inner').click(function(){
  $('#tab-nav .inner').removeClass('active');
  $(this).addClass('active');
  $('.tab-content').hide();
  
  var activeTab = $(this).find('a').attr('href');
  $(activeTab).fadeIn();
  return false;
});
	
