<?php

namespace Admin\Factory\Controller;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Doctrine\ORM\EntityManagerInterface;
use Admin\Controller\DashboardController;
use Zend\Session\Container;

class DashboardControllerFactory implements FactoryInterface {

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator) {

        $realServiceLocator = $serviceLocator->getServiceLocator();

        $postService = $realServiceLocator->get('doctrine.entitymanager.orm_default');

        $sessionConfig = new Container('User');

        return new DashboardController($postService, $sessionConfig);
    }

    public function __invoke(\Interop\Container\ContainerInterface $container, $requestedName, mixed $options = null) {
        
    }

}
