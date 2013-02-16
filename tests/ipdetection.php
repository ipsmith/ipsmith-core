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

include(dirname(__FILE__).'/../ipsmith-core/libs/classes/IPAddress.class.php');

$ips = array('10.0.0.2','2A01:4F8:131:1403::2','88.198.51.179');

foreach($ips as $ip)
{
	echo "\n";
	echo "Working with    ".$ip."\n";
	echo "************************************************************\n";
	$address = new IPAddress($ip);
	echo "Adresse:       ".$address->IP."\n";
	echo "Decimal:       ".$address->DECIMAL."\n";
	echo "IsV4:          ".$address->IsIPv4."\n";
	echo "IsV6:          ".$address->IsIPv6."\n";
	echo "Addressrecord: ".$address->AddressRecord."\n";
	echo "\n";
	echo "\n";

	echo "Working with   ".$address->DECIMAL."\n";
	echo "************************************************************\n";
	$address = new IPAddress($address->DECIMAL);
	echo "Adresse:       ".$address->IP."\n";
	echo "Decimal:       ".$address->DECIMAL."\n";
	echo "IsV4:          ".$address->IsIPv4."\n";
	echo "IsV6:          ".$address->IsIPv6."\n";
	echo "Addressrecord: ".$address->AddressRecord."\n";
	echo "\n";
}