<?php
namespace UserAuth\Frontend\Controller;

use Zend\View\Model\ViewModel;
use Zend\Mvc\Controller\AbstractActionController;
use UserAuth\Frontend\Form\RegisterForm;
use UserAuth\Entity\UserEntity;
use GSMail\Service\GSMailManager;
use Zend\Mvc\MvcEvent;

class RegisterController extends AbstractActionController
{
    
    private $entityManager;
    
    private $userManager;
    
    private $mailManager;
    
    
    public function __construct($entityManager, $userManager, $mailManager)
    {
        $this->entityManager = $entityManager;
        $this->userManager = $userManager;
        $this->mailManager = $mailManager;
    }
    
    public function onDispatch(MvcEvent $e)
    {
        $response = parent::onDispatch($e);
        
        $this->layout('layout/layout2');
        
        return $response;
    }
    
    public function registerAction()
    {
        $registerForm = new RegisterForm($this->entityManager, $this->userManager);
        
        if($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $registerForm->setData($data);
            
            if($registerForm->isValid()) {
                $data = $registerForm->getData();
                
                $user = $this->userManager->addUser($data);
                
                $mailData = [
                    'to' => [
                        'schwarz@domesle.de' => 'Guenther Schwarz',
                    ],
                    'subject' => 'Neuer Intranet Benutzer!',
                ];
                
                $template = ['success2admin' => __DIR__ . '/../../../view/userauth/mails/success2admin.phtml'];
                
                $this->mailManager->gsMail($mailData, $template);
                return $this->redirect()->toRoute('user', ['action' => 'success']);
            }
        }
        
        return new ViewModel([
        	'form' => $registerForm,
        ]);
    }
    
    public function forgetPasswordAction()
    {
        $forgetPasswordForm = new \UserAuth\Frontend\Form\ForgetPasswordForm();
        
        return new ViewModel([
            'form' => $forgetPasswordForm,
        ]);
    }
    
    public function successAction()
    {
        return new ViewModel();
        
    }
}