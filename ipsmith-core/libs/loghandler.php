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

$loader = require_once LIB_DIR.'/3rdparty/autoload.php';
$loader->add('Monolog\\',LIB_DIR.'/3rdparty/monolog/src');
use \Monolog\Logger;
use \Monolog\Handler\StreamHandler;
    const IPSMITH_DEBUG = 100;
    const IPSMITH_INFO = 200;
    const IPSMITH_NOTICE = 250;
    const IPSMITH_WARNING = 300;
    const IPSMITH_ERROR = 400;
    const IPSMITH_CRITICAL = 500;
    const IPSMITH_ALERT = 550;
    const IPSMITH_EMERGENCY = 600;

class LogHandlerClass
{
    public $logger = null;
    public $dblogger = null;

	function __construct()
	{
		if($this->logger==null)
		{
			$this->logger = new Logger('ipsmith');

			$this->logger->pushHandler(new StreamHandler(LOG_DIR.'/debug.log', Logger::DEBUG,false));
			$this->logger->pushhandler(new StreamHandler(LOG_DIR.'/info.log', Logger::INFO,false));
			$this->logger->pushHandler(new StreamHandler(LOG_DIR.'/notice.log', Logger::NOTICE,false));
			$this->logger->pushHandler(new StreamHandler(LOG_DIR.'/warning.log', Logger::WARNING,false));
			$this->logger->pushHandler(new StreamHandler(LOG_DIR.'/error.log', Logger::ERROR,false));
			$this->logger->pushHandler(new StreamHandler(LOG_DIR.'/critical.log', Logger::CRITICAL,false));
			$this->logger->pushHandler(new StreamHandler(LOG_DIR.'/alert.log', Logger::ALERT,false));
			$this->logger->pushHandler(new StreamHandler(LOG_DIR.'/emergency.log', Logger::EMERGENCY,false));

		}

        if($this->dblogger==null)
        {
            $this->dblogger = new Logger('doctrine');

            $this->dblogger->pushHandler(new StreamHandler(LOG_DIR.'/doctrine_debug.log', Logger::DEBUG,false));
            $this->dblogger->pushhandler(new StreamHandler(LOG_DIR.'/doctrune_info.log', Logger::INFO,false));
            $this->dblogger->pushHandler(new StreamHandler(LOG_DIR.'/doctrine_notice.log', Logger::NOTICE,false));
            $this->dblogger->pushHandler(new StreamHandler(LOG_DIR.'/doctrine_warning.log', Logger::WARNING,false));
            $this->dblogger->pushHandler(new StreamHandler(LOG_DIR.'/doctrine_error.log', Logger::ERROR,false));
            $this->dblogger->pushHandler(new StreamHandler(LOG_DIR.'/doctrine_critical.log', Logger::CRITICAL,false));
            $this->dblogger->pushHandler(new StreamHandler(LOG_DIR.'/doctrine_alert.log', Logger::ALERT,false));
            $this->dblogger->pushHandler(new StreamHandler(LOG_DIR.'/doctrine_emergency.log', Logger::EMERGENCY,false));

        }

	}

    private function ClearPassword($data)
    {

        if(is_array($data))
        {
            foreach($data as $key => $value)
            {
                $data[$key] = $this->ClearPassword($value);
            }
        }
        else
        {
            if(isset($data["password"]))
            {
                $data["password"] = sprintf("**** SECRET WITH %s CHARS ****", strlen($data["password"]));
            }
        }

        return $data;

    }

	public function getLogger()
	{
		return $this->logger;
	}

    public function getDbLogger()
    {
        return $this->dblogger;
    }

	public function Log($message,$level=200,$context = array())
	{
        $context = $this->ClearPassword($context);
		$this->logger->addRecord($level,$message, $context);
	}

    public function DbLog($message,$level=200,$context = array())
    {
        $context = $this->ClearPassword($context);
        $this->dblogger->addRecord($level,$message,$context);
    }

}
