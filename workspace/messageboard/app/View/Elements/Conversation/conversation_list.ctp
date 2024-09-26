<div class="actions" style="width: 24%; ">
	<h3 class="d-flex justify-content-between"><?php echo __('Message List'); ?> &nbsp;&nbsp;
		<?php echo $this->Html->link(__('New Message'), ['controller' => 'conversations', 'action' => 'add'], ['class' => 'btn btn-sm']); ?>
	</h3>
	<ul class="list-group">
		<?php if (empty($conversations)) : ?>
			<li class="list-group-item list-group-item-action border">No messages found.</li>
		<?php else: ?>
			<div id="conversation_list">
				<?php echo $this->element('conversation/conversation_card', ['conversations' => $conversations, 'max' => $max, 'conversation_id' => $conversation_id ?? '']); ?>
			</div>
		<?php endif; ?>
	</ul>
</div>