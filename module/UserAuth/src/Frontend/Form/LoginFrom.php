<?php
namespace UserAuth\Frontend\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\Form\Fieldset;

class LoginForm extends Form
{
    public function __construct()
    {
        parent::__construct('login-form');
        
        $this->setAttribute('method', 'post');
        $this->addElements();
        $this->addInputFilters();
    }
    
    public function addElements()
    {
        $this->add([
            'type' => 'text',
            'name' => 'email',
            'options' => [
                'label' => 'E-Mail'
            ],
        ]);
        
        $this->add([
            'type' => 'password',
            'name' => 'password',
            'options' => [
                'label' => 'Passwort'
            ],
        ]);
        
        $this->add([
            'type' => 'checkbox',
            'name' => 'remember_me',
            'options' => [
                'label' => 'Eingeloggt bleigen',
            ],
        ]);
        
        $this->add([
            'type' => 'hidden',
            'name' => 'redirect_url',
        ]);
        
        $this->add([
            'type' => 'csrf',
            'name' => 'csrf',
            'options' => [
                'csrf_options' => [
                    'timeout' => 600,
                ],
            ],
        ]);
        
        $this->add([
            'type' => 'submit',
            'name' => 'submit',
            'attributes' => [
                'value' => 'Anmelden',
                'id' => 'submit',
            ],
        ]);
    }
    
    public function addInputFilters()
    {
        // Create main input filter
        $inputFilter = new InputFilter();
        $this->setInputFilter($inputFilter);
        
        // Add input for "email" field
        $inputFilter->add([
            'name'     => 'email',
            'required' => true,
            'filters'  => [
                ['name' => 'StringTrim'],
            ],
            'validators' => [
                [
                    'name' => 'EmailAddress',
                    'options' => [
                        'allow' => \Zend\Validator\Hostname::ALLOW_DNS,
                        'useMxCheck' => false,
                    ],
                ],
            ],
        ]);
        
        // Add input for "password" field
        $inputFilter->add([
            'name'     => 'password',
            'required' => true,
            'filters'  => [
            ],
            'validators' => [
                [
                    'name'    => 'StringLength',
                    'options' => [
                        'min' => 8,
                        'max' => 64
                    ],
                ],
            ],
        ]);
        
        // Add input for "remember_me" field
        $inputFilter->add([
            'name'     => 'remember_me',
            'required' => false,
            'filters'  => [
            ],
            'validators' => [
                [
                    'name'    => 'InArray',
                    'options' => [
                        'haystack' => [0, 1],
                    ]
                ],
            ],
        ]);
        
        // Add input for "redirect_url" field
        $inputFilter->add([
            'name'     => 'redirect_url',
            'required' => false,
            'filters'  => [
                ['name'=>'StringTrim']
            ],
            'validators' => [
                [
                    'name'    => 'StringLength',
                    'options' => [
                        'min' => 0,
                        'max' => 2048
                    ]
                ],
            ],
        ]);
    }
}
