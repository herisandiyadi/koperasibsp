$(function(){
	$("#wizard").steps({
        headerTag: "h2",
        bodyTag: "section",
        transitionEffect: "fade",
        enableAllSteps: true,
        transitionEffectSpeed: 500,
        labels: {
            finish: "Daftar",
            next: "Selanjutnya",
            previous: "Sebelumnya"
        }
    });
    $('.wizard > .steps li a').click(function(){
    	$(this).parent().addClass('checked');
		$(this).parent().prevAll().addClass('checked');
		$(this).parent().nextAll().removeClass('checked');
    });
    // Custome Jquery Step Button
    $('.forward').click(function(){
		alert('Anda harus mengisi data dengan lengkap !');
		var nama = document.getElementById("nama").value;
		if (nama != "" && email!="" && alamat !="") {
			$("#wizard").steps('next');
		}else{
			alert('Anda harus mengisi data dengan lengkap !');
		}
    })
    $('.backward').click(function(){
        $("#wizard").steps('previous');
    })
	$('.finish').click(function(){
		$("#wizard").steps('previous');
	})

	// Select Dropdown
    $('html').click(function() {
        $('.select .dropdown').hide();
    });
    $('.select').click(function(event){
        event.stopPropagation();
    });
    $('.select .select-control').click(function(){
        $(this).parent().next().toggle();
    })
    $('.select .dropdown li').click(function(){
        $(this).parent().toggle();
        var text = $(this).attr('rel');
        $(this).parent().prev().find('div').text(text);
    })
})
