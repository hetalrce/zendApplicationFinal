<?php

namespace User;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\ResultSet\ResultSet;
use User\Service\User;
use Zend\Authentication\Adapter\DbTable as DbTableAuthAdapter;
use Zend\Authentication\AuthenticationService;
use User\Service\UserEncryption;
use User\Service\UserMailServices;

class Module
{

    public function onBootstrap(MvcEvent $e)
    {

        $eventManager = $e->getApplication()->getEventManager();

        $moduleRouteListener = new ModuleRouteListener();

        $moduleRouteListener->attach($eventManager);
    }

    public function getConfig()
    {

        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {

        return [
            'Zend\Loader\StandardAutoloader' => [
                'namespaces' => [
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ],
            ],
        ];
    }

    // Automatically invoked by service manager
    public function getServiceConfig()
    {
        return [
            'factories' => [
                'User\Model\UserTable' => function($sm)
                {
                    $tableGateway = $sm->get('UserTableGateway');
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $table = new UserTable($tableGateway, $dbAdapter);
                    return $table;
                },
                'User\Service\user' => function ($serviceManager)
                {
                    return new User(null, $serviceManager);
                },
                'User\Service\UserEncryption' => function ($serviceManager)
                {
                    return new UserEncryption(null, $serviceManager);
                },
                'User\Service\UserMailServices' => function ($serviceManager)
                {
                    return new UserMailServices($serviceManager);
                },
                'UserTableGateway' => function($sm)
                {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new User());
                    return new TableGateway('users', $dbAdapter, null, $resultSetPrototype);
                },
            ]
        ];
    }

}
