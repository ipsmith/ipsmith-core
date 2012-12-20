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

$selectAttributesQuery = 'SELECT * FROM attribute_definitions ORDER BY id asc;';
$selectAttributesStmt  = $doctrineConnection->prepare($selectAttributesQuery);

$countAttributesQuery = 'SELECT count(attributeid) AS counter FROM attributes2entry WHERE attributeid=:attributeid;';
$countAttributesStmt  = $doctrineConnection->prepare($countAttributesQuery);


$selectAttributesStmt->execute();

$attributes = array();
while($selectAttributesRow = $selectAttributesStmt->fetch())
{
    $_row = $selectAttributesRow;

    $countAttributesStmt->bindValue('attributeid',$selectAttributesRow['id']);
    $countAttributesStmt->execute();

    if($countAttributesRow = $countAttributesStmt->fetch())
    {
            $_row['counter'] = $countAttributesRow['counter'];
    }

    $attributes[] = $_row;
}

$smarty->assign('attributes', $attributes);