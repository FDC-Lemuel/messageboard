<div class="conversations form">
	<?php echo $this->Form->create('Conversation'); ?>
	<fieldset>
		<div class="form-group row py-0">
			<div class="col-md-7 offset-1">
				<legend><?php echo __('New Message'); ?></legend>
			</div>
		</div>
		<div class="form-group row py-0">
			<label for="name" class="col-md-1 text-right col-form-label">Send to: </label>
			<div class="col-md-7">
				<?php
				echo $this->Form->input('receiver_id', [
					'id' => 'select2js',
					'type' => 'select',
					'options' => $users,
					'label' => false,
					'class' => 'form-control',
					'empty' => 'Select a recipient',
					'required' => true
				]);
				?>
			</div>
		</div>
		<div class="form-group row py-0">
			<label for="name" class="col-md-1 text-right col-form-label">Message: </label>
			<div class="col-md-7">
				<?php
				echo $this->Form->textarea('message', ['rows' => 5, 'placeholder' => 'Enter Message', 'class' => 'form-control', 'required' => true]);
				?>
			</div>
		</div>
		<div class="form-group row py-0">
			<div class="col-md-7 offset-1">
				<?php echo $this->Form->end(__('Send Message')); ?>
			</div>
		</div>
	</fieldset>
</div>
<?php echo $this->element('conversation/conversation_list', ['conversations' => $conversations, 'max' => $max]); ?>
<script>
	$(document).ready(function() {
		function formatState(state) {
			console.log(state.element);
			if (!state.id) {
				return state.text;
			}
			var baseUrl = "/messageboard/img/uploads";
			var $state = $(
				'<span><img src="' + baseUrl + '/' + state.id + '? <?php echo time(); ?>" style="width:50px;" /> ' + state.text + '</span>'
			);
			return $state;
		};

		$('#select2js').select2({
			templateResult: formatState,
			placeholder: {
				id: '-1',
				text: "Select a state"
			},
			allowClear: true
		});
	});
</script>