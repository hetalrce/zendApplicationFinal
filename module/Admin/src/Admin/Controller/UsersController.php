<?php

namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class UsersController extends AbstractActionController
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
     *  @var User Form
     */
    protected $_userForm;

    /**
     *  @var User Form Validation
     */
    protected $_userFormValidation;

    /**
     *  @var Hydrator class
     */
    protected $_hydrate;

    /**
     *  @var Reflect object
     */
    protected $_object;

    /**
     * __Construct inject UserController Factory
     *
     * @access Public
     * @param $serviceLocator DoctrineORMEntityManager
     * @param $sessionConfig Session Data Class
     * @return Object
     */
    public function __construct($serviceLocator = null, $sessionConfig = null, $userForm = null, $userFormValidation = null, $hydrator = null, $reflaction_object = null)
    {
        if (!is_null($serviceLocator)) {
            $this->_em = $serviceLocator;
        }
        if (!is_null($sessionConfig)) {
            $this->_session = $sessionConfig;
        }
        if (!is_null($userForm)) {
            $this->_userForm = $userForm;
        }
        if (!is_null($userFormValidation)) {
            $this->_userFormValidation = $userFormValidation;
        }
        if (!is_null($hydrator)) {
            $this->_hydrate = $hydrator;
        }
        if (!is_null($reflaction_object)) {
            $this->_object = $reflaction_object;
        }
    }

    /**
     * add User Action
     *
     * @package Post
     * @access Public
     * @return Object ViewModel
     */
    public function addAction()
    {
        $this->layout("layout/page_layout");
        $request = $this->getRequest();
        $errorList = [];
        if ($request->isPost()) {
            $this->_userForm->setInputFilter($this->_userFormValidation->getInputFilter());
            $this->_userForm->setData($request->getPost());
            if ($this->_userForm->isValid()) {
                $postData = $this->_userForm->getData();
                $postData['role'] = 2;
                $postData['password'] = md5($postData['password']);
                $data = $this->_hydrate->hydrate(
                        $postData, $this->_object
                );
                $this->_em->persist($data);
                $this->_em->flush();
                return $this->redirect()->toRoute('users');
            } else {
                $errorList = $this->_userForm->getMessages();
            }
        }
        return array('userForm' => $this->_userForm, 'errorList' => $errorList,);
    }

    /**
     * update post Action
     *
     * @package Post
     * @access Public
     * @return Object ViewModel
     */
    public function updateAction()
    {
        $this->layout("layout/page_layout");

        $id = (int) $this->params()->fromRoute('id', 0);

        try {
            $user = $this->_em->getRepository('Admin\Entity\Users')->findOneBy(
                    array('id' => $id));
        } catch (\Exception $ex) {
            return $this->redirect()->toRoute('users', array(
                        'action' => 'index',
            ));
        }

        $this->_userForm->get('submit')->setAttribute('value', 'Update');

        $request = $this->getRequest();
        if ($request->isPost()) {

            $this->_userForm->setInputFilter($this->_userFormValidation->getInputFilter());

            $this->_userForm->setData($request->getPost());

            if ($this->_userForm->isValid()) {
                $postData = $this->_userForm->getData();
                $postemdata = $this->_em->find('Admin\Entity\Users', $postData['id']);
                $postemdata->first_name = $postData['first_name'];
                $postemdata->last_name = $postData['last_name'];
                $postemdata->email = $postData['email'];
                $this->_em->flush();
                return $this->redirect()->toRoute('users');
            } else {
                $errorList = $this->_userForm->getMessages();
                $this->flashMessenger()->addMessage($errorList);
            }
        }

        return array('userForm' => $this->_userForm, 'user' => $user, 'id' => $id,);
    }

    /**
     * Users Action
     *
     * @package Admin Users
     * @access Public
     * @return Object ViewModel
     */
    public function indexAction()
    {
        $this->layout("layout/page_layout");
        $users = $this->_em->getRepository('Admin\Entity\Users')->findBy(array('role' => 2));
        return array('users' => $users);
    }

    /**
     * Delete user Action
     *
     * @package Post
     * @access Public
     * @return Redirect index action
     */
    public function deleteAction()
    {
        $id = (int) $this->getEvent()->getRouteMatch()->getParam('id');
        $user = $this->_em->find('Admin\Entity\Users', $id);
        if ($user) {
            $this->_em->remove($user);
            $this->_em->flush();
            return $this->redirect()->toRoute('users');
        }
    }

}
