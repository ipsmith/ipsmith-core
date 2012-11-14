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
