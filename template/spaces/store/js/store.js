$.ajax({
    type : "get",
    url : current_domain+"/javascript.php?part=member&id="+memberid,
    dataType : "jsonp",
    jsonp: "callback",
    jsonpCallback:"success_jsonpCallback",
    success: function(json) {
		$('#hit').html(json);
	}
});