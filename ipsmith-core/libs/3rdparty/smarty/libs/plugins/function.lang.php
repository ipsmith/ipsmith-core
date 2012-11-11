<?php
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     function.vlang.php
 * Type:     function
 * Name:     lang
 * Purpose:  check string length and outputs a response
 * -------------------------------------------------------------
 */

function smarty_function_lang($params, &$smarty)
{
	global $translation, $config;
	if(!isset($params["language"])) { $params["language"] = $_SESSION["userdata"]["language"]; }
	return $translation->get($params["string"],$params["language"]); 
}
