<?php
namespace UserAuth\Frontend\Controller;

use UserAuth\Frontend\Form\ForgetPasswordForm;
use UserAuth\Frontend\Form\ResetPasswordForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\MvcEvent;
use Zend\View\Model\ViewModel;
use UserAuth\Entity\UserEntity;
use GSMail\Service\GSMailManager;

/**
 *  This controller is responsible for frontend user management.
 */
class UserController extends AbstractActionController
{

	/**
	 * Entity manager.
	 * @var Doctrine\ORM\EntityManager
	 */
	private $entityManager;

	/**
	 * User manager.
	 * @var UserAuth\Model\Service\UserManager
	 */
	private $userManager;

	/**
	 * Mail manager.
	 * @var GSMail\Service\GSMailManager
	 */
	private $mailManager;

	/**
	 * Constructor
	 * @param Doctrine\ORM\EntityManager $entityManager
	 * @param UserAuth\Model\Service\UserManager $userManager
	 * @param GSMail\Service\GSMailManager $mailManager
	 */
	public function __construct ($entityManager, $userManager, $mailManager)
	{
		$this->entityManager = $entityManager;
		$this->userManager 	 = $userManager;
		$this->mailManager 	 = $mailManager;
	}

	/**
	 * Call the onDispatch for switching the layout
	 * {@inheritDoc}
	 * @see \Zend\Mvc\Controller\AbstractActionController::onDispatch()
	 */
	public function onDispatch (MvcEvent $e)
	{
		$response = parent::onDispatch($e);

		$this->layout('layout/layout2');

		return $response;
	}

	/**
	 * This action handles the forget password routine
	 */
	public function forgetPasswordAction ()
	{
		// Create form
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

	/**
	 * This action displays additional informational message page.
	 * 
	 * @throws \Exception
	 * @return \Zend\View\Model\ViewModel
	 */
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
		$token = $this->params()->fromQuery('token', null);
		
		// validate token length
		if ($token!=null && (!is_string($token) || strlen($token)!=32)) {
			throw new \Exception('Invalid token type or length');
		}
		
		if($token === null || !$this->userManager->validatePasswordResetToken($token)) {
				return $this->redirect()->toRoute('login');
		}
				
		$form = new ResetPasswordForm();

		if($this->getRequest()->isPost()) {
			$data = $this->params()->fromPost();

			$form->setData($data);

			if($form->isValid()) {
				
				$data = $form->getData();

				if($this->userManager->setNewPasswordByToken($token, $data['new_password'])) {
					return $this->redirect()->toRoute('login');
				} else {
					return $this->redirect()->toRoute('home');
				}
			} else {
				\Zend\Debug\Debug::dump($form->isValid());
			}
		}
		\Zend\Debug\Debug::dump($form->getMessages());
		return new ViewModel([
			'form' => $form,
		]);
	}

	public function successAction ()
	{
		return new ViewModel();
	}
}