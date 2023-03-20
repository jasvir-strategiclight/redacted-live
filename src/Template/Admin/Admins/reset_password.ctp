<?php $this->assign('title', __('Reset Password')) ?>
<?= $this->Flash->render('auth') ?>

<!-- /.login-logo -->
<div class="login-box-body">
    <?php if (isset($isValidPasswordToken)) { ?>
        <p class="login-box-msg"><?= __('Please enter new password.') ?></p>
        <?= $this->Form->create('') ?>
        <div class="form-group has-feedback">
            <?= $this->Form->control('password', ['class' => 'form-control', 'placeholder' => 'Password', 'label' => false]) ?>
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        </div>
        <div class="form-group has-feedback">
            <?= $this->Form->control('confirm_password', ['class' => 'form-control', 'placeholder' => 'Confirm Password', 'type' => 'password', 'label' => false]) ?>
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        </div>
        <div class="row">
            <!-- /.col -->
            <div class="col-xs-4">
                <?= $this->Form->button(__('Submit'), ['class' => 'btn btn-primary btn-flat']); ?>
            </div>
            <!-- /.col -->
        </div>
        <?= $this->Form->end() ?>
        <?= $this->Html->link(__('Login'), ['controller' => 'Admins', 'action' => 'login']) ?>
        <br>
    <?php } else { ?>
        <p class="text-yellow"><?= __('Forgot password token has been expired. Please click on the following link to get reset password token again.')?></p>
        <?= $this->Html->link(__('Get Reset Password Token'), ['controller' => 'Admins', 'action' => 'forgotPassword'],['class' => 'btn btn-default']) ?>
    <br>
    
    <?php } ?>

</div>
<!-- /.login-box-body -->



