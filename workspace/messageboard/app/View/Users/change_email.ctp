<div class="users form">
	<h2><?php echo __('Change Email'); ?></h2>
	<?php echo $this->Form->create('User'); ?>
	<div class="container">
		<div class="row">
			<div class="col">
				<?php if ($this->Form->isFieldError('email')) : ?>
					<div class="error">
						<ul>
							<?php
							foreach ($this->Form->validationErrors['User'] as $field => $messages) :
								foreach ($messages as $message) :
									echo '<li>' . h($message) . '</li>';
								endforeach;
							endforeach;
							?>
						</ul>
					</div>
				<?php endif; ?>
			</div>
		</div>
		<div class="row">
			<div class="col-md-2 text-center">
				<?php
				$image_url = getAvatarURL($user['User']['id']);
				echo $this->Html->image(
					$image_url,
					[
						'alt' => "Profile Image of " . h($user['User']['name']),
						'class' => 'img-fluid rounded-square',
						'id' => "profileImage",
						'style' => 'width: 100px; max-height: 200px;'
					]
				); ?>
				<h2 style="color: black;">
					<?php echo h($user['User']['name']) . ' '; ?>
				</h2>
			</div>
			<div class="col-md-6">
				<?php echo $this->Form->input('email', [
					'value' => $user['User']['email'],
					'label' => 'Set New Email for ' . h($user['User']['name']),
					'class' => 'form-control',
					'id' => 'email',
					'type' => 'email',
					'required' => true,
					'errorMessage' => false
				]); ?>
				<?php echo $this->Form->end(__('Update Email')); ?>
			</div>
		</div>
	</div>
</div>


<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<?php echo $this->element('actions', ['id' => $user['User']['id']]); ?>
	</ul>
</div>