<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace UserAuth;

use Zend\Mvc\MvcEvent;
use Zend\Session\SessionManager;

class Module
{

    /**
     * This method returns the path to module.config.php file.
     */
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }
    
    public function onBootstrap(MvcEvent $event) {
    	$application = $event->getApplication();
    	$serviceManager = $application->getServiceManager();
    	$sessionManager = $serviceManager->get(SessionManager::class);
    }
}
