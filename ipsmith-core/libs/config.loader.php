<?php
include(LIB_DIR .'/config.default.php');
include(LIB_DIR .'/config.inc.php');

if(count($defaultconfig)>0 && count($userconfig)>0)
{
	$config = array_merge($defaultconfig,$userconfig);
}
else
{
	$config = $defaultconfig;
}