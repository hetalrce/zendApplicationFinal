<?php

namespace Admin\Factory\Controller;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Doctrine\ORM\EntityManagerInterface;
use Admin\Controller\UsersController;
use Zend\Session\Container;
use Admin\Form\UserForm;
use Admin\Model\UserValidation;
use ReflectionClass;
use Zend\Hydrator\Reflection as ReflectionHydrator;
use Admin\Entity\Users;

class UsersControllerFactory implements FactoryInterface
{

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {

        $realServiceLocator = $serviceLocator->getServiceLocator();

        $postService = $realServiceLocator->get('doctrine.entitymanager.orm_default');

        $sessionConfig = new Container('User');

        $userForm = new UserForm();

        $userValidation = new UserValidation();

        $hydrator = new ReflectionHydrator();

        $reflaction_object = (new ReflectionClass(Users::class))->newInstanceWithoutConstructor();

        return new UsersController($postService, $sessionConfig, $userForm, $userValidation, $hydrator, $reflaction_object);
    }

    public function __invoke(\Interop\Container\ContainerInterface $container, $requestedName, mixed $options = null)
    {
        
    }

}
