<?php

namespace User\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use User\Hydrator\Users;

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
     *  @var Registration Form
     */
    protected $_registerForm;

    /**
     *  @var Registration Form Validation
     */
    protected $_registerFormValidation;

    /**
     *  @var Hydrate data from array 
     */
    protected $_hydrate;

    /**
     *  @var Reflect class object for hydrate 
     */
    protected $_object;

    /**
     *  @var Forgot password Form
     */
    protected $_forgotPasswordForm;

    /**
     *  @var Reset Password Form Validation
     */
    protected $_resetPasswordForm;

    /**
     *  @var Render View
     */
    protected $_phpRenderView;

    /**
     *  @var Template Resolver
     */
    protected $_resolver;

    /**
     *  @var View Model
     */
    protected $_viewModel;

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
    public function __construct($serviceLocator = null, $sessionConfig = null, $loginFormConfig = null, $loginFormValidationConfig = null, $registerForm = null, $registerFormValidation = null, $hydrator = null, $reflaction_object = null, $forgotPasswordForm = null, $resetPasswordForm = null, $phpRenderView = null, $resolver = null, $viewModel = null)
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
        if (!is_null($registerForm)) {
            $this->_registerForm = $registerForm;
        }
        if (!is_null($registerFormValidation)) {
            $this->_registerFormValidation = $registerFormValidation;
        }
        if (!is_null($hydrator)) {
            $this->_hydrate = $hydrator;
        }
        if (!is_null($reflaction_object)) {
            $this->_object = $reflaction_object;
        }

        if (!is_null($forgotPasswordForm)) {
            $this->_forgotPasswordForm = $forgotPasswordForm;
        }
        if (!is_null($resetPasswordForm)) {
            $this->_resetPasswordForm = $resetPasswordForm;
        }

        if (!is_null($phpRenderView)) {
            $this->_phpRenderView = $phpRenderView;
        }
        if (!is_null($resolver)) {
            $this->_resolver = $resolver;
        }
        if (!is_null($viewModel)) {
            $this->_viewModel = $viewModel;
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
                        ['email' => $data['email'], 'password' => md5($data['password']),]
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

        return ['loginForm' => $this->_loginForm, 'errorList' => $errorList];
    }

    /**
     * Registration Form Action
     *
     * @package User
     * @access Public
     * @return Object ViewModel
     */
    public function registerAction()
    {
        $errorList = [];
        $request = $this->getRequest();
        if ($request->isPost()) {
            $this->_registerForm->setInputFilter($this->_registerFormValidation->getInputFilter());
            $this->_registerForm->setData($request->getPost());
            if ($this->_registerForm->isValid()) {
                $postData = $this->_registerForm->getData();
                $postData['password'] = md5($postData['password']);
                $postData['role'] = 2;
                $data = $this->_hydrate->hydrate(
                        $postData, $this->_object
                );
                $this->_em->persist($data);
                $this->_em->flush();
                return $this->redirect()->toRoute("user");
            } else {
                $errorList = $this->_registerForm->getMessages();
            }
        }
        return ['registerForm' => $this->_registerForm, 'errorList' => $errorList,];
    }

    /**
     * Forgot password Action
     *
     * @package User
     * @access Public
     * @return Object ViewModel
     */
    public function forgotPasswordAction()
    {
        $request = $this->getRequest();
        if ($request->isPost()) {

            $this->_forgotPasswordForm->setData($request->getPost());
            $config = $this->getServiceLocator()->get('config'); //config data get
            if ($this->_forgotPasswordForm->isValid()) {
                $userPassword = $this->getServiceLocator()->get('User\Service\UserEncryption'); //for password encrypt
                $UserMailServices = $this->getServiceLocator()->get('User\Service\UserMailServices');
                $data = $this->_forgotPasswordForm->getData();


                $userDetails = $this->_em->getRepository('User\Entity\User')->findOneBy(
                        ['email' => $data['email'],]
                );


                if (!empty($userDetails)) {
                    $emailID = $data['email'];
                    $time = time();
                    $encrytedKey = $userPassword->encryptUrlParameter($emailID . '|' . $userDetails->id . '|' . $time);

                    // /// Mail Code will be here //////////
                    $message['resetLink'] = $config['settings']['BASE_URL'] . '/user/reset-password/token/' . $encrytedKey;

                    $message['userName'] = $userDetails->first_name . " " . $userDetails->last_name;
                    $mailData['mailTo'] = $emailID;

                    $mailData['mailFrom'] = $config['settings']['EMAIL']['FROM'];

                    $mailData['mailFromNickName'] = $config['settings']['EMAIL']['MAIL_FROM_NICK_NAME'];

                    $mailData['mailSubject'] = $config['settings']['FORGOT_PASSWORD_SUBJECT'];

                    $mailData['mailBody'] = $this->getForgotPasswordTemplate($message);

                    echo $mailData['mailBody'];

                    $UserMailServices->sendMail($mailData);
                }
            }
        }
        return ['forgotPasswordForm' => $this->_forgotPasswordForm];
    }

    /**
     * Reset Password Action
     *
     * @package User
     * @access Public
     * @return Object ViewModel
     */
    public function resetPasswordAction()
    {
        $userPassword = $this->getServiceLocator()->get('User\Service\UserEncryption');
        // ////////Get the Token From URL and Decrypt it///////////
        $token = $this->params()->fromRoute('token');
        $resetPasswordDecryptedData = $userPassword->decryptUrlParameter($token);
        $resetPasswordData = explode("|", $resetPasswordDecryptedData);

        if (!($this->_session->offsetExists('resetPassword'))) {
            $this->_session->offsetSet('resetPassword', $resetPasswordData[0]);
            $resetPasswordData[1] = (int) $resetPasswordData[1];
            $this->_session->offsetSet('resetUserID', $resetPasswordData[1]);
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $this->_resetPasswordForm->setData($request->getPost());
            if ($this->_resetPasswordForm->isValid()) {
                $postData = $this->_resetPasswordForm->getData();
                $postemdata = $this->_em->find('User\Entity\User', $this->_session->offsetGet('resetUserID'));
                $postemdata->password = md5($postData['password']);
                $this->_em->flush();
                return $this->redirect()->toRoute("user");
            }
        }

        return(['resetPasswordForm' => $this->_resetPasswordForm]);
    }

    /**
     * Function for getting the forgot password template
     *
     * @package User
     * @access Public
     * @return Ambigous <string, \Zend\Filter\mixed, mixed>
     */
    public function getForgotPasswordTemplate($variables = array())
    {
        $view = $this->_phpRenderView;

        $resolver = $this->_resolver;

        $resolver->setMap([
            'mailTemplate' => __DIR__ . '/../../../view/user/user/forgot-mail-template.phtml'
        ]);

        $view->setResolver($resolver);

        $viewModel = $this->_viewModel;

        $viewModel->setTemplate('mailTemplate');

        $viewModel->setVariables($variables);

        $content = $view->render($viewModel);

        return $content;
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
