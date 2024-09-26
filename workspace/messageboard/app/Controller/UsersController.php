<?php
App::uses('AppController', 'Controller');
/**
 * Users Controller
 *
 * @property User $User
 * @property PaginatorComponent $Paginator
 */
class UsersController extends AppController
{
	/**
	 * Components
	 *
	 * @var array
	 */
	public $components = array('Paginator', 'Flash', 'Session');
	public $uses = array('User', 'Message', 'Conversation', 'ConversationMember');

	public function beforeFilter()
	{
		$this->Auth->allow('add');
	}

	public function login()
	{
		$this->render('/Auth/login');
		if ($this->request->is('post')) {
			if ($this->Auth->login()) {
				$userId = $this->Auth->user('id');
				$this->User->id = $userId;
				$this->User->saveField('last_login_time', date('Y-m-d H:i:s'));

				return $this->redirect($this->Auth->redirect('/conversations/add'));
			} else {
				$this->Session->setFlash(__('Invalid username or password, try again'));
			}
		}
	}

	public function logout()
	{
		$this->Auth->logout();
		$this->redirect('/login');
	}

	/**
	 * index method
	 *
	 * @return void
	 */
	public function index()
	{
		$this->User->recursive = 0;
		$this->set('users', $this->Paginator->paginate());
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
		// if (AuthComponent::user('id') != $id) {
		// 	return $this->redirect(array('action' => 'view', AuthComponent::user('id')));
		// }

		if (!$this->User->exists($id)) {
			throw new NotFoundException(__('Invalid user'));
		}
		$options = array('conditions' => array('User.' . $this->User->primaryKey => $id));
		$this->set('user', $this->User->find('first', $options));
	}

	/**
	 * add method
	 *
	 * @return void
	 */
	public function add()
	{
		$previousPage = $this->referer();
		$this->set('previousPage', $previousPage);

		if ($this->request->is('post')) {
			$this->User->create();
			if ($this->User->save($this->request->data)) :
				if ($this->Auth->login()):
					return $this->redirect('/thankyou');
				endif;
			else:
				$this->Flash->error(__('The user could not be saved. Please, try again.'));
			endif;
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
		if (AuthComponent::user('id') != $id) {
			return $this->redirect(array('action' => 'edit', AuthComponent::user('id')));
		}

		if (!$this->User->exists($id)) {
			throw new NotFoundException(__('Invalid user'));
		}

		if ($this->request->is(array('post', 'put'))) {
			$this->User->id = $id;
			if (!empty($this->request->data['User']['avatar']['tmp_name'])) {
				$file = $this->request->data['User']['avatar']['tmp_name'];
				$image = $id;
				$target = WWW_ROOT . 'img' . DS . 'uploads' . DS;
				$target = $target . basename($image);

				if (move_uploaded_file($file, $target)) {
					$this->request->data['User']['avatar'] = 1;
				}
			} 

			if ($this->User->save($this->request->data)) {
				if ($this->Auth->login()) {
					$this->Flash->success(__('The user has been saved.'));
					return $this->redirect(array('action' => 'view', $id));
				}
			} else {
				$this->Flash->error(__('The user could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('User.' . $this->User->primaryKey => $id));
			$this->request->data = $this->User->find('first', $options);
		}
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
		if (!$this->User->exists($id)) {
			throw new NotFoundException(__('Invalid user'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->User->delete($id)) {
			$converastions = $this->ConversationMember->find('all', array('conditions' => array('user_id' => $id)));
			foreach ($converastions as $conversation) {
				$this->Conversation->delete($conversation['ConversationMember']['conversation_id']);
			}
			$this->ConversationMember->deleteAll(array('user_id' => $id), false);
			$this->Message->deleteAll(array('user_id' => $id), false);

			$this->Flash->success(__('The user has been deleted.'));
		} else {
			$this->Flash->error(__('The user could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'logout'));
	}

	public function thankyou()
	{
		$this->render('/Auth/reg_complete');
	}

	public function change_password($id = null)
	{
		if (AuthComponent::user('id') != $id) {
			return $this->redirect(array('action' => 'change_password', AuthComponent::user('id')));
		}

		$this->User->id = $id;
	
		if ($this->request->is(['post', 'put'])) :
			if ($this->User->save($this->request->data)) :
				$this->Flash->success(__('Password has been changed.'));
				return $this->redirect(array('action' => 'view', $id));
			else:
				$this->Flash->error(__('The user could not be saved. Please, try again.'));
			endif;
		endif;

		$user = $this->User->findById($id);
		$this->set('user', $user);
	}

	public function change_email($id = null)
	{
		if (AuthComponent::user('id') != $id) {
			return $this->redirect(array('action' => 'change_email', AuthComponent::user('id')));
		}

		$this->User->id = $id;

		if ($this->request->is(['post', 'put'])) :
			if ($this->User->save($this->request->data)) :
				$this->Flash->success(__('Email has been changed.'));
				return $this->redirect(array('action' => 'view', $id));
			else:
				$this->Flash->error(__('The user could not be saved. Please, try again.'));
			endif;
		endif;

		$user = $this->User->findById($id);
		$this->set('user', $user);
	}
}
