<div class="form">
    <fieldset>
        <legend><?php echo __('Login'); ?></legend>
        <?php
        echo $this->Form->create('User');
        echo $this->Form->input('email');
        echo $this->Form->input('password');
        echo $this->Form->end('Login');
        ?>
    </fieldset>
    <h1>No Account Yet? <?php echo $this->HTML->link('Register Here', '/register'); ?></h1>
</div>