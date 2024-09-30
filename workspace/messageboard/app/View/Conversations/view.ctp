<div class="conversations view">
	<div class="row">
		<div class="col-md-8 d-flex align-items-end">
			<?php
			$image_url = getAvatarURL($recepient['User']['id']);
			echo $this->Html->image(
				$image_url,
				[
					'alt' => 'Profile Image of ' . h($recepient['User']['name']),
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
		'id' => 'message-form'
	]); ?>
	<div class="row pb-0 mb-0">
		<div class="col-md-3 my-0">
			<h2 class="mb-0 text-black" style="color: black !important;"><?php echo __('Message Details'); ?></h2>
		</div>
		<div class="col-md-5 my-0 text-right d-flex">
			<input type="text" placeholder="Search Messages" class="form-control" id="search_message" style="width:100%; margin-top:0 !important;">
			<button class="btn btn-sm btn-secondary ml-1 w-50" id="new-reply" type="button">
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
					'id' => 'message-input',
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
		<div class="col-md-8">
			<div id="message-details" class="p-0">
				<?php echo $this->element('message/message_details'); ?>
			</div>
			<!-- <div class="custom-control custom-switch w-auto">
				<input type="checkbox" class="custom-control-input" id="autorefresh">
				<label class="custom-control-label ml-1" style="width: 300px;" for="autorefresh">Auto Refresh (Every 5 Seconds)</label>
			</div> -->
		</div>
	</div>
</div>
<?php echo $this->element('conversation/conversation_list', ['conversations' => $conversations, 'max' => $max, 'conversation_id' => $conversation_id]); ?>
<script>
	$('#new-reply').on('click', function() {
		$('.reply-message').toggle();
		$(this).toggleClass('btn-secondary btn-danger').text(function(_, text) {
			return text === 'Cancel' ? 'New Reply' : 'Cancel';
		});
	});

	$('#message-form').on('submit', function(event) {
		event.preventDefault();
		const message = $('#message-input').val();
		$.ajax({
			url: api_url + '/addMessage/' + conversation_id,
			type: 'POST',
			data: {
				message: message,
			},
			success: function(response) {
				$(convertToMessageHTML(response)).hide().prependTo('#messages-conversations').fadeIn(500);
				const message_count = $('#messages-conversations').children().length;
				$('#message-input').val('');
				$('#message-input').focus();
				show_more_conversation(0);
			},
			error: function(xhr, status, error) {
				console.error(error);
			}
		});
	});

	let searchTimeout = null;
	let search_counting = 0;
	$('#search_message').on('keyup', function() {
		clearTimeout(searchTimeout);
		const searchTerm = $('#search_message').val().trim();
		searchTimeout = setTimeout(function() {
			$.ajax({
				url: api_url + '/searchMessages/' + conversation_id,
				type: 'GET',
				data: {
					q: searchTerm,
					type: 'message'
				},
				success: function(response) {
					$('#messages-conversations').html('');
					count = search_counting + response.messages.length
					if (count <= response.count && response.count != 0) {
						$('#messages-conversations-container').show();
						$('#messages-conversations').show();
						$('#no-message-found').hide();
						if (response.count > 5) {
							$('#show_more').show();
						} else {
							$('#show_more').hide();
						}
						response.messages.forEach(function(message) {
							$('#messages-conversations').append(convertToMessageHTML(message));
						});
					} else {
						$('#show_more').hide();
						$('#messages-conversations').hide();
						$('#messages-conversations-container').hide();
						$('#no-message-found').show();
					}
				},
				error: function(xhr, status, error) {
					console.error(error);
				}
			});
		}, 1000);
	});

	$(document).ready(function() {
		$(document).on('click', '.message-group', function() {
			const $messageBody = $(this).find('.message-body');
			const hasSeeMore = $(this).find('#see_more').length > 0;
			const hasSeeLess = $(this).find('#see_less').length > 0;

			if ($messageBody.html().trim().length > 250) {
				const messageId = $(this).attr('id');
				$.ajax({
					url: messages_url + '/view/' + messageId,
					type: 'GET',
					data: {
						see_more: hasSeeMore
					},
					success: function(response) {
						if (hasSeeMore) {
							$messageBody.html(response.message + ' <button class="badge badge-warning badge-sm border-0" id="see_less" type="button">See Less</button>');
						} else {
							$messageBody.html(response.truncated + ' <button class="badge badge-primary badge-sm border-0" id="see_more" type="button">See More</button>');
						}
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
				url: messages_url + '/delete/' + messageDeleteButton.attr('id'),
				type: 'DELETE',
				success: function(response) {
					messageDeleteButton.closest('.message-group').fadeOut(500, function() {
						$(this).remove();
					});

					setTimeout(function() {
						let remainingMessages = $('.message-group').length;
						if (remainingMessages < 1) {
							window.location.href = conversation_url + '/add';
						} else {
							show_more_conversation(0);
						}
					}, 700);
				},
				error: function(xhr, status, error) {
					console.error(error);
				}
			});
		});
	});
</script>