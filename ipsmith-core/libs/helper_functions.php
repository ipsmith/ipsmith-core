<?php
/**
 * Project:     IPSmith - Free ip address managing tool
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This Software is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 * For questions, help, comments, discussion, etc., please join the
 * IPSmith mailing list. Go to http://www.ipsmith.org/lists
 *
 **/

function hex2rgb($color)
{
    $color = str_replace('#', '', $color);
    $rgb = array();

    if (strlen($color) != 6)
    {
        $rgb= array(0,0,0);
    }

    for ($x=0;$x<3;$x++)
    {
        $rgb[$x] = hexdec(substr($color,(2*$x),2));
    }

    return $rgb;
}

function downloadUrl($url)
{
 /*   global $LogHandler;
    $LogHandler->addRecord(
                Logger::NOTICE,
                "About to download ".$url,
                array('url' => $url)
            );*/

    $ch = curl_init ($url);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_BINARYTRANSFER,0);
	curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 0);

	$rawdata=curl_exec($ch);
	curl_close ($ch);
    /*$LogHandler->addRecord(
                Logger::NOTICE,
                "Finished downloading ".$url,
                array('url' => $url)
            );
*/
	return $rawdata;
}

function parseApiRequest($request)
{
    global $LogHandler;
   /* $LogHandler->addRecord(
                Logger::NOTICE,
                "About to fetch REST Api from ipsmith.org ",
                array('request' => $request)
            );
*/
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

function userHashPassword($password)
{
    return md5(sha1($password));
}

function SetTitle($title)
{
    global $webapp;

    $webapp["title"] = $title.' - '.$webapp["title"];
}

function PumpMessage($arrayname,$message)
{
    if(!isset($_SESSION["currentmessages"]))
    {
        $_SESSION["currentmessages"] = array();   
    }


    if(!isset($_SESSION["currentmessages"][$arrayname]))
    {
        $_SESSION["currentmessages"][$arrayname] = array();   
    }

    $_SESSION["currentmessages"][$arrayname][] = $message;
}

function is_empty($string)
{
    if( $string === NULL ) 
    { 
        return true; 
    }

    $string = trim($string);

    return ($string=="" || $string=='' || is_null($string));
}