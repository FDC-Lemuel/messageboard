<?php

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @package       app.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

$cakeDescription = __d('cake_dev', 'CakePHP: the rapid development php framework');
$cakeVersion = __d('cake_dev', 'CakePHP %s', Configure::version())
?>
<!DOCTYPE html>
<html>

<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		Message Board
	</title>

	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
	<link rel="stylesheet" href="https://code.jquery.com/ui/1.14.0/themes/base/jquery-ui.css">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />

	<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
	<script src="https://code.jquery.com/ui/1.14.0/jquery-ui.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

	<script>
		const messages_url = '<?php echo $this->Html->url(['controller' => 'messages', 'action' => 'index']); ?>';
		const conversation_url = '<?php echo $this->Html->url(['controller' => 'conversations', 'action' => 'index']); ?>';
		const users_url = '<?php echo $this->Html->url(['controller' => 'users', 'action' => 'index']); ?>';
		const api_url = '<?php echo $this->Html->url(['controller' => 'api', 'action' => 'index']); ?>';
		const conversation_id = <?php echo $conversation_id ?? ''; ?>;
	</script>

	<?php
	echo $this->Html->meta('icon');

	echo $this->Html->css('cake.generic');

	echo $this->fetch('meta');
	echo $this->fetch('css');
	echo $this->fetch('script');

	echo $this->Html->css('custom');
	echo $this->Html->script('functions');
	echo $this->Html->script('converter_functions');
	?>
	<style>
		.form div {
			margin-bottom: 0;
			padding-left: 0;
			padding-right: 0;
		}

		.select2-results__options {
			background-color: white;
			color: black;
		}

		.select2-results__options li {
			margin: 0;
		}

		.submit {
			text-align: right;
		}

		.active-conversation {
			border-color: red !important;
			color: red !important;
		}

		body {
			background-color: #343a40;
		}
	</style>
</head>

<body>
	<div id="container">
		<div id="header">
			<?php if (AuthComponent::user()): ?>
				<div class="d-flex align-items-end">
					<?php
					$image_url = getAvatarURL(AuthComponent::user('id'));
					echo $this->Html->image(
						$image_url,
						[
							'alt' => "Profile Image of " . h(AuthComponent::user('name')),
							'class' => 'img-fluid rounded-square border-white border',
							'style' => 'width: 50px; margin-right: 10px;'
						]
					); ?>
					<div class="">
						<div>
							<b class="h4"><?php echo AuthComponent::user('name'); ?></b>
						</div>
						<br />
						<div>
							<?php echo $this->HTML->link('View Profile', ['controller' => 'users', 'action' => 'view', AuthComponent::user('id')], ['class' => 'btn btn-primary btn-sm text-decoration-none']); ?>
							&nbsp;|&nbsp;
							<?php echo $this->HTML->link('View Messageboard', ['controller' => 'conversations', 'action' => 'add'], ['class' => 'btn btn-info btn-sm text-decoration-none']); ?>
							&nbsp;|&nbsp;
							<?php echo $this->HTML->link('Logout', ['controller' => 'users', 'action' => 'logout'], ['class' => 'btn btn-danger btn-sm text-decoration-none']); ?>
						</div>
					</div>
				</div>

			<?php else: ?>
				<b class="h5">Please Login to continue</b>
			<?php endif; ?>
		</div>
		<div id="content">
			<?php echo $this->Flash->render(); ?>

			<?php echo $this->fetch('content'); ?>
		</div>
		<div id="footer">
		</div>
	</div>
</body>

</html>