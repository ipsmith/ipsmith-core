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
 * or see http://www.ipsmith.org/docs/license
 *
 * For questions, help, comments, discussion, etc., please join the
 * IPSmith mailing list. Go to http://www.ipsmith.org/lists
 *
 **/

$requestedlocationid = 1;
$catid = 1;
$currentlocation = null;

if(Isset($_REQUEST["locationid"]))
{
    $requestedlocationid = $_REQUEST["locationid"];
}
$smarty->assign("locationid",$requestedlocationid);

$currentlocation = Location::GetById($requestedlocationid);

if(isset($_REQUEST["catid"]))
{
    $catid = $_REQUEST["catid"];
}
else
{
	if($currentlocation->IsValid())
    	$catid = $currentlocation->preselectedcat;
}

$smarty->assign("catid",$catid);

$currentcategories = Category::GetByLocationId($requestedlocationid);

$entries = array();

if($catid==0)
{
	$entries = Entry::GetByLocationId($requestedlocationid);
}
else
{
	$entries = Entry::GetByLocationAndCategory($requestedlocationid,$catid);
}

$smarty->assign('currentcategories',$currentcategories);
$smarty->assign('entries',$entries);

SetTitle('Übersicht');