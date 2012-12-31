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

$typeQuery = 'SELECT * FROM types ORDER BY typename;';
$typeStmt = $doctrineConnection->prepare($typeQuery);
$typeStmt->execute();

$types = array();
while($row = $typeStmt->fetch())
{
    $types[] = $row;
}
$smarty->assign('types',$types);

$exportQuery = 'SELECT * FROM exports ORDER BY exportname;';
$exportStmt = $doctrineConnection->prepare($exportQuery);
$exportStmt->execute();

$exports = array();
while($row = $exportStmt->fetch())
{
    $exports[] = $row;
}

$smarty->assign('allexports',$exports);


$selectLogsQuery = 'SELECT * FROM tablelog WHERE tablename=:tablename '
                 . 'AND tableid=:tableid ORDER BY id DESC;';

$selectLogsStmt = $doctrineConnection->prepare($selectLogsQuery);
$selectLogsStmt->bindValue('tablename','entries');
$selectLogsStmt->bindValue('tableid',$requestedid);
$selectLogsStmt->execute();

$logentries = array();
while($row = $selectLogsStmt->fetch())
{
    $logentries[] = $row;
}

$smarty->assign('logentries',$logentries);

$export2entryQuery = 'SELECT * FROM entry2export WHERE  dataid=:id;';
$export2entryStmt = $doctrineConnection->prepare($export2entryQuery);
$export2entryStmt->bindValue('id',$requestedid);
$export2entryStmt->execute();

$exportids = array();
while($row = $export2entryStmt->fetch())
{
    $exportids[] = $row['exportid'];
}

$selectedexports = array();
$availableexports = array();
foreach($exports as $export)
{
    if(in_array($export['id'],$exportids))
    {
        if($export['force_assignment']==0)
        {
            $selectedexports[] = $export;
        }
    }
    else
    {
        if($export['force_assignment']==0)
        {
            $availableexports[] = $export;
        }
    }
}

$smarty->assign('selectedexports',$selectedexports);
$smarty->assign('availableexports',$availableexports);

$catQuery = 'SELECT * FROM categories ORDER BY ordernumber;';
$catStmt = $doctrineConnection->prepare($catQuery);
$catStmt->execute();

$cats = array();
while($row = $catStmt->fetch())
{
    $cats[] = $row;
}
$smarty->assign('cats',$cats);

$currentitemname = 'Neuen Eintrag';

if($requestedid>0)
{
    $q = 'SELECT * FROM entries WHERE id= :id ';

    $stmt = $doctrineConnection->prepare($q);
    $stmt->bindValue('id', $requestedid);
    $stmt->execute();

    if($row = $stmt->fetch())
    {
        $currententry = $row;
        $currentitemname = $row['hostname'];
    }
}

