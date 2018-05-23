<?php

namespace User\Service;

//use Zend\ServiceManager\ServiceLocatorAwareInterface;
//use Zend\ServiceManager\ServiceLocatorAwareTrait;
//use Zend\ServiceManager\Factory\FactoryInterface;

use Doctrine\ORM\EntityManager;

class User {

    /**
     * @var DoctrineORMEntityManager
     */
//    protected $em;
//
//    public function __invoke(ContainerInterface $container, $requestedName, array $options = NULL) {
//        $this->em = $container->get('doctrine.entitymanager.orm_default');
//        
//    }
//    
//    public function createService(\Zend\Di\ServiceLocatorInterface $serviceLocator)
//    {
//        return $this($serviceLocator, \User\Entity\Post::class);
//    }


    public function getEntityManager() {
        echo 'aaa';
        exit;
        echo '<pre>';
        var_dump($this->getServiceLocator());
        ECHO 'AAA';
        exit;
        //  return $this->em;
    }

}
