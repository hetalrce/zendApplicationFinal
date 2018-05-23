<?php

namespace Admin\Entity;

use Doctrine\ORM\Mapping as ORM;
/**
 * A music Post.
 *
 * @ORM\Entity
 * @ORM\Table(name="posts")
 * @property string $title
 * @property text $content
 * @property int $user_id
 * @property date $created_on
 * @property int $id
 */
class Posts {

    protected $inputFilter;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer");
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     */
    protected $title;

    /**
     * @ORM\Column(type="text")
     */
    protected $content;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $created_on;

    /**
     * @ORM\Column(type="integer")
     */
    protected $user_id;

    /**
     * Magic getter to expose protected properties.
     *
     * @param string $property
     * @return mixed
     */
    public function __get($property) {
        return $this->$property;
    }

    /**
     * Magic setter to save protected properties.
     *
     * @param string $property
     * @param mixed $value
     */
    public function __set($property, $value) {
        $this->$property = $value;
    }

   
}
