
<script>
    $(window).on('load', function() { // makes sure the whole site is loaded 
	"use strict";
	$('#status').delay(50).hide(); // will first fade out the loading animation 
	$('#preloader').delay(550).hide('slow'); // will fade out the white DIV that covers the website. 
	$('body').delay(550).css({'overflow':'visible'});
});
</script>
    </body>
</html> 