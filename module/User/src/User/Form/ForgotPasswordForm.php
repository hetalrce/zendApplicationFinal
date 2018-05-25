<?php

namespace User\Form;

use Zend\Form\Form;

class ForgotPasswordForm extends Form
{

    public function __construct($name = null)
    {
        parent::__construct('login');

        // Setting post method for this form
        $this->setAttribute("method", "post");

        // Adding Hidden element to the form for ID
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
            'name' => 'submit',
            'attributes' => [
                'type' => 'submit',
                'value' => 'Submit',
                'id' => 'submitbutton',
                'class' => 'btn',
            ],
        ]);

        $this->add([
            'type' => 'Zend\Form\Element\Csrf',
            'name' => 'loginCsrf',
            'options' => [
                'csrf_options' => [
                    'timeout' => 3600
                ]
            ],
        ]);
    }

}
