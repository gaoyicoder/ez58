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
        url:'sync.php',
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
	$(function(){
		$(".pop,.filter").css("display","block")

	$(".shut").click(function(){

		$(".pop,.filter").css("display","none")
	});
	$(".filter").bind("click",function(){
		$(".pop,.filter").css("display","none")
	})
})

}

showMessage()

$(function() {
    setInterval("keepOnLine()",30000);

});
