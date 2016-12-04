( function( $ ) {
	$( document ).ready( function() {
		show_percentage( percetage_show );
	});
	var show_percentage = function( percentage ) {
		if(percentage <= 20){
			$("#five-percentage").prop("checked",true);
		} else if(percentage > 20 && percentage <= 40){
			$("#twenty-percentage").prop("checked",true);
		} else if(percentage > 40 && percentage <= 60){
			$("#sixty-percentage").prop("checked",true);
		} else if(percentage > 60 && percentage <= 80){
			$("#eighty-percentage").prop("checked",true);
		} else if(percentage > 80 && percentage <= 100){
			$("#onehundred-percentage").prop("checked",true);
		}
		$(".percentage_number").html(percentage+"%");
		$(".myprogress .progress-bar").animate({ width:percentage+"%" }, 10, function() { });
	};
})( jQuery );