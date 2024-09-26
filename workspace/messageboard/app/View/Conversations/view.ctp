<div class="conversations view">
	<div class="row">
		<div class="col-md-8 d-flex align-items-end">
			<?php
			$image_url = getAvatarURL($recepient['User']['id']);
			echo $this->Html->image(
				$image_url,
				[
					'alt' => "Profile Image of " . h($recepient['User']['name']),
					'class' => 'img-fluid rounded-square',
					'style' => 'width: 100px; margin-right: 10px;'
				]
			); ?>
			<div>
				<span class="h3"><?php echo $recepient['User']['name']; ?></span>
				<br />
				<small>Last Login Time: <b><?php echo $recepient['User']['last_login_time'] ? formatDate($recepient['User']['last_login_time']) : "Never logged in yet"; ?></b></small>
			</div>
			<div class="dropdown ml-auto">
				<button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					Actions
				</button>
				<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
					<?php echo $this->HTML->link('View Profile', [
						'controller' => 'users',
						'action' => 'view',
						$recepient['User']['id'],
					], ['class' => 'dropdown-item text-decoration-none']); ?>
					<?php echo $this->Form->postLink('Delete Conversation', [
						'controller' => 'conversations',
						'action' => 'delete',
						$conversation_id
					], [
						'class' => 'dropdown-item text-decoration-none',
						'confirm' => __('Are you sure you want to delete?')
					]); ?>
				</div>
			</div>
		</div>
	</div>

	<?php echo $this->Form->create('Message', [
		'style' => 'width:100%',
		'method' => 'POST',
		'url' => [
			'controller' => 'conversations',
			'action' => 'view',
			$conversation_id
		],
		'id' => 'messageForm'
	]); ?>
	<div class="row pb-0 mb-0">
		<div class="col-md-4 my-0">
			<h2 class="mb-0 text-black" style="color: black !important;"><?php echo __('Message Details'); ?></h2>
		</div>
		<div class="col-md-3 my-0 text-right">
			<input type="text" placeholder="Search Messages" class="form-control" id="search_message" style="width:100%; margin-top:0 !important;">
		</div>
		<div class="col-md-2 my-0">
			<button class="btn btn-sm btn-secondary" id="new-reply" type="button">
				New Reply
			</button>
		</div>
	</div>
	<div class="row reply-message mb-0" style="display: none;">
		<div class="col-md-8 mb-0 text-right">
			<?php echo $this->Form->input(
				'message',
				[
					'label' => false,
					'id' => 'MessageInput',
					'type' => 'textarea',
					'rows' => 3,
					'class' => 'form-control',
					'placeholder' => __('Enter your message here'),
					'div' => [
						'style' => 'margin-bottom:0; !important',
						'class' => 'p-0'
					]
				]
			);
			?>
			<?php echo $this->Form->button(__('Reply Message'), [
				'id' => 'reply-message-button',
				'class' => 'btn btn-success mt-2',
				'div' => [
					'style' => 'margin-top:0; !important'
				]
			]); ?>
		</div>
	</div>
	<div class="row mt-2">
		<div class="col-md-8" id="message-details">
			<?php echo $this->element('message/message_details'); ?>
		</div>
	</div>
</div>
<?php echo $this->element('conversation/conversation_list', ['conversations' => $conversations, 'max' => $max, 'conversation_id' => $conversation_id]); ?>

