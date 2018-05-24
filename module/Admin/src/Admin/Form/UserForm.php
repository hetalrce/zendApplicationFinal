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
        $this->add(array(
            "name" => "id",
            "attributes" => array(
                "type" => "hidden",
            ),
        ));

        $this->add(array(
            'name' => 'first_name',
            'attributes' => array(
                'id' => 'first_name',
                'type' => 'text',
                'class' => 'input-txt form-control',
            ),
            'options' => array(
                'label' => 'First Name',
            ),
        ));

        $this->add(array(
            'name' => 'last_name',
            'attributes' => array(
                'id' => 'last_name',
                'type' => 'text',
                'class' => 'input-txt form-control',
            ),
            'options' => array(
                'label' => 'Last Name',
            ),
        ));

        $this->add(array(
            'name' => 'email',
            'attributes' => array(
                'id' => 'email',
                'type' => 'text',
                'class' => 'input-txt form-control',
            ),
            'options' => array(
                'label' => 'Email',
            ),
        ));
        $this->add(array(
            'name' => 'password',
            'attributes' => array(
                'id' => 'password',
                'type' => 'password',
                'class' => 'input-txt form-control',
            ),
            'options' => array(
                'label' => 'Password',
            ),
        ));
        $this->add(array(
            'name' => 'confirmpassword',
            'attributes' => array(
                'id' => 'confirmpassword',
                'type' => 'password',
                'class' => 'input-txt form-control',
            ),
            'options' => array(
                'label' => 'Confirm Password',
            ),
        ));

        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type' => 'submit',
                'value' => 'Submit',
                'id' => 'submitbutton',
                'class' => 'btn btn-default',
            ),
        ));
    }

}