if(isset($_POST['submit']))
{
    $rgb = hex2rgb($_POST['entry_color_hex']);

    if(isset($_POST['entry_id']) && $_POST['entry_id']>0)
    {
        $updateQuery = 'UPDATE entries SET macaddress=:macaddress , '
                     . 'hostname=:hostname , ip=:ip , ipnum=:ipnum, '
                     . 'color_hex=:colorhex, color_r=:colorr, '
                     . 'color_g=:colorg, color_b=:colorb, '
                     . 'locationid=:locationid, typeid=:typeid, catid=:catid, '
                     . 'iconpath=:iconpath, '
                     . 'updatedat=CURRENT_TIMESTAMP, updatedby=:updatedby '
                     . 'WHERE id=:id;';

        $updateStmt = $doctrineConnection->prepare($updateQuery);

        $updateStmt->bindValue('macaddress' , $_POST['entry_mac']);
        $updateStmt->bindValue('hostname'   , $_POST['entry_hostname']);
        $updateStmt->bindValue('ip'         , $_POST['entry_ip']);
        $updateStmt->bindValue('ipnum'      , ip2long($_POST['entry_ip']));
        $updateStmt->bindValue('colorhex'   , $_POST['entry_color_hex']);
        $updateStmt->bindValue('colorr'     , $rgb[0]);
        $updateStmt->bindValue('colorg'     , $rgb[1]);
        $updateStmt->bindValue('colorb'     , $rgb[2]);
        $updateStmt->bindValue('locationid' , $_POST['entry_location']);
        $updateStmt->bindValue('typeid'     , $_POST['entry_type']);
        $updateStmt->bindValue('catid'      , $_POST['entry_cat']);
        $updateStmt->bindValue('iconpath'   , $_POST['entry_icon']);
        $updateStmt->bindValue('updatedby'  , $_SESSION['userdata']['username']);
        $updateStmt->bindValue('id'         , $_POST['entry_id']);

        $updateStmt->execute();
        $requestedid = $_POST['entry_id'];
        $lastaction = 'update';
    }

    if(!isset($_POST['entry_id']))
    {
        $insertQuery = 'INSERT INTO entries (macaddress, '
                     . 'hostname,ip,ipnum, color_hex, color_r, '
                     . 'color_g, color_b, locationid, typeid, catid, '
                     . 'iconpath, createdat, createdby ) VALUES '
                     . '(:macaddress , :hostname , :ip , :ipnum , '
                     . ':colorhex , :colorr , :colorg , :colorb , '
                     . ':locationid , :typeid , :catid , :iconpath , '
                     . 'CURRENT_TIMESTAMP , :createdby ); ';

        $insertStmt = $doctrineConnection->prepare($insertQuery);

        $insertStmt->bindValue('macaddress' , $_POST['entry_mac']);
        $insertStmt->bindValue('hostname'   , $_POST['entry_hostname']);
        $insertStmt->bindValue('ip'         , $_POST['entry_ip']);
        $insertStmt->bindValue('ipnum'      , ip2long($_POST['entry_ip']));
        $insertStmt->bindValue('colorhex'   , $_POST['entry_color_hex']);
        $insertStmt->bindValue('colorr'     , $rgb[0]);
        $insertStmt->bindValue('colorg'     , $rgb[1]);
        $insertStmt->bindValue('colorb'     , $rgb[2]);
        $insertStmt->bindValue('locationid' , $_POST['entry_location']);
        $insertStmt->bindValue('typeid'     , $_POST['entry_type']);
        $insertStmt->bindValue('catid'      , $_POST['entry_cat']);
        $insertStmt->bindValue('iconpath'   , $_POST['entry_icon']);
        $insertStmt->bindValue('createdby'  , $_SESSION['userdata']['username']);

        $insertStmt->execute();

        $lastaction = 'create';

        $requestedid = $doctrineConnection->lastInsertId();
    }

    $logQuery = 'INSERT INTO tablelog (tablename, tableid, username, action) '
              . 'VALUES (:tablename, :tableid, :username, :action);';

    $logStmt = $doctrineConnection->prepare($logQuery);
    $logStmt->bindValue('tablename','entries');
    $logStmt->bindValue('tableid',$requestedid);
    $logStmt->bindValue('username',$_SESSION['userdata']['username']);

    if($lastaction=='update')
    {
        $logStmt->bindValue('action','Entry #'.$requestedid.' updated');
    }
    else
    {
        $logStmt->bindValue('action','Entry #'.$requestedid.' created');
    }

    $logStmt->execute();

    $deleteAssignmentQuery = 'DELETE FROM entry2export WHERE dataid=:dataid ;';
    $deleteAssignmentStmt = $doctrineConnection->prepare($deleteAssignmentQuery);
    $deleteAssignmentStmt->bindValue('dataid',$requestedid);
    $deleteAssignmentStmt->execute();

    $currentexports = $_POST['entry_exports'];

    foreach($exports as $export)
    {
        if(!in_array($export['id'],$currentexports))
        {
            if($export['force_assignment']==1)
            {
                $currentexports[] = $export['id'];
            }
        }
    }

    $insertExportQuery = 'INSERT INTO entry2export (dataid,exportid) VALUES '
                       . '(:dataid,:exportid);';

    $insertExportStmt = $doctrineConnection->prepare($insertExportQuery);

    foreach($currentexports as $insertexport)
    {
        $insertExportStmt->bindValue('dataid',$requestedid);
        $insertExportStmt->bindValue('exportid',$insertexport);
        $insertExportStmt->execute();
    }

    header('Location: '.$config['baseurl'].'/item/edithost.html?id='.$requestedid);
}

$smarty->assign('currentitemname',$currentitemname);
$smarty->assign('currententry',$currententry);

SetTitle($currentitemname.' bearbeiten');