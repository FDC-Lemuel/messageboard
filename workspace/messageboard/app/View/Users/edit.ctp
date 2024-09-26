<div class="users form">
	<h2><?php echo __('User Profile Edit'); ?></h2>
	<?php echo $this->Form->create('User', ['type' => 'file']); ?>
	<?php if ($this->Form->isFieldError('name') || $this->Form->isFieldError('email')) : ?>
		<div class="row">
			<div class="col">
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
			</div>
		</div>
	<?php endif; ?>

	<div class="row">
		<div class="col-md-2">
			<?php
			$image_url = getAvatarURL($this->Form->value('User.id'));
			echo $this->Html->image(
				$image_url,
				[
					'alt' => "Profile Image of " . h($this->request->data['User']['name']),
					'class' => 'img-fluid rounded-square',
					'id' => "profileImage"
				]
			); ?>
			<button id="uploadButton" class="btn btn-success mt-2 w-100" type="button">Upload Pic</button>
			<?php echo $this->Form->file('avatar', [
				'id' => 'fileInput',
				'accept' => '.jpg,.gif,.png,.jpeg',
				'style' => 'display: none;',
				'label' => false
			]); ?>
		</div>
	</div>
	<div class="row">
		<div class="col-md-8">
			<div class="form-group row py-0">
				<label for="name" class="col-md-2 col-form-label">Name</label>
				<div class="col-md-10">
					<?php echo $this->Form->input('name', [
						'label' => false,
						'class' => 'form-control',
						'id' => 'name',
						'required' => true,
						'errorMessage' => false
					]); ?>
				</div>
			</div>
			<div class="form-group row py-0">
				<label for="datepicker" class="col-md-2 col-form-label">Birthdate</label>
				<div class="col-md-10">
					<?php
					$birthdate = new DateTime($this->request->data['User']['birthdate']);
					echo $this->Form->input('birthdate', [
						'type' => 'text',
						'label' => false,
						'value' => $this->request->data['User']['birthdate'] ? h($birthdate->format('F d, Y')) : '',
						'class' => 'form-control',
						'id' => 'datepicker',
						'required' => true,
						'errorMessage' => false
					]);
					?>
				</div>
			</div>
			<div class="form-group row py-0">
				<label for="gender" class="col-md-2 col-form-label">Gender</label>
				<div class="col-md-10 form-inline">
					<input name="data[User][gender]" id="male" class="form-control" type="radio"
						value="Male" <?php echo $this->request->data['User']['gender'] == "Male" ? "checked='checked'" : ""; ?>>
					<label for="male" class="ml-2">Male</label>

					<input name="data[User][gender]" id="female" class="form-control ml-4" type="radio"
						value="Female" <?php echo $this->request->data['User']['gender'] == "Female" ? "checked='checked'" : ""; ?>>
					<label for="female" class="ml-2">Female</label>
				</div>
			</div>
			<div class="form-group row py-0">
				<label for="hobby" class="col-md-2 col-form-label">Hobby</label>
				<div class="col-md-10">
					<?php echo $this->Form->input('hobby', [
						'label' => false,
						'class' => 'form-control',
						'id' => 'hobby',
						'type' => 'textarea',
						'rows' => 10,
						'required' => true,
						'errorMessage' => false
					]); ?>
				</div>
			</div>
			<div class="form-group row py-0">
				<div class="col-md-2"></div>
				<div class="col-md-10">
					<?php echo $this->Form->end(__('Update')); ?>
				</div>
			</div>
		</div>
	</div>
</div>


<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('View User'), array('controller' => 'users', 'action' => 'view', $this->Form->value('User.id'))); ?></li>
		<li> <?php echo $this->Form->postLink(__('Delete User'), array('action' => 'delete', $this->Form->value('User.id')), array('confirm' => __('Are you sure you want to delete?'))); ?></li>
		<li><?php echo $this->Html->link(__('Change Email'), array('controller' => 'users', 'action' => 'change_email', $this->Form->value('User.id'))); ?></li>
		<li><?php echo $this->Html->link(__('Change Password'), array('controller' => 'users', 'action' => 'change_password', $this->Form->value('User.id'))); ?></li>
		<!-- <li><?php echo $this->Html->link('Return to Users', ['controller' => 'users', 'action' => 'index']) ?></li> -->
	</ul>
</div>

<script>
	$(function() {
		$("#datepicker").datepicker({
			dateFormat: "MM dd, yy"
		});
	});

	// Get references to the elements
	const fileInput = document.getElementById('fileInput');
	const uploadButton = document.getElementById('uploadButton');
	const profileImage = document.getElementById('profileImage');

	// Trigger file input on button click
	uploadButton.addEventListener('click', function() {
		fileInput.click();
	});

	// Handle file input change
	fileInput.addEventListener('change', function() {
		const file = fileInput.files[0]; // Get the selected file

		if (file) {
			const fileType = file.type;
			const validTypes = ['image/jpeg', 'image/gif', 'image/png'];

			// Validate file type
			if (!validTypes.includes(fileType)) {
				fileInput.value = ''; // Clear the input
			} else {
				// Use FileReader to display the selected image
				const reader = new FileReader();
				reader.onload = function(e) {
					profileImage.src = e.target.result; // Set the image src to the selected file
				};
				reader.readAsDataURL(file); // Read the file as a data URL
			}
		}
	});
</script>