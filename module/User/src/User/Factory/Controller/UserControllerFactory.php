<?php

namespace User\Factory\Controller;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Doctrine\ORM\EntityManagerInterface;
use User\Controller\UserController;
use Zend\Session\Container;
use User\Form\LoginForm;
use User\Model\LoginValidation;
use User\Form\RegisterForm;
use User\Model\RegisterValidation;
use ReflectionClass;
use Zend\Hydrator\Reflection as ReflectionHydrator;
use User\Entity\User;
use User\Form\ForgotPasswordForm;
use User\Form\ResetPasswordForm;
use Zend\View\Renderer\PhpRenderer;
use \Zend\View\Resolver\TemplateMapResolver;

class UserControllerFactory implements FactoryInterface
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

        $loginFormConfig = new LoginForm();

        $loginFormValidationConfig = new LoginValidation();


        $registerForm = new RegisterForm();

        $registerFormValidation = new RegisterValidation('registerValidation');

        $hydrator = new ReflectionHydrator();

        $reflaction_object = (new ReflectionClass(User::class))->newInstanceWithoutConstructor();


        $forgotPasswordForm = new ForgotPasswordForm();


        $resetPasswordForm = new ResetPasswordForm();

        $phpRenderView = new PhpRenderer();

        $resolver = new TemplateMapResolver();
       
        $viewModel = new \Zend\View\Model\ViewModel();
 
        return new UserController($postService, $sessionConfig, $loginFormConfig, $loginFormValidationConfig, $registerForm, $registerFormValidation, $hydrator, $reflaction_object, $forgotPasswordForm, $resetPasswordForm, $phpRenderView, $resolver,$viewModel);
    }

    public function __invoke(\Interop\Container\ContainerInterface $container, $requestedName, mixed $options = null)
    {
        
    }

}
