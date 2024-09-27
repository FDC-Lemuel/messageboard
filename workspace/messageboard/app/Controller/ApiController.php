<?php
App::uses('AppController', 'Controller');
/**
 * Users Controller
 *
 * @property User $User
 * @property PaginatorComponent $Paginator
 */
class ApiController extends AppController
{
    public $components = array('RequestHandler');
    public $uses = array('User', 'Message', 'Conversation', 'ConversationMember');

    public function fetchmessages($id)
    {
        $offset = $this->request->query['offset'] ?? 0;
        $limit = $this->request->query['limit'] ?? 5;

        $this->autoRender = false;
        $this->layout = 'ajax';
        $this->response->type('json');

        $this->loadModel('Message');

        $messages = $this->Message->find('all', array(
            'conditions' => array(
                'Message.conversation_id' => $id,
                'Message.message LIKE' => '%' . $this->request->query['q'] . '%',
            ),
            'order' => array('Message.created' => 'DESC'),
            'fields' => array('Message.id', 'Message.conversation_id', 'Message.user_id', 'Message.message', 'Message.created', 'User.name'),
            'recursive' => 0,
            'limit' => $limit,
            'offset' => $offset
        ));

        $count = $this->Message->find('count', array(
            'conditions' => array(
                'Message.conversation_id' => $id,
                'Message.message LIKE' => '%' . $this->request->query['q'] . '%',
            ),
        ));

        $formattedMessages['messages'] = array_map(function ($message) use ($count) {
            return array(
                'id' => $message['Message']['id'],
                'conversation_id' => $message['Message']['conversation_id'],
                'user_id' => $message['Message']['user_id'],
                'message' => $message['Message']['message'],
                'created' => $message['Message']['created'],
                'name' => $message['User']['name'],
                'avatar' => getAvatarURL($message['Message']['user_id']),
                'position' => AuthComponent::user('id') == $message['Message']['user_id'] ? 'right' : 'left',
            );
        }, $messages);

        $formattedMessages['count'] = $count;

        $this->response->body(json_encode($formattedMessages));
    }

    public function addMessage($id)
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
                $this->Conversation->save($data);
            }
        } else {
            $this->redirect(array('controller' => 'conversations', 'action' => 'add'));
        }

        $this->autoRender = false;
        $this->layout = 'ajax';
        $this->response->type('json');

        $this->loadModel('Message');

        $message = $this->Message->find('first', array(
            'conditions' => array(
                'Message.id' => $this->Message->id
            ),
            'fields' => array('Message.id', 'Message.conversation_id', 'Message.user_id', 'Message.message', 'Message.created', 'User.name'),
            'recursive' => 0
        ));

        $formattedMessage = array(
            'id' => $this->Message->id,
            'conversation_id' => $message['Message']['conversation_id'],
            'user_id' => $message['Message']['user_id'],
            'message' => $message['Message']['message'],
            'created' => $message['Message']['created'],
            'name' => $message['User']['name'],
            'avatar' => getAvatarURL($message['Message']['user_id']),
            'position' => AuthComponent::user('id') == $message['Message']['user_id'] ? 'right' : 'left',
        );

        $this->response->body(json_encode($formattedMessage));
    }

    public function searchMessages($id)
    {
        $this->autoRender = false;
        $this->layout = 'ajax';
        $this->response->type('json');

        $this->loadModel('Message');

        $offset = $this->request->query['offset'] ?? 0;
        $limit = $this->request->query['limit'] ?? 5;

        $messages = $this->Message->find('all', array(
            'conditions' => array(
                'Message.conversation_id' => $id,
                'Message.message LIKE' => '%' . $this->request->query['q'] . '%',
            ),
            'order' => array('Message.created' => 'DESC'),
            'fields' => array('Message.id', 'Message.conversation_id', 'Message.user_id', 'Message.message', 'Message.created', 'User.name'),
            'recursive' => 0,
            'offset' => $offset,
            'limit' => $limit
        ));

        $count = $this->Message->find('count', array(
            'conditions' => array(
                'Message.conversation_id' => $id,
                'Message.message LIKE' => '%' . $this->request->query['q'] . '%',
            ),
        ));

        $formattedMessages['messages'] = array_map(function ($message) {
            return array(
                'id' => $message['Message']['id'],
                'conversation_id' => $message['Message']['conversation_id'],
                'user_id' => $message['Message']['user_id'],
                'message' => $message['Message']['message'],
                'created' => $message['Message']['created'],
                'name' => $message['User']['name'],
                'avatar' => getAvatarURL($message['Message']['user_id']),
                'position' => AuthComponent::user('id') == $message['Message']['user_id'] ? 'right' : 'left',
            );
        }, $messages);

        $formattedMessages['count'] = $count;

        $this->response->body(json_encode($formattedMessages));
    }

    public function fetchConversationList()
    {
        $this->autoRender = false;
        $this->layout = 'ajax';
        $this->response->type('json');

        $this->loadModel('Conversation');
        $limit = $this->request->query['limit'] ?? 5;

        $conversations = $this->ConversationMember->find('all', array(
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
                ),
                array(
                    'table' => 'users',
                    'alias' => 'ReceiverUser',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'ReceiverUser.id = Receiver.user_id'
                    )
                )
            ),
            'fields' => array(
                'ReceiverUser.name',
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

        $count = $this->ConversationMember->find('count', array(
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
                ),
                array(
                    'table' => 'users',
                    'alias' => 'ReceiverUser',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'ReceiverUser.id = Receiver.user_id'
                    )
                )
            ),
        ));

        $formattedMessages['conversation'] = array_map(function ($message) {
            return array(
                'conversation_id' => $message['Conversation']['id'],
                'user_id' => $message['Receiver']['user_id'],
                'message' => truncateWithEllipsis($message['LastMessage']['message']),
                'modified' => $message['LastMessage']['modified'],
                'name' => $message['ReceiverUser']['name'],
                'avatar' => getAvatarURL($message['Receiver']['user_id']),
            );
        }, $conversations);

        $formattedMessages['count'] = $count;

        $this->response->body(json_encode($formattedMessages));
    }
}
