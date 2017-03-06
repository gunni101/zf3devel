<?php
namespace UserAuth\Frontend\Factory;

use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;
use UserAuth\Frontend\Controller\RegisterController;
use UserAuth\Model\Service\UserManager;
use GSMail\Service\GSMailManager;

class RegisterControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $userManager = $container->get(UserManager::class);
        $mailManager = $container->get(GSMailManager::class);
        return new RegisterController($entityManager, $userManager, $mailManager);
    }
}