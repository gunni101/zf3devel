<?php
namespace GSMail\Service;

use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;
use GSMail\Service\GSMailManager;

class GSMailManagerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get('configuration');
        return new GSMailManager($config['gsmail']);
    }
}