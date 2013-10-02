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


if(isset($_REQUEST["success"]) && $_REQUEST["success"]==1)
{
    if($_REQUEST["lastaction"]=='update')
    {
        PumpMessage('success',"Eintrag erfolgreich bearbeitet.");
    }
    else
    {
        PumpMessage('success',"Eintrag erfolgreich erstellt.");
    }
}

$currententry = null;
$requestedid = 0;

if(isset($_GET['id']))
{
    $requestedid = $_GET['id'];
}
else if(isset($_POST['entry_id']))
{
    $requestedid = $_POST['entry_id'];
}

$smarty->assign('currentid',$requestedid);

$icons = array();
$icondir = dir(PUBLIC_DIR.'/assets/icons');
while(false !== ($entry = $icondir->read()))
{
    if($entry!='.' && $entry!='..' && $entry!='.gitkeep')
    {
        $icons[]= array ( 'name' => basename($entry),
                          'path'=>$config['baseurlpath'].'/assets/icons/'.$entry);
    }
}
$smarty->assign('icons',$icons);

$types = Type::GetAll();
$smarty->assign('types',$types);


$logentries = TableLog::GetByTableNameAndId('entries',$requestedid);
$smarty->assign('logentries',$logentries);

$cats = Category::GetAll();
$smarty->assign('cats',$cats);

$exports = ExportTemplate::GetAll();
$smarty->assign('allexports',$exports);
$exportids = array();
$assignedExports = ExportTemplate::GetByEntryId($requestedid);
foreach($assignedExports as $export)
{
    $exportids[] = $export->id;
}

$selectedexports = array();
$availableexports = array();
foreach($exports as $export)
{
    if(in_array($export->id,$exportids))
    {
        if($export->force_assignment==0)
        {
            $selectedexports[] = $export;
        }
    }
    else
    {
        if($export->force_assignment==0)
        {
            $availableexports[] = $export;
        }
    }
}

$smarty->assign('selectedexports',$selectedexports);
$smarty->assign('availableexports',$availableexports);

$currentitemname = 'Neuen Eintrag';

if($requestedid>0)
{
        $currententry = Entry::GetById($requestedid);
        $currentitemname = $currententry->hostname;
}

if(isset($_POST['submit']))
{

    $rgb = hex2rgb($_POST['entry_color_hex']);

    $entry = new Entry();
    $lastaction = 'create';

    if(isset($_POST['entry_id']) && $_POST['entry_id']>0)
    {
        $lastaction = 'update';
        $currentid = $_POST['entry_id'];
        $entry->LoadById($_POST['entry_id']);
    }

    $entry->macaddress= $_POST['entry_mac'];
    $entry->hostname= $_POST['entry_hostname'];
    $entry->ip=$_POST['entry_ip'];
    $entry->colorhex= $_POST['entry_color_hex'];
    $entry->colorr= $rgb[0];
    $entry->colorg= $rgb[1];
    $entry->colorb= $rgb[2];
    $entry->locationid= $_POST['entry_location'];
    $entry->typeid= $_POST['entry_type'];
    $entry->catid = $_POST['entry_cat'];
    $entry->iconpath = $_POST['entry_icon'];
    $entry->Save();

    if($lastaction=='create')
    {
        PumpMessage('success',"Eintrag erfolgreich erstellt.");
    }
    else
    {
        PumpMessage('success',"Eintrag erfolgreich bearbeitet.");
    }

    $entry->RemoveFromExports();

    $currentexports = $_POST['entry_exports'];

    $forcedExports = ExportTemplate::GetAllForced();

    foreach($forcedExports as $export)
    {
        if(!in_array($export->id,$currentexports))
        {
            $currentexports[] = $export->id;
        }
    }

    $entry->AddToExports($currentexports);

    header('Location: '.$config['baseurl'].'/item/edithost.html?id='.$entry->id.'&success=1&lastaction='.$lastaction );
}


$smarty->assign('currentitemname',$currentitemname);
$smarty->assign('currententry',$currententry);
SetTitle($currentitemname.' bearbeiten');
