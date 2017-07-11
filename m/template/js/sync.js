function keepOnLine(){
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showPosition);
    }
}

function showPosition(position) {
    var lat = position.coords.latitude;
    var lng = position.coords.longitude;

    alert(lat+':'+lng);
    $.ajax({
        url:'index.php?mod=sync',
        type:"POST",
        dataType:"json",
        data:{
            lat:lat,
            lng:lng
        },
        success:function(data){
            if(data.hasmessage==1) {
                showMessage();
            }
        }


    });
}


function showMessage() {
    $(".pop,.filter").css("display","block");

	$(".shut").click(function(){
        $(".pop,.filter").css("display","none")
	});
	$(".filter").bind("click",function(){
		$(".pop,.filter").css("display","none");
	});

}



$(function() {
    showMessage();
    setInterval("keepOnLine()",30000);

});
