<?php if ($counter > 0): ?>
    <div style="overflow-y: auto; max-height: 350px;" class="border p-0" id="messages-conversations">
        <?php foreach ($messages as $message):
            echo $this->element('Message/message_card_parent', ['message' => $message]);
        endforeach; ?>
    </div>
    <div class="w-100 text-center mt-2 mb-0 d-flex justify-content-between">
        <?php echo $this->Form->button('Refresh Messages', [
            'class' => 'btn btn-info',
            'id' => 'refresh-messages',
            'type' => 'button',
            'onclick' => 'show_more_conversation(0);showmore(0, false);'
        ]); ?>

        <?php if ($counter > 5 && $counter > $limit):
            echo $this->Form->button('Show More', [
                'class' => 'btn btn-primary',
                'id' => 'show_more',
                'type' => 'button',
                'onclick' => 'showmore();'
            ]);
        endif; ?>
    </div>

<?php else: ?>
    <div class="list-group-item list-group-item-action text-center">
        <div class="d-flex w-100 justify-content-between mb-0">
            <div class="w-100">
                <h5 class="mb-1">No Messages found</h5>
            </div>
        </div>
    </div>
<?php endif; ?>