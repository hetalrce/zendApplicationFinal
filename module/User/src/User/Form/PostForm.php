<?php

namespace User\Form;

use Zend\Form\Form;

class PostForm extends Form
{

    public function __construct($name = null)
    {

        parent::__construct('login');
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
            'name' => 'title',
            'attributes' => [
                'id' => 'title',
                'type' => 'text',
                'class' => 'input-txt',
            ],
            'options' => [
                'label' => 'Title',
            ],
        ]);

        $this->add([
            'name' => 'content',
            'attributes' => [
                'id' => 'content',
                'type' => 'textarea',
                'class' => 'input-txt',
                'rows' => 4,
            ],
            'options' => [
                'label' => 'Content',
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
    }

}
