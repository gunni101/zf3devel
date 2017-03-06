<?php
namespace UserAuth\Model\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use UserAuth\Model\Service\AuthAdapter;

class AuthAdapterFactory implements FactoryInterface
{
	public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
	{
		$entityManager = $container->get('doctrine.entitymanager.orm_default');
		
		return new AuthAdapter($entityManager);
	}
}
