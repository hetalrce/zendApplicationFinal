<?php

namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use User\Hydrator\Posts;

class PostsController extends AbstractActionController
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
     *  @var Hydrate data from array 
     */
    protected $_hydrate;

    /**
     *  @var Reflect class object for hydrate 
     */
    protected $_object;

    /**
     *  @var Post Form
     */
    protected $_postForm;

    /**
     *  @var Post Form Validation 
     */
    protected $_postFormValidation;

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
    public function __construct($serviceLocator = null, $sessionConfig = null, $hydrator = null, $reflaction_object = null, $PostsForm = null, $postValidation = null)
    {

        if (!is_null($serviceLocator)) {
            $this->_em = $serviceLocator;
        }
        if (!is_null($sessionConfig)) {
            $this->_session = $sessionConfig;
        }
        if (!is_null($hydrator)) {
            $this->_hydrate = $hydrator;
        }
        if (!is_null($reflaction_object)) {
            $this->_object = $reflaction_object;
        }
        if (!is_null($PostsForm)) {
            $this->_postForm = $PostsForm;
        }
        if (!is_null($postValidation)) {
            $this->_postFormValidation = $postValidation;
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
        $this->layout("layout/page_layout");
        $posts = $this->_em->getRepository('Admin\Entity\Posts')->findAll();
        return array('post' => $posts,);
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
        $errorList = [];
        $this->layout("layout/page_layout");
        $request = $this->getRequest();
        if ($request->isPost()) {
            $this->_postForm->setInputFilter($this->_postFormValidation->getInputFilter());
            $this->_postForm->setData($request->getPost());
            if ($this->_postForm->isValid()) {
                $postData = $this->_postForm->getData();
                $data = $this->_hydrate->hydrate(
                        $postData, $this->_object
                );
                $this->_em->persist($data);
                $this->_em->flush();
                return $this->redirect()->toRoute('posts');
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
            $post = $this->_em->getRepository('Admin\Entity\Posts')->findOneBy(
                    array('id' => $id,));
        } catch (\Exception $ex) {
            return $this->redirect()->toRoute('posts', array(
                        'action' => 'index',
            ));
        }
        $this->_postForm->get('submit')->setAttribute('value', 'Update');
        $request = $this->getRequest();
        if ($request->isPost()) {
            $this->_postForm->setInputFilter($this->_postFormValidation->getInputFilter());
            $this->_postForm->setData($request->getPost());
            if ($this->_postForm->isValid()) {
                $postData = $this->_postForm->getData();
                $postemdata = $this->_em->find('Admin\Entity\Posts', $postData['id']);
                $postemdata->title = $postData['title'];
                $postemdata->content = $postData['content'];
                $this->_em->flush();
                return $this->redirect()->toRoute('posts');
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
        $post = $this->_em->find('Admin\Entity\Posts', $id);
        if ($post) {
            $this->_em->remove($post);
            $this->_em->flush();
            return $this->redirect()->toRoute('posts');
        }
    }

}
