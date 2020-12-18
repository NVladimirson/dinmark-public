<!-- ================== BEGIN BASE JS ================== -->
<script src="/assets/js/app.min.js"></script>
<script src="/assets/js/theme/default.min.js"></script>
<script src="/assets/plugins/gritter/js/jquery.gritter.js"></script>
<script src="/assets/plugins/select2/dist/js/select2.min.js"></script>
<!-- ================== END BASE JS ================== -->
<script>
    @if (session('status'))
	$.gritter.add({
		title: '{{ session('status') }}',
	});
    @endif
    $('#new_notifications').click(function (e) {
      let bell = $('#notificationbell');
      if(bell.attr('class') === 'fa fa-exclamation'){
          bell.toggleClass('fa-exclamation fa-bell');
      }
      else{
        console.log(bell.attr('class'));
      }
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


	if ($('.custom-file-input').length !== 0){
		document.querySelector('.custom-file-input').addEventListener('change',function(e){
			var fileName = document.getElementById("uploadPhoto").files[0].name;
			var nextSibling = e.target.nextElementSibling
			nextSibling.innerText = fileName
		})
    }

</script>

<script>

var windowsize = $(window).width();

$(window).resize(function() {
    windowsize = $(window).width();
    if (windowsize > 1050 && ($('.sidebar').css('left') == '-220px' )) {
        $('.sidebar').css('left', '0');
        $('.sidebar').css('z-index', '1020');
    }
    // else if ( windowsize < 1050 && ($('.sidebar').css('z-index') == '1010' ) ) {
    //     $('.sidebar').css('z-index', '1020');
    // }
});
</script>

<script>
$('.navbar-toggle').click(function (e) {
    if ( $('#close-burger-menu').is(":visible") ) {
        $('#close-burger-menu').toggleClass('hide');
        $('.sidebar').css('left', '-220px');
        $('.sidebar').css('z-index', '1010');
    } else {
        $('#close-burger-menu').toggleClass('hide');
        $('.sidebar').css('left', '0');
        $('.sidebar').css('z-index', '1000');
    }
    // $('#close-burger-menu').toggleClass('hide');

});

$('#close-burger-menu').click(function(e) {
    $('.sidebar').css('left', '-220px');
    $('.sidebar').css('z-index', '1010');
    $('#close-burger-menu').toggleClass('hide');
})

</script>


<script>
//show full filter
$('#header form.hexa .more').click(function (e) {
    if ($('.more.hexa-plus i').hasClass('fa-plus')) {
        $('.more.hexa-plus i').removeClass('fa-plus');
        $('.more.hexa-plus i').addClass('fa-minus');
        $('.navbar-grey').css('z-index', '0');
    } else {
        $('.more.hexa-plus i').removeClass('fa-minus');
        $('.more.hexa-plus i').addClass('fa-plus');
        $('.navbar-grey').css('z-index', '1020');
    }
    $('#filter').toggleClass('hide');
});
</script>

<script>
//show full filter
$('#mobile-header form.hexa .more').click(function (e) {
    if ($('.more.hexa-plus i').hasClass('fa-plus')) {
        $('.more.hexa-plus i').removeClass('fa-plus');
        $('.more.hexa-plus i').addClass('fa-minus');
        $('.navbar-grey').css('z-index', '0');
    } else {
        $('.more.hexa-plus i').removeClass('fa-minus');
        $('.more.hexa-plus i').addClass('fa-plus');
        $('.navbar-grey').css('z-index', '1020');
    }
    $('#mobile-filter').toggleClass('hide');
});
</script>

<script>
//open catalog
$('#show-catalog-menu').click(function (e) {
    if ($('#show-catalog-menu').hasClass('fa-plus')) {
        $('#show-catalog-menu').removeClass('fa-plus');
        $('#show-catalog-menu').addClass('fa-minus');
        $('.mainmenu').css('display', 'block');
        // console.log('test click');
        // $('.navbar-grey').css('z-index', '0');
    } else {
        $('#show-catalog-menu').removeClass('fa-minus');
        $('#show-catalog-menu').addClass('fa-plus');
        $('.mainmenu').css('display', 'none');
        // $('.navbar-grey').css('z-index', '1020');
    }
    // $('#filter').toggleClass('hide');
});
</script>

<script>

// $('#sidebar')

</script>

<script>
// hide/show menu onscroll
$(window).scroll(function() {
    if ($(this).scrollTop() > 1) {
        // $('.navbar-grey').addClass('hide');
        $('.sidebar').css('padding-top', '89px');
        $('.navbar-grey').css('z-index', '1015');
    } else {
        // $('.navbar-grey').removeClass('hide');
        $('.sidebar').css('padding-top', '135px');
        $('.navbar-grey').css('z-index', '1019');
    }
});

</script>
@stack('scripts')
