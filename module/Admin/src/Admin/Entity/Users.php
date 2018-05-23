<?php

namespace Admin\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * A music User.
 *
 * @ORM\Entity
 * @ORM\Table(name="users")
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property string $password
 * @property int $role
 * @property int $id
 */
class Users {

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
    protected $first_name;

    /**
     * @ORM\Column(type="string")
     */
    protected $last_name;

    /**
     * @ORM\Column(type="string")
     */
    protected $email;

    /**
     * @ORM\Column(type="string")
     */
    protected $password;

    /**
     * @ORM\Column(type="integer")
     */
    protected $role;

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
