<?php
App::uses('AppModel', 'Model');

/**
 * User Model
 *
 * @property ConversationMember $ConversationMember
 * @property Conversation $Conversation
 * @property Message $Message
 */
class User extends AppModel
{

	/**
	 * Display field
	 *
	 * @var string
	 */
	public $displayField = 'name';

	/**
	 * Validation rules
	 *
	 * @var array
	 */
	public $validate = array(
		'name' => array(
			'notBlank' => array(
				'rule' => array('notBlank', 'isUnique'),
			),
			'between' => array(
				'rule' => array('between', 5, 20),
				'message' => 'The name must be between 5 and 20 characters.'
			)
		),
		'email' => array(
			'email' => array(
				'rule' => array('email'),
				'message' => 'This email is invalid.'
			),
			'unique' => array(
				'rule' => array('isUnique'),
				'message' => 'This email is already taken.'
			)
		),
		'password' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
			),
		),
		'confirm_password' => [
			'notBlank' => array(
				'rule' => array('notBlank'),
			),
			'compare' => array(
				'rule' => array('compareFields'),
				'message' => 'The passwords do not match.'
			)
		]
	);

	public function compareFields($data)
	{
		if ($this->data['User']['password'] === $data['confirm_password']) {
			return true;
		}

		return false;
	}

	public function beforeSave($options = array())
	{
		if (isset($this->data['User']['password']) && !empty($this->data['User']['password'])) {
			$this->data['User']['password'] = AuthComponent::password($this->data['User']['password']);
		}

		if(isset($this->data['User']['birthdate']) && !empty($this->data['User']['birthdate'])) {
			$this->data['User']['birthdate'] = date('Y-m-d', strtotime($this->data['User']['birthdate']));
		}	

		return true;
	}

	public function calculateAge($birthdate)
	{
		$birthDate = new \DateTime($birthdate);
		$today = new \DateTime();
		return $today->diff($birthDate)->y;
	}

	public function checkUnique($ignoredData, $fields, $or = true)
	{
		return $this->isUnique($fields, $or);
	}

	// Associations below have been created with all possible keys, those that are not needed can be removed

	/**
	 * hasMany associations
	 *
	 * @var array
	 */
	public $hasMany = array(
		'ConversationMember' => array(
			'className' => 'ConversationMember',
			'foreignKey' => 'user_id',
			'dependent' => false
		),
		'Message' => array(
			'className' => 'Message',
			'foreignKey' => 'user_id',
			'dependent' => false
		)
	);
	
}
