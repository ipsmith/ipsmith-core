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

class IPAddress
{
    // result from ip2long('255.255.255.255')
    private $_maxipv4numeric = 4294967295;

    // result from ip2long('0.0.0.0')
    private $_minipv4numeric = 0;

    private $_type = 0;

    private $_ip2long = 0;

    private $_ip = '0.0.0.0';

    function __construct($value)
    {
        if(ctype_digit($value))
        {
            $this->_ip2long = $value;
            if($this->_ip2long <= $this->_maxipv4numeric &&
               $this->_ip2long >= $this->_minipv4numeric)
            {
                $this->_ip = long2ip($this->_ip2long);
                $this->_type = 4;
            }
            else
            {
                $this->_ip = $this->doLongToIPV6($this->_ip2long);
                $this->_type = 6;
            }
        }
        else
        {
            $this->_ip = $value;
            $this->_type = $this->detectType();
            $this->_ip2long = $this->toLong();
        }
    }

    public function toLong()
    {
        switch($this->_type)
        {
            case 6:
            return $this->doIPv6toLong($this->_ip);
            break;
            case 4:
            default;
            return ip2long($this->_ip);
            break;
        }
    }


    public function detectType()
    {
        if($this->isValidIPV6())
        {
            return 6;
        }
        else if ($this->isValidIPV4())
        {
            return 4;
        }
        return -1;
    }

    public function isValidIPV6()
    {
        // have a look at http://mebsd.com/coding-snipits/php-regex-ipv6-with-preg_match-revisited.html
        $regex = '/^(((?=(?>.*?(::))(?!.+\3)))\3?|([\dA-F]{1,4}(\3|:(?!$)|$)|\2))(?4){5}((?4){2}|(25[0-5]|(2[0-4]|1\d|[1-9])?\d)(\.(?7)){3})\z/i';

        if(!preg_match($regex, $this->_ip))
        {
            return false; // is not a valid IPv6 Address
        }

        return true;

    }

    public function isValidIPV4()
    {
        if( preg_match( "/^((?:25[0-5]|2[0-4][0-9]|[01]?[0-9]?[0-9]).){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9]?[0-9])$/m",$this->_ip) > 0)
        {
            return true;
        }

        return false;
    }

    private function doIPv6toLong($ipv6)
    {
        // read http://www.php.net/manual/en/function.ip2long.php#94477

        $ip_n = inet_pton($ipv6);
        $bits = 15; // 16 x 8 bit = 128bit

        while ($bits >= 0)
        {
            $bin = sprintf("%08b",(ord($ip_n[$bits])));
            $ipv6long = $bin.$ipv6long;
            $bits--;
        }

        return gmp_strval(gmp_init($ipv6long,2),10);
    }



    private function  doLongToIPV6($ipv6long)
    {
// read http://www.php.net/manual/en/function.ip2long.php#94477

        $bin = gmp_strval(gmp_init($ipv6long,10),2);
        if (strlen($bin) < 128)
        {
            $pad = 128 - strlen($bin);
            for ($i = 1; $i <= $pad; $i++)
            {
                $bin = "0".$bin;
            }
        }

        $bits = 0;
        while ($bits <= 7)
        {
            $bin_part = substr($bin,($bits*16),16);
            $ipv6 .= dechex(bindec($bin_part)).":";
            $bits++;
        }

// compress

        return inet_ntop(inet_pton(substr($ipv6,0,-1)));
    }


    public function getIPType()
    {
        return $this->_type;
    }

    public function getLong()
    {
        return $this->_ip2long;
    }

    public function getIP()
    {
        return $this->_ip;
    }

}