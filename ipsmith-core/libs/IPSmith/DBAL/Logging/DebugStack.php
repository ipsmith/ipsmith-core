<?php
namespace IPSmith\DBAL\Logging;
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
 * @author  Roman Borschel <rbe@ipsmith.org>
 */
class DebugStack implements SQLLogger
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
        $this->logger->debug($sql, array('params' => $params, 'types' => $types));
    }

    /**
     * Do nothing
     */
    public function stopQuery() {}
}