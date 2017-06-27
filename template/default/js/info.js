$.ajax({
    type : "get",
    url : current_domain+"/javascript.php?part=information&id="+infoid,
    dataType : "jsonp",
    jsonp: "callback",
    jsonpCallback:"success_jsonpCallback",
    success: function(json) {
		$('#hit').html(json);
	}
});