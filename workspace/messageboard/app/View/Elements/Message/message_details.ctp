<div style="overflow-y: auto; max-height: 350px;" class="border p-0" id="messages-conversations-container">
    <div id="messages-conversations" class="p-0 m-0">
        <?php foreach ($messages as $message):
            echo $this->element('Message/message_card_parent', ['message' => $message]);
        endforeach; ?>
    </div>
    <div class="w-100 text-center p-0 mb-0" id="show_more" style="display: <?php echo ($counter > 5 && $counter > $limit) ? 'block' : 'none'; ?>;">
        <?php echo $this->Form->button('Show More', [
            'class' => 'btn btn-primary p-1 w-100',
            'type' => 'button',
            'onclick' => 'show_more_messages();',
        ]); ?>
    </div>
</div>
<div class="list-group-item list-group-item-action text-center" id="no-message-found" style="display: none;">
    <div class="d-flex w-100 justify-content-between mb-0">
        <div class="w-100">
            <h5 class="mb-1">No Messages found</h5>
        </div>
    </div>
</div>
<!-- <div class="w-100 text-center mt-2 mb-0 d-flex justify-content-between">
    <?php echo $this->Form->button('Refresh Messages', [
        'class' => 'btn btn-info',
        'type' => 'button',
        'onclick' => 'refresh_messages();',
    ]); ?>
</div> -->