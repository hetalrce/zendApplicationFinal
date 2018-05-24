<?php

namespace Admin\Factory\Controller;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Doctrine\ORM\EntityManagerInterface;
use Admin\Controller\PostsController;
use Zend\Session\Container;
use ReflectionClass;
use Zend\Hydrator\Reflection as ReflectionHydrator;
use Admin\Entity\Posts;
use Admin\Form\PostsForm;
use Admin\Model\PostsValidation;

class PostsControllerFactory implements FactoryInterface
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

        $hydrator = new ReflectionHydrator();

        $reflaction_object = (new ReflectionClass(Posts::class))->newInstanceWithoutConstructor();

        $PostsForm = new PostsForm();

        $postValidation = new PostsValidation();

        return new PostsController($postService, $sessionConfig, $hydrator, $reflaction_object, $PostsForm, $postValidation);
    }

    public function __invoke(\Interop\Container\ContainerInterface $container, $requestedName, mixed $options = null)
    {
        
    }

}
