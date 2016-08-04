<?php
function postSMS($mobiel,$data)
{
	$post_data = array();
	$post_data['account'] = iconv('GB2312', 'GB2312',"jiekou-clcs-08");
	$post_data['pswd'] = iconv('GB2312', 'GB2312',"Txb123456");
	//$post_data['ContentType'] = iconv('GB2312', 'GB2312',"15");
	$post_data['mobile'] = $mobiel;
	//iconv('GB2312', 'UTF-8',"15821162098");
	$post_data['msg']=mb_convert_encoding("$data",'UTF-8', 'GB2312');//iconv('GB2312', 'UTF-8',"123456");
	//$post_data['dtime'] = date("Y-m-d H:i:s");
	//$post_data['submit'] = iconv('GB2312', 'UTF-8',"submit");
	$url='http://222.73.117.158/msg/HttpBatchSendSM?';
	$o="";
	foreach ($post_data as $k=>$v)
	{
	   $o.= "$k=".urlencode($v)."&";
	}
	$post_data=substr($o,0,-1);
	//echo($post_data);
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_URL,$url);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
	$result = curl_exec($ch);
	curl_close($ch);
}

//main
$phone="13652401103";
echo $phone;
if(empty($phone))
{
	echo "no";
}
else 
{
	$code=rand(10000,99999);
	$data="你好，验证码为：".$code;
	postSMS($phone,$data);
}
?>
