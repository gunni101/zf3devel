<?php
namespace UserAuth\Frontend\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;

class ForgetPasswordForm extends Form
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct('forget-password-form');
        
        $this->setAttribute('method', 'post');
        
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
        
        $this->add([
            'type' => 'captcha',
            'name' => 'captcha',
            'options' => [
                'label' => 'Ich bin kein Roboter!',
                'captcha' => [
                    'class' => 'Image',
                    'imgDir' => 'public/img/captcha',
                    'suffix' => '.png',
                    'imgUrl' => '/img/captcha/',
                    'imgAlt' => 'CAPTCHA Image',
                    'font' => './data/font/ThorneShaded.ttf',
                    'fsize' => 18,
                    'width' => 200,
                    'height' => 100,
                    'expiration' => 600,
                    'lineNoiseLevel' => 4,
                	'dotNoiseLevel' => 4,
                    'wordLen' => 4
                ],
            ],
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
            ],
        ]);
    }

}
