<?php
function curl($url, $postFields = null)
{
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_FAILONERROR, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	if (is_array($postFields) && 0 < count($postFields))
	{
		$postBodyString = "";
		foreach ($postFields as $k => $v)
		{
			$postBodyString .= "$k=" . urlencode($v) . "&"; 
		}
		unset($k, $v);
		curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);  
			curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0); 
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, substr($postBodyString,0,-1));
	}
	$reponse = curl_exec($ch);
	if (curl_errno($ch)){
		throw new Exception(curl_error($ch),0);
	}
	else{
		$httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		if (200 !== $httpStatusCode){
			throw new Exception($reponse,$httpStatusCode);
		}
	}
	curl_close($ch);
	return $reponse;
}

function import($class,$type='class')
{
	static $classes = array();
	$class = $class.'.'.$type.'.php';
	if (in_array($class,$classes)) return true;

    $class_path = ROOT.'/Lib/'.$class;
    if (!is_file($class_path)) return false;

    include_once($class_path);
    $classes[] = $class;
	return true;
}

function cutStr($str,$length=15, $add_dot = true,$encoding = 'utf-8')
{
	if(mb_strlen($str,$encoding) > $length)
	{
		$str = mb_substr($str, 0, $length,$encoding);
		if($add_dot) $str .= '...';
	}
	return $str;
}