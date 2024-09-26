<?php
if ($see_more):
    echo h($message['Message']['message']) . ' ';
    echo $this->Form->button(
        'Show Less',
        [
            'class' => 'badge badge-warning badge-sm border-0',
            'id' => 'see_less',
            'type' => 'button',
        ]
    );
else:
    echo truncateWithEllipsis($message['Message']['message'], 250) . ' ';
    echo $this->Form->button(
        'Show More',
        [
            'class' => 'badge badge-primary badge-sm border-0',
            'id' => 'see_more',
            'type' => 'button',
        ]
    );
endif;
