<?php
namespace UserAuth\Model\Factory;

use Interop\Container\ContainerInterface;
use UserAuth\Model\Service\UserManager;
use GSMail\Service\GSMailManager;

class UserManagerFactory 
{
	public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
	{
		$entityManager = $container->get('doctrine.entitymanager.orm_default');
		$mailManager = $container->get(GSMailManager::class);
		return new UserManager($entityManager, $mailManager);
	}
}