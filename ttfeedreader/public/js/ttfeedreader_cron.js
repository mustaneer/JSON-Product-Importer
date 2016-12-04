( function( $ ) {
	$( document ).ready( function() {
		$('button[name="delete_button"]').click(function(){
			var feed_id = $(this).data('feed-id');
			var form = $("#delete_"+ feed_id +"_form");
			$('#confirm').modal({ backdrop: 'static', keyboard: false }).on('click', "#delete", function(){
				form.submit();
			})
		});
		$("button[name='embed_jsonfeed']").click(function(){
			var feed_name = $(this).data('feed-name');		
			var feed_url = $(this).data('feed-url');
			
			$("#feed_embed_name").html(feed_name);
			$("#embed_url").html(feed_url);
			
			$('#embed').modal({ backdrop: 'static', keyboard: false });
		});
		$("button[name='edit_jsonfeed']").click(function(){
			var feed_id = $(this).data('feed-id');
			window.location.href="?post_type=jsonfeeds&page=feedcron&action=update_url&feed_id="+ feed_id;
		});
		$("button[name='process_button']").click(function(){
			var feed_id = $(this).data('feed-id');
			window.location.href="?post_type=jsonfeeds&page=feedcron&action=files_create&feed_id="+ feed_id;
		});
		$("button[name='import_button']").click(function(){
			var feed_id = $(this).data('feed-id');
			window.location.href="?post_type=jsonfeeds&page=feedcron&action=process_now&feed_id="+ feed_id;
		});
		$("a[name='show_jsonfeed']").fancybox({
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
})( jQuery );