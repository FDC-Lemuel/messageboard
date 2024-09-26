<?php
App::uses('AppModel', 'Model');
/**
 * Conversation Model
 *
 * @property User $User
 * @property Message $Message
 */
class Conversation extends AppModel {


	// The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Message' => array(
			'className' => 'Message',
			'foreignKey' => 'conversation_id',
		),
		'ConversationMember' => array(
			'className' => 'ConversationMember',
			'foreignKey' => 'conversation_id',
		),
	);

}
