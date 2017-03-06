<?php
namespace UserAuth\Model\Factory;

use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;
use Zend\Session\SessionManager;
use Zend\Authentication\Storage\Session as SessionStorage;
use UserAuth\Model\Service\AuthAdapter;
use Zend\Authentication\AuthenticationService;
use UserAuth\Entity\UserEntity;

class AuthenticationServiceFactory implements FactoryInterface
{
	public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
	{
		$sessionManager = $container->get(SessionManager::class);
		$authStorage = new SessionStorage('Zend_Auth', 'session', $sessionManager);
		$authAdapter = $container->get(AuthAdapter::class);
		
		return new AuthenticationService($authStorage, $authAdapter);
	}
}