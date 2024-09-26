<div class="users form">
	<h2><?php echo __('Change Password'); ?></h2>
	<?php echo $this->Form->create('User'); ?>
	<div class="container">
		<div class="row">
			<div class="col">
				<?php if ($this->Form->isFieldError('password') || $this->Form->isFieldError('confirm_password')) : ?>
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
			<div class="col-6">
				<?php
				echo $this->Form->input('password', [
					'label' => 'Set New Password for ' . h($user['User']['name']),
					'class' => 'form-control',
					'errorMessage' => false,
					'required' => true,
					'id' => 'password'
				]);
				echo $this->Form->input('confirm_password', [
					'class' => 'form-control',
					'type' => 'password',
					'errorMessage' => false,
					'required' => true,
					'id' => 'confirm_password'
				]);
				?>
				<?php echo $this->Form->end(__('Update Password')); ?>
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