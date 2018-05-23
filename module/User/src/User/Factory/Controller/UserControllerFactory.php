<?php

namespace User\Factory\Controller;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Doctrine\ORM\EntityManagerInterface;
use User\Controller\UserController;
use Zend\Session\Container;
use User\Form\LoginForm;
use User\Model\LoginValidation;

class UserControllerFactory implements FactoryInterface {

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

        $loginFormConfig = new LoginForm();

        $loginFormValidationConfig = new LoginValidation();

        return new UserController($postService, $sessionConfig, $loginFormConfig, $loginFormValidationConfig);
    }

    public function __invoke(\Interop\Container\ContainerInterface $container, $requestedName, mixed $options = null) {
        
    }

}
