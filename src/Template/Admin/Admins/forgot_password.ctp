<?php $this->assign('title', __('Forgot Password')) ?>
<?= $this->Flash->render('auth') ?>

<!-- /.login-logo -->
<div class="login-box-body">
    <p class="login-box-msg"><?= __('Please enter your email to get Reset Password link.')?></p>

    <?= $this->Form->create('') ?>
    <div class="form-group has-feedback">
        <?= $this->Form->control('email', ['class' => 'form-control', 'type' => 'email', 'placeholder' => 'Email', 'label' => false]) ?>
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
    </div>
    <div class="row">
        <!-- /.col -->
        <div class="col-xs-4">
            <?= $this->Form->button(__('Get Reset Password Link'),['class' => 'btn btn-primary btn-flat']); ?>
        </div>
        <!-- /.col -->
    </div>
    <?= $this->Form->end() ?>
    <?= $this->Html->link(__('Login'),['controller' => 'Admins', 'action' => 'login'])?>
    <br>

</div>
<!-- /.login-box-body -->



