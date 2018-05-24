<?php

namespace User\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use User\Hydrator\Posts;

class PostController extends AbstractActionController
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
     *  @var Post Form data
     */
    protected $_postForm;

    /**
     *  @var Post Form Validation 
     */
    protected $_postFormValidation;

    /**
     *  @var Hydrate data from array 
     */
    protected $_hydrate;

    /**
     *  @var Reflect class object for hydrate 
     */
    protected $_object;

    /**
     * __Construct inject PostController Factory
     *
     * @access Public
     * @param $serviceLocator DoctrineORMEntityManager
     * @param $sessionConfig Session Data Class
     * @param $postFormConfig Post Form Class
     * @param $postFormValidationConfig Post Form Validation Class
     * @return Object
     */
    public function __construct($serviceLocator = null, $sessionConfig = null, $postFormConfig = null, $postFormValidationConfig = null, $hydrator = null, $reflaction_object = null)
    {

        if (!is_null($serviceLocator)) {
            $this->_em = $serviceLocator;
        }
        if (!is_null($sessionConfig)) {
            $this->_session = $sessionConfig;
        }
        if (!is_null($postFormConfig)) {
            $this->_postForm = $postFormConfig;
        }
        if (!is_null($postFormValidationConfig)) {
            $this->_postFormValidation = $postFormValidationConfig;
        }
        if (!is_null($hydrator)) {
            $this->_hydrate = $hydrator;
        }
        if (!is_null($reflaction_object)) {
            $this->_object = $reflaction_object;
        }
    }

    /**
     * index post Action
     *
     * @package Post
     * @access Public
     * @return Object ViewModel
     */
    public function indexAction()
    {
        $this->layout("layout/header");
        $userId = $this->_session->offsetGet('userId');
        $post = $this->_em->getRepository('User\Entity\Post')->findBy(
                array('user_id' => $userId,));
        return array('post' => $post,);
    }

    /**
     * add post Action
     *
     * @package Post
     * @access Public
     * @return Object ViewModel
     */
    public function addAction()
    {
        $request = $this->getRequest();
        $userId = $this->_session->offsetGet('userId');
        $errorList = [];
        if ($request->isPost()) {
            $this->_postForm->setInputFilter($this->_postFormValidation->getInputFilter());
            $this->_postForm->setData($request->getPost());
            if ($this->_postForm->isValid()) {
                $postData = $this->_postForm->getData();
                $postData['user_id'] = $userId;
                $data = $this->_hydrate->hydrate(
                        $postData, $this->_object
                );
                $this->_em->persist($data);
                $this->_em->flush();
                return $this->redirect()->toRoute('post');
            } else {
                $errorList = $this->_postForm->getMessages();
            }
        }
        return array('postForm' => $this->_postForm, 'errorList' => $errorList,);
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
        $errorList = [];
        $id = (int) $this->params()->fromRoute('id', 0);
        try {
            $post = $this->_em->getRepository('User\Entity\Post')->findOneBy(
                    array('id' => $id));
        } catch (\Exception $ex) {
            return $this->redirect()->toRoute('post', array(
                        'action' => 'index',
            ));
        }
        $this->_postForm->get('submit')->setAttribute('value', 'Update');
        $request = $this->getRequest();
        if ($request->isPost()) {
            $userId = $this->_session->offsetGet('userId');
            $this->_postForm->setInputFilter($this->_postFormValidation->getInputFilter());
            $this->_postForm->setData($request->getPost());
            if ($this->_postForm->isValid()) {
                $postData = $this->_postForm->getData();
                $postemdata = $this->_em->find('User\Entity\Post', $postData['id']);
                $postemdata->title = $postData['title'];
                $postemdata->content = $postData['content'];
                $this->_em->flush();
                return $this->redirect()->toRoute('post');
            } else {
                $errorList = $this->_postForm->getMessages();
            }
        }
        return array('postForm' => $this->_postForm, 'post' => $post, 'id' => $id, 'errorList' => $errorList,);
    }

    /**
     * delete post Action
     *
     * @package Post
     * @access Public
     * @return Redirect index action
     */
    public function deleteAction()
    {
        $id = (int) $this->getEvent()->getRouteMatch()->getParam('id');
        $post = $this->_em->find('User\Entity\Post', $id);
        if ($post) {
            $this->_em->remove($post);
            $this->_em->flush();
            return $this->redirect()->toRoute('post');
        }
    }

}
