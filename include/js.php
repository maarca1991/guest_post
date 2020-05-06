<script src="js/jquery.min.js"></script>
<script src="js/popper.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/plugins.js"></script>
<script src="js/dzsparallaxer.js"></script>
<script src="js/jquery.syotimer.min.js"></script>
<script src="js/jquery.steps.js"></script>
<script src="js/script.js"></script>

<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

<script type="text/javascript">
	function googleTranslateElementInit() {
	  new google.translate.TranslateElement({pageLanguage: 'en'}, 'google_translate_element');
	}
	
	$('.dropdown').click(function(){
		if (parseInt($(window).width()) < 991) {
			if($(this).find('.sectionDropDown').hasClass('show')) {
		       $(".sectionDropDown").removeClass("show");	
		   }else{
		       $(".sectionDropDown").addClass("show");	   		
		       $(".dropdown").addClass("show");
		   }			
		}
	});

	$('.dropdown2').click(function(){
		if (parseInt($(window).width()) < 991) {
			if($(this).find('.langDropdown').hasClass('show')) {
		       $(".langDropdown").removeClass("show");	
		   }else{
		       $(".langDropdown").addClass("show");
		       $(".dropdown2").addClass("show");
		   }			
		}
	});

</script>