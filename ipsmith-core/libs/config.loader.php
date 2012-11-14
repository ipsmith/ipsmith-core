<?php
function mergeConfigArrays($Arr1, $Arr2)
{
  foreach($Arr2 as $key => $Value)
  {
    if(array_key_exists($key, $Arr1) && is_array($Value))
      $Arr1[$key] = mergeConfigArrays($Arr1[$key], $Arr2[$key]);

    else
      $Arr1[$key] = $Value;

  }

  return $Arr1;

}


include(LIB_DIR .'/config.default.php');
include(LIB_DIR .'/config.inc.php');

if(count($defaultconfig)>0 && count($userconfig)>0)
{
	$config = mergeConfigArrays($defaultconfig,$userconfig);
}
else
{
	$config = $defaultconfig;
}
