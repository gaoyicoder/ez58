<?php
$busline = $_GET['busline'];

//getLine();
test();
function busView($url2){
	$refer = "http://113.140.71.253:7034/";
	$curl=curl_init();
	curl_setopt($curl, CURLOPT_URL,$url2);  
	curl_setopt($curl, CURLOPT_HEADER, 0);  
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true) ; //请求执行时，不将响应数据直接输出，而是以返回值的形式输出响应数据
	curl_setopt($curl, CURLOPT_REFERER, $refer);  //来路模拟
	$res=curl_exec($curl); 
	echo $res;
	curl_close($curl);  
}
function getLine(){
	$url2 = "http://113.140.71.253:7034/RMBase/BusFront/RuntimeBus.ashx?routeName=HZqVFJdorj0%3d&timestamp=".time()."000";
	busView($url2);
}

function test(){
	$url2 = "http://113.140.71.253:7034/RMBase/BusFront/RuntimeBus.ashx?routeName=HZqVFJdorj0%3d&timestamp=11111000";
	$refer = "http://113.140.71.253:7034/";
	$headers = array();
//	$headers[] = 'Content-Type:application/json';
	$headers[] = 'Accept:*/*';
//	$headers[] = 'Accept-Encoding:gzip, deflate';
	$headers[] = 'Accept-Language:zh-CN,zh;q=0.9';
	$headers[] = 'Connection:keep-alive';
	$headers[] = 'Cookie:ASP.NET_SessionId=sfwmnrlmkss1cnmnaep05xam';
	$headers[] = 'Host:113.140.71.253:7034';
//	$headers[] = 'Referer:http://113.140.71.253:7034/';
	$headers[] = 'User-Agent:Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/63.0.3239.84 Safari/537.36';
	$headers[] = 'X-Requested-With:XMLHttpRequest';
	$curl=curl_init();
	curl_setopt($curl, CURLOPT_URL,$url2);  
	curl_setopt($curl, CURLOPT_HEADER, false);		
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true) ; //请求执行时，不将响应数据直接输出，而是以返回值的形式输出响应数据
	//curl_setopt($curl, CURLOPT_REFERER, $refer);  //来路模拟
	curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($curl, CURLOPT_REFERER, $refer);
	$res=curl_exec($curl);
    echo curl_error($curl);
	echo $res;
	curl_close($curl);  
}
