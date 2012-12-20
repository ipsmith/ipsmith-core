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

class GuidGenerator
{
    public static function Create($context=null)
    {
        $_value = uniqid($context,true);
        $_value = hash('sha512',$_value);

        $_unique = substr($_value, 0, 8);
        $_unique.="-";
        $_unique.= substr($_value, 9, 4);
        $_unique.="-";
        $_unique.= substr($_value, 13, 4);
        $_unique.="-";
        $_unique.= substr($_value, 17, 4);
        $_unique.="-";
        $_unique.= substr($_value, 21, 12);
        return strtoupper($_unique);
    }
}