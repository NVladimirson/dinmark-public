<!-- ================== BEGIN BASE JS ================== -->
<script src="/assets/js/app.min.js"></script>
<script src="/assets/js/theme/default.min.js"></script>
<script src="/assets/plugins/gritter/js/jquery.gritter.js"></script>
<!-- ================== END BASE JS ================== -->
<script>
    @if (session('status'))
	$.gritter.add({
		title: '{{ session('status') }}',
	});
    @endif
    $('#new_notifications').click(function (e) {
    	if($(this).has('#last_notification')){
    		var button = $(this);
			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				}
			});
			$.ajax({
				method: "GET",
				url: '{{route('notification.mark_read')}}',
				data: {'last_notification': $('#last_notification').val()},
				success: function(resp)
				{
					if(resp == "ok"){
						var notificationCount = button.children('.label').text() - 5;
						if(notificationCount < 0){
							button.children('.label').remove();
							notificationCount = 0;
                        }else{
							button.children('.label').text(notificationCount);
                        }

					}
				},
				error:  function(xhr, str){
					console.log(xhr);
				}
			});
        }
	});
</script>
@stack('scripts')