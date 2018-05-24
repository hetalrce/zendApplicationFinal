<?php

namespace User\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class UserController extends AbstractActionController
{

    /**
     *  @var DoctrineORMEntityManager
     */
    protected $_em;

    /**
     *  @var session data
     */
    protected $_session;

    /**
     *  @var Login Form
     */
    protected $_loginForm;

    /**
     *  @var Login Form Validation
     */
    protected $_loginFormValidation;

    /**
     * __Construct inject UserController Factory
     *
     * @access Public
     * @param $serviceLocator DoctrineORMEntityManager
     * @param $sessionConfig Session Data Class
     * @param $loginFormConfig Login Form Class
     * @param $loginFormValidationConfig Login Form Validation Class
     * @return Object
     */
    public function __construct($serviceLocator = null, $sessionConfig = null, $loginFormConfig = null, $loginFormValidationConfig = null)
    {

        if (!is_null($serviceLocator)) {
            $this->_em = $serviceLocator;
        }
        if (!is_null($sessionConfig)) {
            $this->_session = $sessionConfig;
        }
        if (!is_null($loginFormConfig)) {
            $this->_loginForm = $loginFormConfig;
        }
        if (!is_null($loginFormValidationConfig)) {
            $this->_loginFormValidation = $loginFormValidationConfig;
        }
    }

    /**
     * Login Form Action
     *
     * @package User
     * @access Public
     * @return Object ViewModel
     */
    public function indexAction()
    {
        $request = $this->getRequest();
        $errorList = [];
        if ($request->isPost()) {
            $this->_loginForm->setInputFilter($this->_loginFormValidation->getInputFilter());
            $this->_loginForm->setData($request->getPost());
            if ($this->_loginForm->isValid()) {
                $data = $this->_loginForm->getData();
                $userDetails = $this->_em->getRepository('User\Entity\User')->findOneBy(
                        array('email' => $data['email'], 'password' => md5($data['password']),)
                );
                if (!empty($userDetails)) {

                    if ($userDetails->role == 1) {
                        $this->_session->offsetSet('adminId', $userDetails->id);
                        $this->_session->offsetSet('adminEmail', $data['email']);
                        $this->_session->offsetSet('adminFirst_name', $userDetails->first_name);
                        $this->_session->offsetSet('adminLast_name', $userDetails->last_name);
                        return $this->redirect()->toRoute("admin");
                    } else {
                        $this->_session->offsetSet('userId', $userDetails->id);
                        $this->_session->offsetSet('email', $data['email']);
                        $this->_session->offsetSet('first_name', $userDetails->first_name);
                        $this->_session->offsetSet('last_name', $userDetails->last_name);
                        return $this->redirect()->toRoute("post");
                    }
                } else {
                    $errorList['error']['error_message'] = 'Invalid Email or Password.';
                }
            } else {
                $errorList = $this->_loginForm->getMessages();
            }
        }

        return array('loginForm' => $this->_loginForm, 'errorList' => $errorList);
    }

    /**
     * Logout Action
     *
     * @package User
     * @access Public
     * @return Object ViewModel
     */
    public function logoutAction()
    {
        $userId = $this->_session->offsetGet('userId');
        $sessionId = session_id();
        $this->_session->getManager()->destroy();
        $this->_session->getManager()->forgetMe();
        return $this->redirect()->toRoute('user');
    }

}
