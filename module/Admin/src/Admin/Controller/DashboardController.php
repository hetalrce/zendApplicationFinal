<?php

namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class DashboardController extends AbstractActionController {

    /**
     *  @var DoctrineORMEntityManager
     */
    protected $_em;

    /**
     *  @var session data
     */
    protected $_session;

    /**
     * __Construct inject UserController Factory
     *
     * @access Public
     * @param $serviceLocator DoctrineORMEntityManager
     * @param $sessionConfig Session Data Class
     * @return Object
     */
    public function __construct($serviceLocator = null, $sessionConfig = null) 
    {
        if (!is_null($serviceLocator)) {
            $this->_em = $serviceLocator;
        }
        if (!is_null($sessionConfig)) {
            $this->_session = $sessionConfig;
        }
    }

    /**
     * Admin Dashboard Action
     *
     * @package Admin 
     * @access Public
     * @return Object ViewModel
     */
    public function indexAction() {
        $this->layout("layout/page_layout");
        $userDataCount = $this->_em->getRepository('Admin\Entity\Users')->findBy(array('role' => 2));
        $postDataCount = $this->_em->getRepository('Admin\Entity\Posts')->findAll();

        return array('usersCount' => count($userDataCount), 'postsCount' => count($postDataCount));
    }

    /**
     * Logout Action
     *
     * @package Admin 
     * @access Public
     * @return redirect to login action
     */
    public function logoutAction() {
        $adminId = $this->_session->offsetGet('adminId');
        $sessionId = session_id();
        $this->_session->getManager()->destroy();
        $this->_session->getManager()->forgetMe();
        $uri = $this->getRequest()->getUri();
        $baseUrl = sprintf('%s://%s', $uri->getScheme(), $uri->getHost());
        return $this->redirect()->toUrl($baseUrl);
    }

}
