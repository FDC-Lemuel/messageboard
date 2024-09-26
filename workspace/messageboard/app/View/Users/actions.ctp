<ul>
    <li><?php echo $this->Html->link(__('Edit User'), array('controller' => 'users', 'action' => 'edit', $user['User']['id'])); ?></li>
    <li> <?php echo $this->Form->postLink(__('Delete User'), array('controller' => 'users', 'action' => 'delete', $user['User']['id']), array('confirm' => __('Are you sure you want to delete?'))); ?></li>
    <li><?php echo $this->Html->link(__('Change Password'), array('controller' => 'users', 'action' => 'change_password', $user['User']['id'])); ?></li>
    <li><?php echo $this->Html->link('Return to Users', ['controller' => 'users', 'action' => 'index']) ?></li>
</ul>