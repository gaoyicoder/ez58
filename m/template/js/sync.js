function keepOnLine(){

    var geolocation = new BMap.Geolocation();
    geolocation.getCurrentPosition(showBaiduPosition);
}




function showBaiduPosition(r) {

    var lat = r.point.lat;
    var lng = r.point.lng;

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




