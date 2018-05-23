<?php

namespace Admin\Hydrator;

class Posts {

    protected $title;
    protected $content;
    protected $user_id;

    public function __construct($data) {
        $this->title = $data['title'];
        $this->content = $data['content'];
        $this->user_id = $data['user_id'];
    }

}
