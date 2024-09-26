<?php if ($counter > 0): ?>
    <div style="overflow-y: auto; max-height: 350px;" class="border p-0" id="messages-conversations">
        <?php foreach ($messages as $message):
            echo $this->element('Message/message_card_parent', ['message' => $message]);
        endforeach; ?>
    </div>
    <?php if ($counter > 5 && $counter > $limit): ?>
        <div class="w-100 text-center mt-2">
            <?php echo $this->Form->button('Show More', [
                'class' => 'btn btn-primary',
                'id' => 'show_more',
                'type' => 'button',
                'onclick' => 'showmore();'
            ]); ?>
        </div>
    <?php endif; ?>
<?php else: ?>
    <div class="list-group-item list-group-item-action text-center">
        <div class="d-flex w-100 justify-content-between mb-0">
            <div class="w-100">
                <h5 class="mb-1">No Messages found</h5>
            </div>
        </div>
    </div>
<?php endif; ?>