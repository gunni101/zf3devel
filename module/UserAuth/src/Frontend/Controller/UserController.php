<?php
namespace UserAuth\Frontend\Controller;

use UserAuth\Frontend\Form\ForgetPasswordForm;
use UserAuth\Frontend\Form\ResetPasswordForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\MvcEvent;
use Zend\View\Model\ViewModel;
use UserAuth\Entity\UserEntity;

class UserController extends AbstractActionController
{

	private $entityManager;

	private $userManager;

	private $mailManager;

	public function __construct ($entityManager, $userManager, $mailManager)
	{
		$this->entityManager = $entityManager;
		$this->userManager = $userManager;
		$this->mailManager = $mailManager;
	}

	public function onDispatch (MvcEvent $e)
	{
		$response = parent::onDispatch($e);

		$this->layout('layout/layout2');

		return $response;
	}

	public function forgetPasswordAction ()
	{
		$form = new ForgetPasswordForm();
		
		if ($this->getRequest()->isPost()) {
			$data = $this->params()->fromPost();

			 
			$form->setData($data);
			 
			if($form->isValid()) {
				$user = $this->entityManager->getRepository(UserEntity::class)
				->findOneByEmail($data['email']);
				if($user != null) {
					$this->userManager->generatePasswordResetToken($user);
					 
					return $this->redirect()->toRoute('users', ['action' => 'forgetMessage', 'id' => 'sent']);
				} else {
					return $this->redirect()->toRoute('users', ['action' => 'forgetMessage', 'id' => 'invalid-email']);
				}
			}
		}

		return new ViewModel([
			'form' => $form
		]);
	}

	public function forgetMessageAction()
	{
		$id = (string)$this->params()->fromRoute('id');

		if($id != 'sent' && $id != 'invalid-email') {
			throw new \Exception('Invalid message ID specified!');
		}

		return new ViewModel([
			'id' =>$id
		]);
	}

	public function resetPasswordAction ()
	{
		$form = new ResetPasswordForm();
		
		return new ViewModel([
			'form' => $form,
		]);
	}
	
	public function successAction ()
	{
		return new ViewModel();
	}
}