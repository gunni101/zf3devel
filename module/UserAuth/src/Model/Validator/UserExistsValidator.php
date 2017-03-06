<?php
namespace UserAuth\Model\Validator;

use Zend\Validator\AbstractValidator;
use UserAuth\Entity\UserEntity;

class UserExistsValidator extends AbstractValidator
{
    /**
     * Available validator options
     * @var array
     */
    protected $options = [
        'entityManager' => null,
        'user' => null,
    ];
    
    // Validation failure message IDs
    const NOT_SCALAR    = 'notScalar';
    const USER_EXISTS   = 'userExists';
    
    /**
     * Validation failure messages
     * @var array
     */
    protected $messageTemplates = [
        self::NOT_SCALAR => 'The email must be a scalar value',
        self::USER_EXISTS => 'Ein Benutzer mit dieser E-Mail Adresse existiert schon.',
    ];
    
    /**
     * Constructor
     */
    public function __construct($options = null)
    {
        if(is_array($options)) {
            if(isset($options['entityManager']))
                $this->options['entityManager'] = $options['entityManager'];
            if(isset($options['user']))
                $this->options['user'] = $options['user'];
        }
        
        parent::__construct($options);
    }
    
    /**
     * Check if user exists.
     */
    public function isValid($value)
    {
        if(!is_scalar($value)) {
            $this->error(self::NOT_SCALAR);
            return false;
        }
        
        $entityManager = $this->options['entityManager'];
        
        $user = $entityManager->getRepository(UserEntity::class)
            ->findOneByEmail($value);
        
            if($this->options['user'] == null ) {
                $isValid = ($user==null);
            } else {
                if($this->options['user']->getEmail() != $value && $user != null)
                    $isValid = false;
                else
                    $isValid = true;
            }
            
            if(!$isValid) {
                $this->error(self::USER_EXISTS);
            }
            
            return $isValid;
    }
}