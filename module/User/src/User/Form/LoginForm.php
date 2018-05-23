<?php

namespace User\Form;

use Zend\Form\Form;

class LoginForm extends Form {

    public function __construct($name = null) {
        parent::__construct('login');
        // Setting post method for this form
        $this->setAttribute("method", "post");
        $this->add(array(
            'name' => 'email',
            'attributes' => array(
                'id' => 'email',
                'type' => 'text',
                'class' => 'input-txt'
            ),
            'options' => array(
                'label' => 'Email Id'
            )
        ));

        $this->add(array(
            'name' => 'password',
            'attributes' => array(
                'id' => 'password',
                'type' => 'password',
                'class' => 'input-txt'
            ),
            'options' => array(
                'label' => 'Password'
            )
        ));

        $this->add(array(
            'name' => 'rememberMe',
            'attributes' => array(
                'id' => 'rememberMe',
                'value' => 1,
                'type' => 'Checkbox'
            ),
            'options' => array(
                'label' => 'Remember me on this computer'
            )
        ));


        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type' => 'submit',
                'value' => 'Sign in',
                'id' => 'submitbutton',
                'class' => 'btn'
            )
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Csrf',
            'name' => 'loginCsrf',
            'options' => array(
                'csrf_options' => array(
                    'timeout' => 3600
                )
            )
        ));
    }

}
