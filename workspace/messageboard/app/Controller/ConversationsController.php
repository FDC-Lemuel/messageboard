<?php
App::uses('AppController', 'Controller');
/**
 * Conversations Controller
 *
 * @property Conversation $Conversation
 * @property PaginatorComponent $Paginator
 */
class ConversationsController extends AppController
{

	/**
	 * Components
	 *
	 * @var array
	 */
	public $components = array('Paginator', 'RequestHandler');
	public $helpers = array('Js');
	public $uses = array('Conversation', 'Message', 'User', 'ConversationMember');

	/**
	 * index method
	 *
	 * @return void
	 */
	public function index()
	{
		$this->redirect(array('action' => 'add'));
	}

	/**
	 * view method
	 *
	 * @throws NotFoundException
	 * @param string $id
	 * @return void
	 */
	public function view($id = null)
	{
		$ownership = $this->Conversation->ConversationMember->find('first', array(
			'conditions' => array(
				'ConversationMember.conversation_id' => $id,
				'ConversationMember.user_id' => $this->Auth->user('id')
			)
		));

		if (!$this->Conversation->exists($id) || empty($ownership)) {
			$this->Flash->error(__('Invalid conversation or you do not have access to this conversation.'));
			$this->redirect(array('action' => 'add'));
		}
		$this->set('conversation_id', $id);

		$max = $this->ConversationMember->find('count', array(
			'conditions' => array('ConversationMember.user_id' => $this->Auth->user('id'))
		));
		$this->set('max', $max);

		$conversation_limit = $this->request->query['conversation_limit'] ?? 3;
		$this->set('conversation_limit', $conversation_limit);

		$max = $this->ConversationMember->find('count', array(
			'conditions' => array('ConversationMember.user_id' => $this->Auth->user('id'))
		));
		$this->set('max', $max);

		$limit = $this->request->query['limit'] ?? 5;
		$this->set('limit', $limit);

		$searchTerm = $this->request->query['searchTerm'] ?? '';

		$counter = $this->Conversation->Message->find('count', array(
			'conditions' => array(
				'Message.conversation_id' => $id,
				'Message.message LIKE' => '%' . $searchTerm . '%'
			),
		));
		$this->set('counter', $counter);

		$messages = $this->Conversation->Message->find('all', array(
			'conditions' => array(
				'Message.conversation_id' => $id,
				'Message.message LIKE' => '%' . $searchTerm . '%'
			),
			'order' => array('Message.created' => 'desc'),
			'limit' => $limit,
		));

		$this->set('messages', $messages);

		$recepient = $this->User->find('first', [
			'conditions' => array('User.id' => $this->Conversation->ConversationMember->find('first', [
				'conditions' => array('ConversationMember.conversation_id' => $id, 'ConversationMember.user_id !=' => $this->Auth->user('id')),
				'fields' => array('ConversationMember.user_id')
			])['ConversationMember']['user_id'])
		]);
		$this->set('recepient', $recepient);
		$this->set('conversations', $this->getMessageList($conversation_limit));
	}

