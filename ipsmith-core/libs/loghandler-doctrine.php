<?php
use Doctrine\DBAL\Logging\SQLLogger;
use Monolog\Logger;
/**
 * Includes executed SQLs in a Debug Stack
 *
 * @license http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link    www.doctrine-project.org
 * @since   2.0
 * @version $Revision$
 * @author  Benjamin Eberlei <kon...@beberlei.de>
 * @author  Guilherme Blanco <guilher...@hotmail.com>
 * @author  Jonathan Wage <jon...@gmail.com>
 * @author  Roman Borschel <ro...@code-factory.org>
 * @author  Rainer Bendig <rbe@ipsmith.org>
 */
class IPSDebugStack implements SQLLogger
{
    /**
     * @var Logger
     */
    protected $logger;

    Public function __construct(Logger $logger) {
        $this->logger = $logger;
    }

    public function startQuery($sql, array $params = null, array $types = null)
    {

        //-- hide passwords
        if(isset($params["password"])) 
        { 
            $params["password"] = sprintf("**** SECRET WITH %s CHARS ****", strlen($params["password"])); 
        }
        $this->logger->addRecord(
                Logger::NOTICE, 
                $sql, 
                array('params' => $params, 'types' => $types)
            );

        if(!isset($_SESSION["stats"]) )
        {
            $_SESSION["stats"] = array();
        }

        if(!isset($_SESSION["stats"]["dbqueries"]))
        {
            $_SESSION["stats"]["dbqueries"] = 1;
        }
        else
        {
            $_SESSION["stats"]["dbqueries"]++;
        }
    }

    /**
     * Do nothing
     */
    public function stopQuery() {}
}
