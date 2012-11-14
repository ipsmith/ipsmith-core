<?php
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
		 
	}
	
	public function getLogger()
	{
		return $this->logger;
	}
	public function Log($message,$level=200,$context = array())
	{
		$this->logger->addRecord($level,$message, $context);
	}
}