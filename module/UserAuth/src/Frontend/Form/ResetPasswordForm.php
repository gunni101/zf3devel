<?php

namespace UserAuth\Frontend\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;

class ResetPasswordForm extends Form
{
	/**
	 * Constructor.
	 */
	public function __construct()
	{
		parent::__construct('reset-password-form');
		
		$this->setAttribute('method', 'post');
		
		$this->addElements();
		$this->addInputFilter();
	}
	
	public function addElements()
	{
		// Add password
		$this->add([
			'type' => 'password',
			'name' => 'password',
			'options' => [
				'label' => 'Neues Passwort',
			],
		]);
		
		// Add confirm password 
		$this->add([
			'type' => 'password',
			'name' => 'confirm_password',
			'options' => [
				'label' => 'Neues Passwort wiederholen',
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
		
    	// Add filter for password
		$inputFilter->add([
			'name' => 'password',
			'required' => true,
			'filters' => [
				
			],
			'validators' => [
				[
					'name' => 'StringLength',
					'options' => [
						'min' => 6,
						'max' => 64,
					],
				],
			],
		]);
		
		// Add filter for confirm_password
		$inputFilter->add([
			'name' => 'confirm_password',
			'required' => true,
			'filters' => [
				
			],
			'validators' => [
				[
					'name' => 'Identical',
					'options' => [
						'token' => 'password',
					],
				],
			],
		]);
	}
	
}
