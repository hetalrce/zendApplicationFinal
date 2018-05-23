<?php

namespace User\Factory\Controller;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Doctrine\ORM\EntityManagerInterface;
use User\Controller\PostController;
use Zend\Session\Container;
use User\Form\PostForm;
use User\Model\PostValidation;
use ReflectionClass;
use Zend\Hydrator\Reflection as ReflectionHydrator;
use User\Entity\Post;

class PostControllerFactory implements FactoryInterface {

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

        $postFormConfig = new PostForm();

        $postFormValidationConfig = new PostValidation();

        $hydrator = new ReflectionHydrator();

        $reflaction_object = (new ReflectionClass(Post::class))->newInstanceWithoutConstructor();


        return new PostController($postService, $sessionConfig, $postFormConfig, $postFormValidationConfig, $hydrator, $reflaction_object);
    }

    public function __invoke(\Interop\Container\ContainerInterface $container, $requestedName, mixed $options = null) {
        
    }

}
