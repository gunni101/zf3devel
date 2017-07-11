<?php
namespace UserAuth\Model\Service;

use UserAuth\Entity\UserEntity;
use Zend\Crypt\Password\Bcrypt;
use Zend\Math\Rand;

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
		$token = Rand::getString(32, 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890-!$()');
		$user->setPasswordResetToken($token);

		$currentDate = date('Y-m-d H:i:s');
		$user->setPasswordResetTokenCreationDate($currentDate);

		$this->entityManager->flush();

		$httpHost = isset($_SERVER['HTTP_HOST'])?$_SERVER['HTTP_HOST']:'localhost';
		$passwordResetUrl = 'http://' . $httpHost . '/resetpassword?token=' . $token;

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

    public function validatePasswordResetToken($passwordResetToken)
    {
        $user = $this->entityManager->getRepository(UserEntity::class)
            ->findOneByPasswordResetToken($passwordResetToken);

        if($user == null) {
            return false;
        }

        $tokenCreationDate = $user->getPasswordResetTokenCreationDate();
        $tokenCreationDate = strtotime($tokenCreationDate);

        $currentDate = strtotime('now');

        if($currentDate - $tokenCreationDate > 24 * 60 * 60) {
            return false;
        }

        return true;
    }

    public function setNewPasswordByToken($passwordResetToken, $newPassword)
    {
        if (!$this->validatePasswordResetToken($passwordResetToken)) {
            return false;
        }

        $user = $this->entityManager->getRepository(UserEntity::class)
            ->findOneByPasswordResetToken($passwordResetToken);

        if($user == null) {
            return false;
        }

        $bcrypt = new Bcrypt();
        $passwordHash = $bcrypt->create($newPassword);
        $user->setPassword($passwordHash);
        $user->setPwdUpdated(date('Y-m-d H:i:s'));

        $user->setPasswordResetToken(null);
        $user->setPasswordResetTokenCreationDate(null);

        $this->entityManager->flush();

        return true;
    }
}