<?php

namespace Admin;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\ResultSet\ResultSet;

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
                'UserTableGateway' => function($sm)
                {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new User());
                    return new TableGateway('users', $dbAdapter, null, $resultSetPrototype);
                }
            ],
        ];
    }

}