<script>
	let limit = 5;

	function showmore() {
		limit = limit + 5;
		console.log(limit);
		const searchTerm = $('#search_message').val().trim();
		$.ajax({
			url: '<?php echo $this->Html->url(['controller' => 'conversations', 'action' => 'view', $conversation_id]); ?>',
			type: 'GET',
			data: {
				limit: limit,
				searchTerm: searchTerm,
				type: 'message'
			},
			success: function(response) {
				$('#message-details').html(response);

				// Scroll to the bottom of the message-details div
				const messageDetails = $('#messages-conversations');
				messageDetails.scrollTop(messageDetails.prop("scrollHeight"));
			},
			error: function(xhr, status, error) {
				console.error(error);
			}
		});
	};


	$(document).ready(function() {
		const $newReplyButton = $('#new-reply');
		const $messageDetails = $('#message-details');
		const $searchMessage = $('#search_message');
		const $showMore = $('#show_more');
		const $messageInput = $('#MessageInput');
		const conversationId = <?php echo $conversation_id; ?>;
		const baseUrl = '<?php echo $this->Html->url(['controller' => 'conversations', 'action' => 'view', $conversation_id]); ?>';
		const max = <?php echo $counter; ?>;

		// Toggle reply section and button text/styles
		$newReplyButton.on('click', function() {
			$('.reply-message').toggle();
			$(this).toggleClass('btn-secondary btn-danger').text(function(_, text) {
				return text === 'Cancel' ? 'New Reply' : 'Cancel';
			});
		});

		let searchTimeout = null;
		$searchMessage.on('keyup', function() {
			clearTimeout(searchTimeout);
			const searchTerm = $searchMessage.val().trim();
			searchTimeout = setTimeout(function() {
				$.ajax({
					url: baseUrl,
					type: 'GET',
					data: {
						searchTerm: searchTerm,
						type: 'message'
					},
					success: function(response) {
						$messageDetails.html(response);
					},
					error: function(xhr, status, error) {
						console.error(error);
					}
				});
			}, 1000);
		});

		$(document).on('click', '.message-group', function() {
			const $messageBody = $(this).find('.message-body');
			const hasSeeMore = $(this).find('#see_more').length > 0;
			const hasSeeLess = $(this).find('#see_less').length > 0;

			if ($messageBody.html().trim().length > 250) {
				const messageId = $(this).attr('id');
				$.ajax({
					url: 'http://localhost/messages/view/' + messageId,
					type: 'GET',
					data: {
						see_more: hasSeeMore
					},
					success: function(response) {
						$messageBody.html(response);
					},
					error: function(xhr, status, error) {
						console.error(error);
					}
				});
			}
		});

		$(document).on('click', '.message-delete', function() {
			let isConfirmed = confirm('Are you sure you want to delete this message?');
			if (!isConfirmed) {
				return;
			}
			let messageDeleteButton = $(this);

			$.ajax({
				url: 'http://localhost/messages/delete/' + messageDeleteButton.attr('id'),
				type: 'DELETE',
				success: function(response) {
					messageDeleteButton.closest('.message-group').fadeOut(500, function() {
						$(this).remove();
					});

					setTimeout(function() {
						let remainingMessages = $('.message-group').length;
						console.log(remainingMessages);
						if (remainingMessages < 1) {
							window.location.href = 'http://localhost/conversations/add';
						} else {
							show_more_conversation(0);
						}
					}, 600);
				},
				error: function(xhr, status, error) {
					console.error(error);
				}
			});
		});


		$('#messageForm').on('submit', function(event) {
			event.preventDefault();
			const message = $messageInput.val();
			$.ajax({
				url: 'http://localhost/messages/add/' + conversationId,
				type: 'POST',
				data: {
					message: message,
				},
				success: function(response) {
					$(response).hide().prependTo('#messages-conversations').fadeIn(500);
					$messageInput.val('');
					show_more_conversation(0);
				},
				error: function(xhr, status, error) {
					console.error(error);
				}
			});
		});
	});
</script>

<script>
	let conversation_limit = 3;
	const conversationId = <?php echo $conversation_id; ?>;
	const baseUrl = '<?php echo $this->Html->url(['controller' => 'conversations', 'action' => 'view', $conversation_id]); ?>';

	function show_more_conversation(additional = 3) {
		conversation_limit = conversation_limit + additional;
		$.ajax({
			url: baseUrl,
			type: 'GET',
			data: {
				conversation_limit: conversation_limit,
				type: 'conversation'
			},
			success: function(response) {
				//console.log(response);
				$('#conversation_list').html(response);
			},
			error: function(xhr, status, error) {
				console.error(error);
			}
		});
	}
</script>