	/**
	 * add method
	 *
	 * @return void
	 */
	public function add()
	{
		$conversation_limit = $this->request->query['conversation_limit'] ?? 3;
		$this->set('conversation_limit', $conversation_limit);

		$max = $this->ConversationMember->find('count', array(
			'conditions' => array('ConversationMember.user_id' => $this->Auth->user('id'))
		));
		$this->set('max', $max);

		$users = $this->Conversation->ConversationMember->User->find('list', array(
			'conditions' => array('User.id !=' => $this->Auth->user('id')),
			'select' => array('User.id', 'User.name')
		));

		$this->set(compact('users'));

		$this->set('conversations', $this->getMessageList($conversation_limit));

		if ($this->request->is('post') && !$this->request->is('ajax')) {
			$senderUserId = $this->Auth->user('id');
			$receiverUserId = $this->request->data['Conversation']['receiver_id'];

			$authUserConversations = $this->ConversationMember->find('list', array(
				'fields' => array('ConversationMember.conversation_id'),
				'conditions' => array('ConversationMember.user_id' => $senderUserId)
			));

			$receiverUserConversations = $this->ConversationMember->find('list', array(
				'fields' => array('ConversationMember.conversation_id'),
				'conditions' => array('ConversationMember.user_id' => $receiverUserId)
			));

			$sharedConversation_id = array_intersect($authUserConversations, $receiverUserConversations);
			if (!empty($sharedConversation_id)) {
				$conversation_id = reset($sharedConversation_id);
				$this->Conversation->id = $conversation_id;
				$this->request->data['Conversation']['id'] = $conversation_id;
			} else {
				// Create new conversation
				$this->Conversation->create();
				if ($this->Conversation->save($this->request->data)) {
					$data = [];
					$members = [
						$this->Auth->user('id'),
						$this->request->data['Conversation']['receiver_id']
					];

					foreach ($members as $userId) {
						$this->Conversation->ConversationMember->create();
						$data['ConversationMember']['conversation_id'] = $this->Conversation->id;
						$data['ConversationMember']['user_id'] = $userId;
						$this->Conversation->ConversationMember->save($data);
					}
				} else {
					$this->Flash->error(__('Could not create conversation. Please, try again.'));
					return;
				}
			}

			// Save message
			$this->Message->create();
			$data = [
				'Message' => [
					'conversation_id' => $this->Conversation->id,
					'user_id' => $this->Auth->user('id'),
					'message' => $this->request->data['Conversation']['message']
				]
			];

			if ($this->Message->save($data)) {
				// Update conversation with the last message ID
				$this->request->data['Conversation']['last_message_id'] = $this->Message->id;
				if ($this->Conversation->save($this->request->data)) {
					return $this->redirect(array('action' => 'view', $this->Conversation->id));
				} else {
					$this->Flash->error(__('The conversation could not be updated with the last message. Please, try again.'));
				}
			} else {
				$this->Flash->error(__('The message could not be saved. Please, try again.'));
			}
		}
	}

	/**
	 * edit method
	 *
	 * @throws NotFoundException
	 * @param string $id
	 * @return void
	 */
	public function edit($id = null)
	{
		return $this->redirect(array('action' => 'add'));
	}

	/**
	 * delete method
	 *
	 * @throws NotFoundException
	 * @param string $id
	 * @return void
	 */
	public function delete($id = null)
	{
		if (!$this->Conversation->exists($id)) {
			throw new NotFoundException(__('Invalid conversation'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Conversation->delete($id)) {
			$this->Message->deleteAll(array('conversation_id' => $id), false);
			$this->ConversationMember->deleteAll(array('conversation_id' => $id), false);
			$this->Flash->success(__('The conversation has been deleted.'));
		} else {
			$this->Flash->error(__('The conversation could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'add'));
	}

	private function getMessageList($limit = 0)
	{
		$conversations = $this->Conversation->ConversationMember->find('all', array(
			'conditions' => array(
				'ConversationMember.user_id' => $this->Auth->user('id')
			),
			'joins' => array(
				array(
					'table' => 'conversation_members',
					'alias' => 'Receiver',
					'type' => 'LEFT',
					'conditions' => array(
						'Receiver.conversation_id = ConversationMember.conversation_id',
						'Receiver.user_id !=' => $this->Auth->user('id')
					)
				),
				array(
					'table' => 'messages',
					'alias' => 'LastMessage',
					'type' => 'LEFT',
					'conditions' => array(
						'LastMessage.id = Conversation.last_message_id'
					)
				)
			),
			'fields' => array(
				'Receiver.user_id',
				'Conversation.id',
				'LastMessage.modified',
				'LastMessage.message',
			),
			'order' => array(
				'LastMessage.created' => 'DESC'
			),
			'limit' => $limit
		));


		return $conversations;
	}
}
