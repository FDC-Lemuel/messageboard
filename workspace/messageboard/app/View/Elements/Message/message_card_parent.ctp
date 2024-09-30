<?php $position = AuthComponent::user('id') == $message['Message']['user_id'] ? 'right' : 'left'; ?>
<div class="message-group border-0 list-group-item list-group-item-action mb-0 p-0 text-<?php echo $position; ?> <?php echo $position == 'left' ? 'pl-0' : 'pr-0'; ?>" data-message-id="<?php echo $message['Message']['id']; ?>" style='margin-top:1px;'>
    <div class="mb-0 w-100 bg-<?php echo $position == 'left' ? 'secondary' : 'info'; ?> text-white mb-0">
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
                    'style' => 'width: 50px; max-height: 80px;'
                ]
            );

            if ($position == 'left'):
                echo $this->element('Message/message_card', ['message' => $message]);
            endif;
            ?>
        </div>
        <div class="my-0 py-0" style="font-size: 10px;"><?php echo formatDate($message['Message']['created']); ?>
            <?php if ($position == 'right'): ?>
                <?php echo $this->Form->button(
                    'Delete',
                    [
                        'class' => 'message-delete border-0 badge badge-danger badge-sm text-decoration-none mt-1',
                        'style' => 'cursor:pointer; font-size: 10px;',
                        'confirm' => 'Are you sure you want to delete this message?',
                        'escape' => false,
                        'type' => 'button',
                        'id' => $message['Message']['id']
                    ]
                ); ?>
            <?php endif; ?>
        </div>
    </div>
</div>