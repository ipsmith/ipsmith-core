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

use Doctrine\Common\ClassLoader;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL as ORM;

require ( LIB_DIR.'/3rdparty/doctrine-dbal/Doctrine/Common/ClassLoader.php');

$classLoader = new ClassLoader('Doctrine', LIB_DIR.'/3rdparty/doctrine-dbal/');
$classLoader->register();
require ( LIB_DIR . '/loghandler-doctrine.php');
$doctrineConfig = new \Doctrine\DBAL\Configuration();

$databaseLogger = new IPSDebugStack($LogHandler->getDbLogger());
$doctrineConfig->setSQLLogger($databaseLogger );

$doctrineConnectionParams = array(
                                  'dbname' => $config["db"]["name"],
                                  'user' => $config["db"]["user"],
                                  'password' => $config["db"]["pass"],
                                  'host' => $config["db"]["host"],
                                  'driver' => $config["db"]["driver"],
                                  'charset' => 'utf8',
                                  'driverOptions' => array(
                                                           1002=>'SET NAMES utf8'
                                                           )
);

$doctrineConnection = DriverManager::getConnection($doctrineConnectionParams, $doctrineConfig);
$doctrineConnection->driverOptions = array(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

