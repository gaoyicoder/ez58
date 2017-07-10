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

        }


    });
}

$(function() {
    setInterval("keepOnLine()",5000);
});
