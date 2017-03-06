<?php
namespace UserAuth\Model\Service;

use Zend\Authentication\Adapter\AdapterInterface;
use UserAuth\Entity\UserEntity;
use Zend\Authentication\Result;
use Zend\Crypt\Password\Bcrypt;

class AuthAdapter implements AdapterInterface
{
    private $email;
    
    private $password;
 
    private $entityManager;
    
    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    public function setEmail($email)
    {
        $this->email =$email;
    }
    
    public function setPassword($password)
    {
        $this->password = $password;
    }
    
    public function authenticate()
    {
        $user = $this->entityManager->getRepository(UserEntity::class)
            ->findOneByEmail($this->email);
        
        if ($user == null) {
            return new Result(
                RESULT::FAILURE_IDENTITY_NOT_FOUND,
                null,
                ['Invalid credentials']);
        }
        
        if($user->getStatus() == UserEntity::STATUS_NEW) {
            return new Result(
                Result::FAILURE_UNCATEGORIZED,
                null,
                ['Benutzer noch nicht freigegeben.']);            
        }
        
        if($user->getStatus() == UserEntity::STATUS_BLOCKED) {
            return new Result(
                Result::FAILURE,
                null,
                ['Benutzer ist gesperrt.']);
        }
        
        $bcrypt = new Bcrypt();
        $passwordHash = $user->getPassword();
        
        if($bcrypt->verify($this->password, $passwordHash)) {
            return new Result(
                Result::SUCCESS,
                $user,   
                ['Erfolgreich eingeloggt']);
        }
        
        return new Result(
            Result::FAILURE_CREDENTIAL_INVALID,
            null,
            ['Falsche Zugangsdaten']);
    }
}