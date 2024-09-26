<div class="users form">
	<?php echo $this->Form->create('User'); ?>
	<fieldset>
		<legend><?php echo __('Register'); ?></legend>
		<?php
		echo '<div class="error mb-3"';
		if ($this->Form->isFieldError('name') || $this->Form->isFieldError('email') || $this->Form->isFieldError('password') || $this->Form->isFieldError('confirm_password')) :
			echo ' style="display:block"';
		else:
			echo ' style="display:none"';
		endif;
		echo ' id="errorDiv">';
		if ($this->Form->isFieldError('name') || $this->Form->isFieldError('email') || $this->Form->isFieldError('password') || $this->Form->isFieldError('confirm_password')) {
			echo '<ul id="">';
			foreach ($this->Form->validationErrors['User'] as $field => $messages) {
				foreach ($messages as $message) {
					echo '<li>' . h($message) . '</li>';
				}
			}
			echo '</ul>';
		}
		echo '<ul id="errorList">';
		echo '</ul>';
		echo '</div>';
		?>
		<?php
		echo $this->Form->input('name', ['errorMessage' => false, 'id' => 'name']);
		echo $this->Form->input('email', ['errorMessage' => false]);
		echo $this->Form->input('password', ['errorMessage' => false, 'id' => 'password']);
		echo $this->Form->input('confirm_password', ['type' => 'password', 'errorMessage' => false, 'id' => 'confirm_password']);
		?>
		<?php echo $this->Form->end(__('Submit')); ?>
	</fieldset>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<?php if (!AuthComponent::user()): ?>
			<li><?php echo $this->Html->link('Login', ['controller' => 'users', 'action' => 'login']) ?></li>
		<?php endif; ?>
	</ul>
</div>

<script>
	const passwordField = document.getElementById('password');
	const confirmPasswordField = document.getElementById('confirm_password');
	const nameField = document.getElementById('name'); // Add the name field reference
	const errorList = document.getElementById('errorList');
	const errorDiv = document.getElementById('errorDiv');

	function checkPasswordMatch() {
		errorList.innerHTML = ''; // Clear existing errors

		// Check if name is less than 5 characters
		if (nameField.value != "" && nameField.value.length < 5) {
			const errorItem = document.createElement('li');
			errorItem.textContent = 'Name must be at least 5 characters long';
			errorList.appendChild(errorItem);
		}

		// Check if either password field is not empty and if they match
		if (passwordField.value !== "" && confirmPasswordField.value !== "") {
			if (passwordField.value !== confirmPasswordField.value) {
				const errorItem = document.createElement('li');
				errorItem.textContent = 'Passwords do not match';
				errorList.appendChild(errorItem);
			}
		}

		// Show or hide the error div based on the list content
		if (errorList.children.length > 0) {
			console.log(errorList.children.length);
			errorDiv.style.display = 'block'; // Show the div
		} else {
			errorDiv.style.display = 'none'; // Hide the div
		}
	}

	// Attach event listeners
	passwordField.addEventListener('keyup', checkPasswordMatch);
	confirmPasswordField.addEventListener('keyup', checkPasswordMatch);
	nameField.addEventListener('keyup', checkPasswordMatch); // Listen for changes in the name field
</script>
