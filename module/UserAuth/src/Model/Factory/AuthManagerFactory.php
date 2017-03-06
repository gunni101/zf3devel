<?php
namespace UserAuth\Model\Factory;

use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;
use Zend\Session\SessionManager;
use UserAuth\Model\Service\AuthManager;


class AuthManagerFactory implements FactoryInterface
{
	public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
	{
		$authenticationService = $container->get(\Zend\Authentication\AuthenticationService::class);
		$sessionManager = $container->get(SessionManager::class);
		
		$config = $container->get('Config');
		if(isset($config['access_filter']))
			$config = $config['access_filter'];
		else 
			$config = [];
		
		return new AuthManager($authenticationService, $sessionManager, $config);
	}
}