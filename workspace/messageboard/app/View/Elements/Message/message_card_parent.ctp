<?php $position = AuthComponent::user('id') == $message['Message']['user_id'] ? 'right' : 'left'; ?>
<div class="list-group-item list-group-item-action text-<?php echo $position; ?> message-group mb-0" id="<?php echo $message['Message']['id']; ?>">
    <div class="d-flex w-100 justify-content-between mb-0 p-0">
        <?php
        if ($position == 'right'):
            echo $this->element('Message/message_card', ['message' => $message]);
        endif;

        $image_url = getAvatarURL($message['User']['id']);
        echo $this->Html->image(
            $image_url,
            [
                'alt' => 'Profile Image of ' . h($message['User']['name']),
                'class' => 'img-fluid rounded-square',
                'id' => 'profileImage',
                'style' => 'width: 50px; max-height: 100px;'
            ]
        );

        if ($position == 'left'):
            echo $this->element('Message/message_card', ['message' => $message]);
        endif;
        ?>
    </div>
    <small class="my-1" style="font-size: 10px;"><?php echo formatDate($message['Message']['created']); ?></small>
    &nbsp;
    <?php if ($position == 'right'): ?>
        <?php echo $this->Form->button(
            'Delete',
            [
                'class' => 'message-delete border-0 badge badge-danger badge-sm text-decoration-none my-1',
                'confirm' => 'Are you sure you want to delete this message?',
                'escape' => false,
                'type' => 'button',
                'id' => $message['Message']['id']
            ]
        ); ?>
    <?php endif; ?>
</div>