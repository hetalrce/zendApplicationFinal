<?php

namespace User\Form;

use Zend\Form\Form;

class ResetPasswordForm extends Form
{

    public function __construct($name = null)
    {
        parent::__construct('login');

        // Setting post method for this form
        $this->setAttribute("method", "post");

        // Adding Hidden element to the form for ID
        $this->add([
            'name' => 'password',
            'attributes' => [
                'id' => 'password',
                'type' => 'password',
                'class' => 'input-txt',
            ],
            'options' => [
                'label' => 'New Password',
            ],
        ]);
        $this->add([
            'name' => 'confirmpassword',
            'attributes' => [
                'id' => 'confirmpassword',
                'type' => 'password',
                'class' => 'input-txt',
            ],
            'options' => [
                'label' => 'Confirm Password',
            ],
        ]);

        $this->add([
            'name' => 'submit',
            'attributes' => [
                'type' => 'submit',
                'value' => 'Submit',
                'id' => 'submitbutton',
                'class' => 'btn',
            ]
        ]);

        $this->add([
            'type' => 'Zend\Form\Element\Csrf',
            'name' => 'loginCsrf',
            'options' => [
                'csrf_options' => [
                    'timeout' => 3600
                ],
            ],
        ]);
    }

}
