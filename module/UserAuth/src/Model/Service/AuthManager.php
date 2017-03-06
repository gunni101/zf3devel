<?php
namespace UserAuth\Model\Service;

use Zend\Authentication\Result;

class AuthManager
{
	private $authService;
	
	private $sessionManager;
	
	private $config;
	
	public function __construct($authService, $sessionManager, $config)
	{
		$this->authService = $authService;
		$this->sessionManager = $sessionManager;
		$this->config = $config;
	}
	
	public function login($email, $password, $rememberMe)
	{
		
	    if($this->authService->getIdentity() !=null) {
			throw new \Exception('Bereits eingeloggt.');
		}
		
		$authAdapter = $this->authService->getAdapter();
		$authAdapter->setEmail($email);
		$authAdapter->setPassword($password);
		$result = $this->authService->authenticate();
		
		if ($result->getCode() == Result::SUCCESS && $rememberMe) {
			$this->sessionManager->rememberMe(60*60*24*30);
		}
		
		return $result;
	}
	
	public function logout()
	{
		if ($this->authService->getIdentity() == null) {
			throw new \Exception('Benutzer nicht eingeloggt.');
		}
		
		$this->authService->clearIdentity();
	}
}