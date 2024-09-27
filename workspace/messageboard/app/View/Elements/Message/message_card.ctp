<div class="w-100 mb-0">
    <h5 class="mb-1"><?php echo $message['User']['name']; ?></h5>
    <div class="message-body text-truncate d-inline-block mb-0 px-0 w-100" style="word-break: break-word; text-overflow: ellipsis; max-width:100%; white-space:unset">
        <?php echo truncateWithEllipsis($message['Message']['message'], 250, true); ?>
    </div>
</div>