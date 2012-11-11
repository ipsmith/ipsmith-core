<?php
function downloadUrl($url)
{
	$ch = curl_init ($url);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_BINARYTRANSFER,0);
	curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 0); 
	
	$rawdata=curl_exec($ch);
	curl_close ($ch);

	return $rawdata;
}

function parseApiRequest($request)
{
	$url = "https://api.ipsmith.org/".$request;

	$result = downloadUrl($url);
	$returnvalue = json_decode($result,true);
	
	return $returnvalue;
}

function startsWith($check, $startStr) 
{
	if (!is_string($check) || !is_string($startStr) || strlen($check)<strlen($startStr)) 
	{
		return false;
	}
 
	return (substr($check, 0, strlen($startStr)) === $startStr);
}


function parseRequest($request)
{
    global $req;

    $vars = array('module','page','displaytype');

    foreach($vars as $var)
    {
	    if(isset($request[$var]) && !is_null($request[$var]))
    	{
	    	$req[$var] = strtolower($request[$var]);
        }
    }

   return $req;
}
