<?php

namespace User\Form;

use Zend\Form\Form;

class PostForm extends Form {

    public function __construct($name = null) {

        parent::__construct('login');
        // Setting post method for this form
        $this->setAttribute("method", "post");

        // Adding Hidden element to the form for ID
        $this->add(array(
            "name" => "id",
            "attributes" => array(
                "type" => "hidden"
            )
        ));
        $this->add(array(
            'name' => 'title',
            'attributes' => array(
                'id' => 'title',
                'type' => 'text',
                'class' => 'input-txt'
            ),
            'options' => array(
                'label' => 'Title'
            )
        ));

        $this->add(array(
            'name' => 'content',
            'attributes' => array(
                'id' => 'content',
                'type' => 'textarea',
                'class' => 'input-txt',
                'rows' => 4
            ),
            'options' => array(
                'label' => 'Content'
            )
        ));

        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type' => 'submit',
                'value' => 'Submit',
                'id' => 'submitbutton',
                'class' => 'btn'
            )
        ));
    }

}
