<?php
namespace UserAuth\Frontend\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use UserAuth\Frontend\Form\LoginForm;
use Zend\Authentication\Result;
use Zend\View\Model\ViewModel;
use Zend\Uri\Uri;

class AuthController extends AbstractActionController
{
    /**
     * EntityManager
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;
    
	/**
	 * AuthManager
	 * @var \UserAuth\Model\Service\AuthManager
	 */
    private $authManager;
    
    /**
     * AuthService
     * @var \Zend\Authentication\AuthenticationService
     */
    private $authService;
    
    /**
     * UserManager
     * @var \UserAuth\Model\Service\UserManager
     */
    private $userManager;
    
    public function __construct($entityManager, $authManager, $authService, $userManager)
    {
        $this->entityManager = $entityManager;
        $this->authManager   = $authManager;
        $this->authService   = $authService;
        $this->userManager   = $userManager;
    }
    
    public function loginAction()
    {
        
    	if ($this->authService->hasIdentity() )
    		$this->authService->clearIdentity();
    	
        $redirectUrl = (string)$this->params()->fromQuery('redirectUrl', '');
        if(strlen($redirectUrl) > 2048) {
            throw new \Exception('Die URL für die Weiterleitung ist zu lange!');
        }
        
        // Create the login form
        $form = new LoginForm();
        $form->get('redirect_url')->setValue($redirectUrl);
        
        // store login status
        
        $isLoginError = false;
        $message = null;
        $codes = null;
        
        // Check if it a POST request
        if($this->getRequest()->isPost()) {
            
            // Fill the form with the POST data.
            $data = $this->params()->fromPost();
            
            $form->setData($data);

            // Validate form
            if($form->isValid()) {

 	            // Get filtered and validated data
                $data = $form->getData();
                //Perform the login attempt.
                $result = $this->authManager->login($data['email'], $data['password'], $data['remember_me']);
                $message = $result->getMessages()[0];
                $codes = $result->getCode();
                
                // Check login result
                if($result->getCode() == RESULT::SUCCESS) {
                    
                    // Get the redirect URL from Post
                    $redirectUrl = $this->params()->fromPost($redirectUrl);
                    
                    if(!empty($redirectUrl)) {
                        // The below check is to prevent poosible redirect attack
                        // if someone tries to redirect user to another domain.
                        $uri = new Uri($redirectUrl);
                        if(!$uri->isValid() || $uri->getHost() != null)
                            throw new \Exception('Falsche Weiterleitung: ' . $redirectUrl);
                    }
                    
                    // If redirect URL is provided, redirect the user to that URL;
                    // otherwise redirect to Home page.
                    if(empty($redirectUrl)) {
                        return $this->redirect()->toRoute('home');
                    } else {
                        $this->redirect()->toUrl($redirectUrl);
                    }
                } else {
                    $isLoginError = true;
                }
            } else {
                $isLoginError = true;
            }
        }

        return new ViewModel([
            'form' => $form,
            'isLoginError' => $isLoginError,
            'message' => $message,
        	'codes' => $codes,
            'redirectUrl' => $redirectUrl
        ]);
    }
    public function logoutAction()
    {
    	$this->authManager->logout();
    
    	return $this->redirect()->toRoute('home');
    }
}