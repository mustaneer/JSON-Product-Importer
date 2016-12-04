( function( $ ) {
	$( document ).ready( function() {
		$(document).on( 'click', '#feed_submit', function(e) {
			$(".informs").hide();
			var data_url = $("#feed_url").val();
			if(data_url == ""){
				return false;
			}
			e.preventDefault();
			$(this).prop("disabled",true);
			$("#feed_remaining_submit").prop("disabled",true);
			$('.direcory_show').show();
			$('#progressBar').hide();
			$('.valuesShowentries').hide();
			$('.valuesShow').hide();
			//start the process
			files_step( data_url);

		});
		$(document).on( 'click', '#feed_remaining_submit', function(e) {
			e.preventDefault();
			$(this).prop("disabled",true);
			$('#feed_submit').prop("disabled",true);
			$("#feed-header").append( '<span class="spinner is-active apnaspinner"></span>' );
			$('#progressBar div').html("");
			$('#progressBar').show();
			$('#progressBar div').animate({
							width:'0',
						}, 50, function() { });
			var data_url = $("#feed_url").val();
			
			//start the process
			process_step( 1, data_url, 0 ,"files");

		});
		
		$("input[name='feed_url']").blur(function(){
			var complete_url = "?post_type=jsonfeeds&page=feedcron&action=test_url&feed_url="+encodeURIComponent($(this).val());
			$("a[name='test_feedurl']").attr("href",complete_url);
		});
		$("a[name='test_feedurl']").fancybox({
			type: 'iframe',
			beforeShow : function(){
				$('.fancybox-iframe').contents().find('body').css({
					background: '#fff'
				});
				$('.fancybox-iframe').contents().find('#adminmenumain ,#wpadminbar').css({
					opacity: 0,
					display: 'none'
				});
			},
			afterShow : function(){
				$('.fancybox-close').css({
					top: '6px',
					right: '-3px',
				});
			}
		});
	});
	var process_step = function( step, data, counter ,way) {
		
		$.ajax({
			type: 'POST',
			url: ajaxurl,
			data: {
				feed_url: data,
				action: 'feed_wp_ajax_function',
				step: step,
				myaction: 'process',
				counter: counter,
				way: way,
			},
			dataType: "json",
			success: function( response ) {
				if( 'done' == response.step ) {
					$("#feed_submit").prop("disabled",false);
					$("#feed_remaining_submit").prop("disabled",false);
					var percentage = 100;
					$('.spinner').remove();
					$('.offlinebtn').hide();
					$('.valuesShow').show();
					$('#total_usage').html(response.total_usage);
					$('#time_usage').html(response.time_usage);
					$('#progressBar div').html(percentage+'%');	
					$('#progressBar div').animate({
						width: percentage + '%',
					}, 50, function() {
						// Animation complete.
					});

				} else {
					$('#progressBar div').html(response.percentage+'%');	
					$('#progressBar div').animate({
						width: response.percentage + '%',
					}, 50, function() {
						// Animation complete.
					});
					process_step( parseInt( response.step ), data, parseInt( response.counter ), response.way );
				}

			}
		}).fail(function (response) {
			if ( window.console && window.console.log ) {
				console.log( response );
			}
		});

	}
	var files_step = function(data) {
		
		$.ajax({
			type: 'POST',
			url: ajaxurl,
			data: {
				feed_url: data,
				action: 'feed_wp_ajax_function',
				myaction: 'files',
			},
			dataType: "json",
			success: function( response ) {
				$('.direcory_show').hide();
				if( 'done' == response.step ) {
					$('.valuesShowentries').show();
					$('#total_entries').html(response.total_entries);
					$("#feed-header").append( '<span class="spinner is-active apnaspinner"></span>' );
					$('#progressBar div').html("");
					$('#progressBar').show();
					$('#progressBar div').animate({
									width:'0',
								}, 50, function() { });
					process_step( 1, data, 0 ,"ajax");
				} else if('cancel' == response.step){
					$(".informs").css("color","red");
					$(".informs p").html("No Json Record Found by URL");
					$(".informs").show();
					$("#feed_submit").prop("disabled",false);
					$("#feed_remaining_submit").prop("disabled",false);
				}
			}
		}).fail(function (response) {
			if ( window.console && window.console.log ) {
				console.log( response );
			}
		});

	}
})( jQuery );