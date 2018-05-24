<?php

namespace User\Form;

use Zend\Form\Form;

class LoginForm extends Form
{

    public function __construct($name = null)
    {
        parent::__construct('login');
        // Setting post method for this form
        $this->setAttribute("method", "post");
        $this->add([
            'name' => 'email',
            'attributes' => [
                'id' => 'email',
                'type' => 'text',
                'class' => 'input-txt',
            ],
            'options' => [
                'label' => 'Email Id',
            ],
        ]);

        $this->add([
            'name' => 'password',
            'attributes' => [
                'id' => 'password',
                'type' => 'password',
                'class' => 'input-txt',
            ],
            'options' => [
                'label' => 'Password',
            ],
        ]);

        $this->add([
            'name' => 'rememberMe',
            'attributes' => [
                'id' => 'rememberMe',
                'value' => 1,
                'type' => 'Checkbox',
            ],
            'options' => [
                'label' => 'Remember me on this computer',
            ],
        ]);


        $this->add([
            'name' => 'submit',
            'attributes' => [
                'type' => 'submit',
                'value' => 'Sign in',
                'id' => 'submitbutton',
                'class' => 'btn',
            ],
        ]);

        $this->add([
            'type' => 'Zend\Form\Element\Csrf',
            'name' => 'loginCsrf',
            'options' => [
                'csrf_options' => [
                    'timeout' => 3600,
                ],
            ],
        ]);
    }

}
