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

	/**
	 * index method
	 *
	 * @return void
	 */
	public function index()
	{
		$this->redirect(array('controller' => 'conversations', 'action' => 'add'));
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
		$this->autoRender = false;
        $this->layout = 'ajax';
        $this->response->type('json');

        $this->loadModel('Message');

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
		
		$ajax['message'] = $this->Message->find('first', $options);
		$ajax['message'] = $ajax['message']['Message']['message'];
		$ajax['truncated'] = truncateWithEllipsis($ajax['message'], 250);
		return json_encode($ajax);
	}

	/**
	 * add method
	 *
	 * @return void
	 */
	public function add($id = null)
	{
		$this->redirect(array('controller' => 'conversations', 'action' => 'add'));
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
		$this->redirect(array('controller' => 'conversations', 'action' => 'add'));
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
