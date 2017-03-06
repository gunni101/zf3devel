<?php
namespace UserAuth\Frontend\Form;

use Zend\Form\Form;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilter;
use UserAuth\Model\Validator\UserExistsValidator;


/**
 * This form is used to register a new user. This form collect user's email, username and password.
 * The default status for a newly created user is "const STATUS_NEW".
 */

class RegisterForm extends Form
{
    /**
     * Entity Manager
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager = null;
    
    private $user;
    
    /**
     * Constructor
     */
    public function __construct($entityManager = null, $user = null)
    {
        parent::__construct('register-form');
        
        // Set POST method for this form.
        $this->setAttribute('method', 'post');
        
        // Save parameters for internal use.
        $this->entityManager = $entityManager;
        $this->user = $user;
        
        $this->addElements();
        $this->addInputFilter();
    }
    
    public function addElements()
    {
        
        // Add email
        $this->add([
            'type' => 'text',
            'name' => 'email',
            'options' => [
                'label' => 'E-mail',
            ],
        ]);
        
        // Add username
        $this->add([
            'type' => 'text',
            'name' => 'username',
            'options' => [
                'label' => 'Anzeigename',
            ],
        ]);
        
        // Add password
        $this->add([
            'type' => 'password',
            'name' => 'password',
            'options' => [
                'label' => 'Passwort',
            ],
        ]);
        
        // Add confirm password
        $this->add([
            'type' => 'password',
            'name' => 'confirm_password',
            'options' => [
                'label' => 'Passwort wiederholen',
            ],
        ]);
        
        // Add submit button
        $this->add([
            'type' => 'submit',
            'name' => 'submit',
            'attributes' => [
                'value' => 'Registrieren',
            ],
        ]);
    }
    
    public function addInputFilter()
    {
        // Create main input filter
        $inputFilter = new InputFilter();
        $this->setInputFilter($inputFilter);
        
        // Add filter for email
        $inputFilter->add([
            'name' => 'email',
            'required' => true,
            'filters' => [
                ['name' => 'StringTrim'],
            ],
            'validators' => [
                [
                    'name' => 'StringLength',
                    'options' => [
                        'min' => 6,
                        'max' => 128,
                    ],
                ],
                [
                    'name' => 'EmailAddress',
                    'options' => [
                        'allow' => \Zend\Validator\Hostname::ALLOW_DNS,
                        'useMxCheck' => false,
                    ],
                ],
                [
                   'name' => UserExistsValidator::class,
                   'options' => [
                       'entityManager' => $this->entityManager,
                       'user' => $this->user,
                   ],
               ],
            ],
        ]);
        
        
        // Add filter for username
        $inputFilter->add([
            'name' => 'username',
            'required' => true,
            'filters' => [
                ['name' => 'StringTrim'],
            ],
            'validators' => [
                [
                    'name' => 'StringLength',
                    'options' => [
                        'min' => 1,
                        'max' => 512,
                    ],
                ],
            ],
        ]);
        
        // Add filter for password
        $inputFilter->add([
            'name' => 'password',
            'required' => true,
            'filters' => [
                
            ],
            'validators' => [
                [
                    'name' => 'StringLength',
                    'options' => [
                        'min' => 6,
                        'max' => 64,
                    ],
                ],
            ],
        ]);
        
        // Add filter for confirm_password
        $inputFilter->add([
            'name' => 'confirm_password',
            'required' => true,
            'filters' => [
                
            ],
            'validators' => [
                [
                    'name' => 'Identical',
                    'options' => [
                        'token' => 'password',
                    ],
                ],
            ],
        ]);
    }
}