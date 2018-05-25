<?php

namespace User\Form;

use Zend\Form\Form;

class RegisterForm extends Form
{

    public function __construct($name = null)
    {
        parent::__construct('register');

        // Setting post method for this form
        $this->setAttribute("method", "post");

        // Adding Hidden element to the form for ID
        $this->add([
            "name" => "id",
            "attributes" => [
                "type" => "hidden",
            ]
        ]);

        $this->add([
            "name" => "first_name",
            "attributes" => [
                "type" => "text",
            ],
            "options" => [
                "label" => "First Name",
            ],
        ]);

        $this->add([
            "name" => "last_name",
            "attributes" => [
                "type" => "text",
            ],
            "options" => [
                "label" => "Last Name",
            ],
        ]);

        // Adding a text element to the form for Marks
        $this->add([
            "name" => "email",
            "attributes" => [
                "type" => "text",
            ],
            "options" => [
                "label" => "Email Id",
            ],
        ]);


        $this->add([
            "name" => "password",
            "attributes" => [
                "type" => "password",
            ],
            "options" => [
                "label" => "Password",
            ],
        ]);
        $this->add([
            "name" => "confirmpassword",
            "attributes" => [
                "type" => "password",
            ],
            "options" => [
                "label" => "Confirm Password",
            ],
        ]);
        // Adding Submit button to the form 
        $this->add([
            "name" => "submit",
            "attributes" => [
                "type=" => "submit",
                "value" => "Add",
            ],
        ]);
    }

}
