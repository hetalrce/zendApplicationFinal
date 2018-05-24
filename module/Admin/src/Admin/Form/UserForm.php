<?php

namespace Admin\Form;

use Zend\Form\Form;

class UserForm extends Form
{

    public function __construct($name = null)
    {

        parent::__construct('user');
        // Setting post method for this form
        $this->setAttribute("method", "post");

        // Adding Hidden element to the form for ID
        $this->add([
            "name" => "id",
            "attributes" => [
                "type" => "hidden",
            ],
        ]);

        $this->add([
            'name' => 'first_name',
            'attributes' => [
                'id' => 'first_name',
                'type' => 'text',
                'class' => 'input-txt form-control',
            ],
            'options' => [
                'label' => 'First Name',
            ],
        ]);

        $this->add([
            'name' => 'last_name',
            'attributes' => [
                'id' => 'last_name',
                'type' => 'text',
                'class' => 'input-txt form-control',
            ],
            'options' => [
                'label' => 'Last Name',
            ],
        ]);

        $this->add([
            'name' => 'email',
            'attributes' => [
                'id' => 'email',
                'type' => 'text',
                'class' => 'input-txt form-control',
            ],
            'options' => [
                'label' => 'Email',
            ],
        ]);
        $this->add([
            'name' => 'password',
            'attributes' => [
                'id' => 'password',
                'type' => 'password',
                'class' => 'input-txt form-control',
            ],
            'options' => [
                'label' => 'Password',
            ],
        ]);
        $this->add([
            'name' => 'confirmpassword',
            'attributes' => [
                'id' => 'confirmpassword',
                'type' => 'password',
                'class' => 'input-txt form-control',
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
                'class' => 'btn btn-default',
            ],
        ]);
    }

}
