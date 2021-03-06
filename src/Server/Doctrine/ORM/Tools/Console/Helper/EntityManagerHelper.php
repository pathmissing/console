<?php

/**
 * AppserverIo\Console\Server\Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * PHP version 5
 *
 * @author    Tim Wagner <tw@appserver.io>
 * @copyright 2018 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/console
 * @link      http://www.appserver.io
 */

namespace AppserverIo\Console\Server\Doctrine\ORM\Tools\Console\Helper;

use AppserverIo\Psr\Application\ApplicationInterface;
use AppserverIo\Psr\EnterpriseBeans\PersistenceContextInterface;

/**
 * Helper implementation that returns the application's entity manager instance.
 *
 * @author    Tim Wagner <tw@appserver.io>
 * @copyright 2018 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/console
 * @link      http://www.appserver.io
 */
class EntityManagerHelper extends \Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper
{

    /**
     * The application instance.
     *
     * @var \AppserverIo\Psr\Application\ApplicationInterface
     */
    protected $application;

    /**
     * Initializes the helper with the actual application instance.
     *
     * @param \AppserverIo\Psr\Application\ApplicationInterface $application The application instance
     */
    public function __construct(ApplicationInterface $application)
    {
        $this->application = $application;
    }

    /**
     * Return's the application intance.
     *
     * @return \AppserverIo\Psr\Application\ApplicationInterface The application instance
     */
    protected function getApplication()
    {
        return $this->application;
    }

    /**
     * Retrieves the default Doctrine ORM EntityManager.
     *
     * @return \Doctrine\ORM\EntityManagerInterface The entity manager instance
     * @throws \Exception Is thrown, if not default entity manager is available
     */
    public function getEntityManager()
    {

        // load the applications persistence manager
        /** @var \AppserverIo\Psr\EnterpriseBeans\PersistenceContextInterface $persistenceManager */
        $persistenceManager = $this->getApplication()->search(PersistenceContextInterface::IDENTIFIER);

        // return the first entity manager
        foreach ($persistenceManager->getEntityManagerNames() as $lookupName) {
            return $persistenceManager->lookup($lookupName);
        }

        // throw an exception, if no default entity manager is available
        throw new \Exception(sprintf('Can\'t load default entity manager for application "%s"', $this->getApplication()->getUniqueName()));
    }

    /**
     * Returns the canonical name of this helper.
     *
     * @return string The canonical name
     * @see \Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper::getName()
     */
    public function getName()
    {
        return 'em';
    }
}
