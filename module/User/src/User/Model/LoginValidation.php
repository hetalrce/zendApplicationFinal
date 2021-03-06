<?php

/**
 * LoginValidation class
 *
 * Used to add validator on login form
 */

namespace User\Model;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

/**
 * LoginValidation is used to add validator on login form
 *
 * @category Login
 * @package Model
 * 
 */
class LoginValidation implements InputFilterAwareInterface
{

    /**
     *
     * @var object Zend\InputFilter\InputFilterAwareInterface
     */
    protected $_inputFilter;

    /**
     * set interface for input filter
     *
     * @param InputFilterInterface $inputFilter
     *            object of InputFilterInterface
     *            
     * @throws \Exception
     * @return void
     */
    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used $inputFilter");
    }

    /**
     * Function to add validation on
     * Add filter form
     *
     * @return object Zend\InputFilter\InputFilterAwareInterface
     */
    public function getInputFilter()
    {
        if (!$this->_inputFilter) {
            $inputFilter = new InputFilter();

            $factory = new InputFactory();
            $isEmpty = \Zend\Validator\NotEmpty::IS_EMPTY;
            $minLength = \Zend\Validator\StringLength::TOO_SHORT;
            $maxLength = \Zend\Validator\StringLength::TOO_LONG;
            $invalidEmail = \Zend\Validator\EmailAddress::INVALID_FORMAT;
            $regexNotMatched = \Zend\Validator\Regex::NOT_MATCH;



            $inputFilter->add($factory->createInput([
                        'name' => 'email',
                        'required' => true,
                        'validators' => [
                            [
                                'name' => 'NotEmpty',
                                'options' => [
                                    'messages' => [
                                        $isEmpty => 'Email can not be empty.',
                                    ],
                                ],
                                'break_chain_on_failure' => true
                            ],
                            [
                                'name' => 'EmailAddress',
                                'options' => [
                                    'messages' => [
                                        $invalidEmail => 'Enter Valid Email Address.',
                                    ],
                                ],
                                'break_chain_on_failure' => true
                            ],
                        ],
                        'filters' => [
                            [
                                'name' => 'StripTags',
                            ],
                            [
                                'name' => 'StringTrim',
                            ],
                        ],
            ]));

            $inputFilter->add($factory->createInput([
                        'name' => 'password',
                        'required' => true,
                        'validators' => [
                            [
                                'name' => 'NotEmpty',
                                'options' => [
                                    'messages' => [
                                        $isEmpty => 'Password can not be empty.',
                                    ],
                                ],
                                'break_chain_on_failure' => true,
                            ],
                        ],
                        'filters' => [
                            [
                                'name' => 'StripTags',
                            ],
                            [
                                'name' => 'StringTrim',
                            ],
                        ],
            ]));


            $this->_inputFilter = $inputFilter;
        }

        return $this->_inputFilter;
    }

}
