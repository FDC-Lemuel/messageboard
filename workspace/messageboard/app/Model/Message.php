<?php
App::uses('AppModel', 'Model');
/**
 * Message Model
 *
 * @property User $User
 * @property Conversation $Conversation
 */
class Message extends AppModel {

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'message' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);

	public function beforeSave($options = array())
	{
		$ipAddress = env('HTTP_CLIENT_IP') ? env('HTTP_CLIENT_IP') : env('REMOTE_ADDR');
		$this->data['Message']['created_ip'] = $ipAddress;
		$this->data['Message']['modified_ip'] = $ipAddress;
		return true;
	}

	// The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Conversation' => array(
			'className' => 'Conversation',
			'foreignKey' => 'conversation_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
