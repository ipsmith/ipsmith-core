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
else if(isset($_POST['type_id']))
{
    $requestedid = $_POST['type_id'];
}

$smarty->assign('currentid',$requestedid);



$selectLogsQuery = 'SELECT * FROM tablelog WHERE tablename=:tablename '
                 . 'AND tableid=:tableid ORDER BY id DESC;';

$selectLogsStmt = $doctrineConnection->prepare($selectLogsQuery);
$selectLogsStmt->bindValue('tablename','types');
$selectLogsStmt->bindValue('tableid',$requestedid);
$selectLogsStmt->execute();

$logentries = array();
while($row = $selectLogsStmt->fetch())
{
    $logentries[] = $row;
}

$smarty->assign('logentries',$logentries);


$currentitemname = 'Neuen Typ';

if($requestedid>0)
{
    $q = 'SELECT * FROM types WHERE id= :id ';

    $stmt = $doctrineConnection->prepare($q);
    $stmt->bindValue('id', $requestedid);
    $stmt->execute();

    if($row = $stmt->fetch())
    {
        $currententry = $row;
        $currentitemname = $row['typename'];
    }
}

if(isset($_POST['submit']))
{
    $bgrgb = hex2rgb($_POST['type_bgcolor_hex']);
    $fgrgb = hex2rgb($_POST['type_fgcolor_hex']);

    if(isset($_POST['type_id']) && $_POST['type_id']>0)
    {
        $updateQuery = 'UPDATE types SET typename=:typename , '
                     . 'bgcolor_hex=:bgcolorhex, bgcolor_r=:bgcolorr, '
                     . 'bgcolor_g=:bgcolorg, bgcolor_b=:bgcolorb, '
                     . 'fgcolor_hex=:fgcolorhex, fgcolor_r=:fgcolorr, '
                     . 'fgcolor_g=:fgcolorg, fgcolor_b=:fgcolorb, '
                     . 'modifiedat=CURRENT_TIMESTAMP, modifiedby=:modifiedby '
                     . 'WHERE id=:id;';

        $updateStmt = $doctrineConnection->prepare($updateQuery);

        $updateStmt->bindValue('typename'     , $_POST['type_typename']);
        $updateStmt->bindValue('bgcolorhex'   , $_POST['type_bgcolor_hex']);
        $updateStmt->bindValue('bgcolorr'     , $bgrgb[0]);
        $updateStmt->bindValue('bgcolorg'     , $bgrgb[1]);
        $updateStmt->bindValue('bgcolorb'     , $bgrgb[2]);
        $updateStmt->bindValue('fgcolorhex'   , $_POST['type_fgcolor_hex']);
        $updateStmt->bindValue('fgcolorr'     , $fgrgb[0]);
        $updateStmt->bindValue('fgcolorg'     , $fgrgb[1]);
        $updateStmt->bindValue('fgcolorb'     , $fgrgb[2]);
        $updateStmt->bindValue('modifiedby'   , $_SESSION['userdata']['username']);
        $updateStmt->bindValue('id'           , $_POST['type_id']);

        $updateStmt->execute();
        $requestedid = $_POST['type_id'];
        $lastaction = 'update';
    }

    if(!isset($_POST['type_id']))
    {
        $insertQuery = 'INSERT INTO types (typename, guid, '
                     . 'bgcolor_hex, bgcolor_r, bgcolor_g, bgcolor_b, '
                     . 'fgcolor_hex, fgcolor_r, fgcolor_g, fgcolor_b, '
                     . 'createdat, createdby ) VALUES '
                     . '(:typename, :guid,  '
                     . ':bgcolorhex , :bgcolorr , :bgcolorg , :bgcolorb , '
                     . ':fgcolorhex , :fgcolorr , :fgcolorg , :fgcolorb , '
                     . 'CURRENT_TIMESTAMP , :createdby ); ';

        $insertStmt = $doctrineConnection->prepare($insertQuery);

        $insertStmt->bindValue('guid'         , GuidGenerator::Create('type'));
        $insertStmt->bindValue('typename'     , $_POST['type_typename']);
        $insertStmt->bindValue('bgcolorhex'   , $_POST['type_bgcolor_hex']);
        $insertStmt->bindValue('bgcolorr'     , $bgrgb[0]);
        $insertStmt->bindValue('bgcolorg'     , $bgrgb[1]);
        $insertStmt->bindValue('bgcolorb'     , $bgrgb[2]);
        $insertStmt->bindValue('fgcolorhex'   , $_POST['type_fgcolor_hex']);
        $insertStmt->bindValue('fgcolorr'     , $fgrgb[0]);
        $insertStmt->bindValue('fgcolorg'     , $fgrgb[1]);
        $insertStmt->bindValue('fgcolorb'     , $fgrgb[2]);
        $insertStmt->bindValue('createdby'    , $_SESSION['userdata']['username']);

        $insertStmt->execute();

        $lastaction = 'create';

        $requestedid = $doctrineConnection->lastInsertId();
    }

    $logQuery = 'INSERT INTO tablelog (tablename, tableid, username, action) '
              . 'VALUES (:tablename, :tableid, :username, :action);';

    $logStmt = $doctrineConnection->prepare($logQuery);
    $logStmt->bindValue('tablename','types');
    $logStmt->bindValue('tableid',$requestedid);
    $logStmt->bindValue('username',$_SESSION['userdata']['username']);

    if($lastaction=='update')
    {
        $logStmt->bindValue('action','Type #'.$requestedid.' updated');
    }
    else
    {
        $logStmt->bindValue('action','Type #'.$requestedid.' created');
    }

    $logStmt->execute();

    header('Location: '.$config['baseurl'].'/types/edit.html?id='.$requestedid);
}

$smarty->assign('currentitemname',$currentitemname);
$smarty->assign('currententry',$currententry);

SetTitle($currentitemname.' bearbeiten');