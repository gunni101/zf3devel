<?php
namespace UserAuth\Frontend\Factory;

use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;
use UserAuth\Model\Service\UserManager;
use UserAuth\Frontend\Controller\AuthController;
use UserAuth\Model\Service\AuthManager;

class AuthControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $authManager = $container->get(AuthManager::class);
        $authServie = $container->get(\Zend\Authentication\AuthenticationService::class);
        $userManager = $container->get(UserManager::class);
        return new AuthController($entityManager, $authManager, $authServie, $userManager);
    }
}