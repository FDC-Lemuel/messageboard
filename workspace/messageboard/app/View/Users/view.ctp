<div class="users view">
	<h2><?php echo __('User Profile'); ?></h2>
	<div class="row">
		<div class="col-md-2">
			<?php
			$image_url = getAvatarURL($user['User']['id']);
			echo $this->Html->image(
				$image_url,
				[
					'alt' => "Profile Image of " . h($user['User']['name']),
					'class' => 'img-fluid rounded-square',
					'id' => "profileImage"
				]
			); ?>
		</div>
		<div class="col-md-8">
			<h2 style="color: black;">
				<?php echo h($user['User']['name']) . ' ';
				$birthDate = new \DateTime($user['User']['birthdate']);
				$today = new \DateTime();
				echo $today->diff($birthDate)->y > 0 ? '(' . $today->diff($birthDate)->y . ')' : '';
				?>
			</h2>
			<p><strong>Gender:</strong> <?php echo h($user['User']['gender']); ?> </p>
			<p><strong>Email:</strong> <?php echo h($user['User']['email']); ?></p>
			<p><strong>Birthdate:</strong>
				<?php
				$birthdate = new DateTime($user['User']['birthdate']);
				echo h($birthdate->format('F d, Y'));
				?>
			</p>
			<p><strong>Joined:</strong>
				<?php
				$created = new DateTime($user['User']['created']);
				echo h($created->format('F d, Y hA'));
				?>
			</p>
			<p><strong>Last Login:</strong>
				<?php
				$lastLoginTime = new DateTime($user['User']['last_login_time']);
				echo h($lastLoginTime->format('F d, Y hA'));
				?>
			</p>
		</div>
	</div>
	<div class="row">
		<div class="col-md-10">
			<p><strong>Hubby:</strong></p>
			<p><?php echo h($user['User']['hobby']); ?></p>
		</div>
	</div>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<?php if (AuthComponent::user('id') == $user['User']['id']): ?>
			<li><?php echo $this->Html->link(__('Edit User'), array('controller' => 'users', 'action' => 'edit', $user['User']['id'])); ?></li>
			<li> <?php echo $this->Form->postLink(__('Delete User'), array('controller' => 'users', 'action' => 'delete', $user['User']['id']), array('confirm' => __('Are you sure you want to delete?'))); ?></li>
			<li><?php echo $this->Html->link(__('Change Email'), array('controller' => 'users', 'action' => 'change_email', $user['User']['id'])); ?></li>
			<li><?php echo $this->Html->link(__('Change Password'), array('controller' => 'users', 'action' => 'change_password', $user['User']['id'])); ?></li>
		<?php else: ?>
			<li><?php echo $this->Html->link(__('Go to Messageboard'), array('controller' => 'conversations', 'action' => 'add')); ?></li>
		<?php endif; ?>
	</ul>
</div>