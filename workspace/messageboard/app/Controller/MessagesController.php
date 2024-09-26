<?php
App::uses('AppController', 'Controller');
/**
 * Messages Controller
 *
 * @property Message $Message
 * @property PaginatorComponent $Paginator
 */
class MessagesController extends AppController
{

	/**
	 * Components
	 *
	 * @var array
	 */
	public $components = array('Paginator');
	public $uses = array('Message', 'Conversation', 'ConversationMember');

	public function user_messages()
	{
		$this->Message->recursive = 0;
		$this->set('messages', $this->Paginator->paginate());
		$this->render('index');
	}

	/**
	 * index method
	 *
	 * @return void
	 */
	public function index()
	{
		$this->Message->recursive = 0;
		$this->set('messages', $this->Paginator->paginate());
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
		if (!$this->Message->exists($id)) {
			throw new NotFoundException(__('Invalid message'));
		}
		$options = array('conditions' => array('Message.' . $this->Message->primaryKey => $id));
		$this->set('message', $this->Message->find('first', $options));

		if ($this->request->is('ajax')) {
			$see_more = $this->request->query['see_more'];
			if ($see_more == 'true') {
				$this->set('see_more', true);
			} else {
				$this->set('see_more', false);
			}
		}

		$this->layout = 'ajax';
	}

	/**
	 * add method
	 *
	 * @return void
	 */
	public function add($id = null)
	{
		if ($this->request->is('ajax')) {
			$this->request->data['Message']['message'] = $this->request->data['message'];
			$this->Message->create();
			$data = [
				'Message' => [
					'conversation_id' => $id,
					'user_id' => $this->Auth->user('id'),
					'message' => $this->request->data['Message']['message']
				]
			];

			if ($this->Message->save($data)) {
				$data = [];
				$data['Conversation']['last_message_id'] = $this->Message->id;
				$this->Conversation->id = $id;
				if ($this->Conversation->save($data)) {
					$this->set('message', $this->Message->read());
					$this->render('/elements/message/message_card_parent');
				}
			}
		} else {
			$this->redirect(array('controller' => 'conversations', 'action' => 'add'));
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
		if (!$this->Message->exists($id)) {
			throw new NotFoundException(__('Invalid message'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Message->save($this->request->data)) {
				$this->Flash->success(__('The message has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The message could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Message.' . $this->Message->primaryKey => $id));
			$this->request->data = $this->Message->find('first', $options);
		}
		$users = $this->Message->User->find('list');
		$conversations = $this->Message->Conversation->find('list');
		$this->set(compact('users', 'conversations'));
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
		if (!$this->Message->exists($id)) {
			throw new NotFoundException(__('Invalid message'));
		}
		$this->request->allowMethod('post', 'delete');
		$message = $this->Message->find('first', [
			'conditions' => ['Message.id' => $id],
			'contain' => ['Conversation'],
		]);
		$conversationId = $message['Message']['conversation_id'];

		if ($this->Message->delete($id)) {
			$countMessages = $this->Message->find('count', [
				'conditions' => ['Message.conversation_id' => $conversationId]
			]);

			$this->Conversation->id = $conversationId;

			if ($countMessages) {
				$latestMessage = $this->Message->find('first', [
					'conditions' => ['Message.conversation_id' => $conversationId, 'Message.id !=' => $id],
					'order' => ['Message.created' => 'DESC'],
					'limit' => 1
				]);
				$this->Conversation->saveField('last_message_id', $latestMessage['Message']['id']);
			} else {
				if($this->Conversation->delete($conversationId)){
					$this->ConversationMember->deleteAll(array('conversation_id' => $conversationId), false);
				}
			}
		}
		$this->autoRender = false;
	}
}
