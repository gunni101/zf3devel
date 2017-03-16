<?php
namespace UserAuth\Model\Service;

use UserAuth\Entity\UserEntity;
use Zend\Crypt\Password\Bcrypt;
use Zend\Math\Rand;
use Zend\Http\Header\UserAgent;

class UserManager
{
	private $entityManager;
	
	private $mailManager;
	
	public function __construct($entityManager, $mailManager)
	{
		$this->entityManager = $entityManager;
		$this->mailManager = $mailManager;
	}
	
	public function addUser($data)
	{
	    if($this->checkUserExists($data['email'])) {
	        throw new \Exception('Ein Benutzer mit der E-Mail Adresse ' . $data['email'] . 'existiert bereits.');
	    }
	    
	    $user = new UserEntity();
	    $user->setEmail($data['email']);
	    $user->setUserName($data['username']);
	    
	    $bcrypt = new Bcrypt();
	    $passwordHash = $bcrypt->create($data['password']);
	    $user->setPassword($passwordHash);
	    
	    $user->setStatus(1);
	    
	    $currentDate = date('Y-m-d H:i:s');
	    $user->setUserCreated($currentDate);
	    $user->setPwdCreated($currentDate);
        
	    $this->entityManager->persist($user);
	    
	    $this->entityManager->flush();
	    
	    return $user;
	}
	
	public function getEmail()
	{
    
	}
	
	public function checkUserExists($email)
	{
	    $user = $this->entityManager->getRepository(UserEntity::class)
	       ->findOneByEmail($email);
	    
	    return $user !== null;
	}
	
	public function generatePasswordResetToken($user)
	{
		$token = Rand::getString(32);
		$user->setPasswordResetToken($token);
		
		$currentDate = date('Y-m-d H:i:s');
		$user->setPasswordResetTokenCreationDate($currentDate);
		
		$this->entityManager->flush();

		$httpHost = isset($_SERVER['HTTP_HOST'])?$_SERVER['HTTP_HOST']:'localhost';
		$passwordResetUrl = 'http://' . $httpHost . '/set-password?token=' . $token;

		$mailData = [
			'to' => [
				$user->getEmail() => $user->getUserName(),
			],
			'subject' => 'Passwortänderung'
		];
		
		$template = [
			'reset2user' => __DIR__ .
			'/../../../view/userauth/mails/reset2user.phtml'
		];
		 
		$this->mailManager->gsMail($mailData, $template, $passwordResetUrl);

	}
}