<li><?php echo $this->Html->link(__('View User'), array('controller' => 'users', 'action' => 'view', $id)); ?></li>
<li> <?php echo $this->Form->postLink(__('Delete User'), array('controller' => 'users', 'action' => 'delete', $id), array('confirm' => __('Are you sure you want to delete?'))); ?></li>
<li><?php echo $this->Html->link(__('Change Email'), array('controller' => 'users', 'action' => 'change_email', $id)); ?></li>
<li><?php echo $this->Html->link(__('Change Password'), array('controller' => 'users', 'action' => 'change_password', $id)); ?></li>