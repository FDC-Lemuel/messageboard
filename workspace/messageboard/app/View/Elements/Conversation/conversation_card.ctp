<div style="max-height:350px; overflow-y:auto;">
    <?php
    foreach ($conversations as $key => $conversation):
        $receiver = getUserById($conversation['Receiver']['user_id']);
        $image_url =  getAvatarURL($receiver['User']['id']);
    ?>
        <a href="<?php echo $this->Html->url(array('controller' => 'conversations', 'action' => 'view', $conversation['Conversation']['id'])); ?>"
            class="list-group-item list-group-item-action border <?php echo isset($conversation_id) && $conversation_id == $conversation['Conversation']['id'] ? 'active-conversation' : ''; ?>">
            <div class="d-flex w-100  justify-content-start">
                <?php echo $this->Html->image(
                    $image_url,
                    [
                        'alt' => "Profile Image of " . h($receiver['User']['name']),
                        'class' => 'img-fluid rounded-square',
                        'id' => "profileImage",
                        'style' => 'width: 50px;  margin-right: 10px;'
                    ]
                ); ?>
                <div>
                    <b class="mb-1"><?php echo $receiver['User']['name']; ?></b>
                    <p class="mb-1"><?php echo truncateWithEllipsis($conversation['LastMessage']['message']) ?></p>
                </div>
            </div>
            <small><?php echo formatDate($conversation['LastMessage']['modified']); ?></small>
        </a>
    <?php endforeach; ?>
</div>

<?php if (count($conversations) < $max): ?>
    <li class="list-group-item list-group-item-action border mt-2">
        <?php echo $this->Form->button(__('Show More'), [
            'class' => 'btn btn-sm text-center w-100',
            'id' => 'show_more_conversations',
            'type' => 'button',
            'onclick' => 'show_more_conversation();'
        ]); ?>
    </li>
<?php endif; ?>