<?php

namespace User;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\ResultSet\ResultSet;
use User\Service\User;
use Zend\Authentication\Adapter\DbTable as DbTableAuthAdapter;
use Zend\Authentication\AuthenticationService;

class Module {

    public function onBootstrap(MvcEvent $e) {

        $eventManager = $e->getApplication()->getEventManager();

        $moduleRouteListener = new ModuleRouteListener();

        $moduleRouteListener->attach($eventManager);
    }

    public function getConfig() {

        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig() {

        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    // Automatically invoked by service manager
    public function getServiceConfig() {
        return array(
            'factories' => array(
                'User\Model\UserTable' => function($sm) {
                    $tableGateway = $sm->get('UserTableGateway');
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $table = new UserTable($tableGateway, $dbAdapter);
                    return $table;
                },
                'User\Service\user' => function ($serviceManager) {
                    return new User(null, $serviceManager);
                },
                'UserTableGateway' => function($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new User());
                    return new TableGateway('users', $dbAdapter, null, $resultSetPrototype);
                },
//                'AuthService' => function ($serviceManager) {
//                    $dbAdapter = $serviceManager->get('Zend\Db\Adapter\Adapter');
//                    $dbTableAuthAdapter = new DbTableAuthAdapter($dbAdapter, 'users', 'email', 'password');
//
//                    $authService = new AuthenticationService();
//                    $authService->setAdapter($dbTableAuthAdapter);
//                    $authService->setStorage($serviceManager->get('User\Model\AuthStoragez'));
//
//                    return $authService;
//                }
            )
        );
    }

}
