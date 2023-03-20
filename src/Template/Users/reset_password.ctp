<?php $this->assign('title', __('Forgot Password')); ?>

<section class="g-bg-gray-light-v5">
    <div class="container g-py-100">
        <div class="row justify-content-center">
            <div class="col-sm-8 col-lg-5">
                <div class="u-shadow-v21 g-bg-white rounded g-py-40 g-px-30">
                    <header class="text-center mb-4">
                        <h2 class="h2 g-color-black g-font-weight-600">Reset Password</h2>
                    </header>
                    
                    <!-- Form -->
                    <?= $this->Form->create('Users', ['url' => ['controller' => 'Users', 'action' => 'resetPasswordApi'], 'class' => 'g-py-', 'id' => 'resetPasswordForm']) ?>
                    <div id="setMessage">
                        <div class="mb-4">
                            <?= $this->Form->input('forgot_password_token', ['type' => 'hidden', 'value' => $forgotPasswordToken, 'required' => false]); ?>
                        </div>
                        
                        <div class="mb-4">
                            <label class="g-color-gray-dark-v2 g-font-weight-600 g-font-size-13">Password:</label>
                            <?= $this->Form->input('password', ['class' => 'form-control g-color-black g-bg-white g-bg-white--focus g-brd-gray-light-v4 g-brd-primary--hover rounded g-py-15 g-px-15', 'label' => false, 'value' => "", 'required' => false, 'autocomplete' => 'off', 'placeholder' => 'New Password']); ?>
                        </div>
                        
                        <div class="mb-4">
                            <label class="g-color-gray-dark-v2 g-font-weight-600 g-font-size-13">Confirm
                                Password:</label>
                            <?= $this->Form->input('Confirm Password', ['name' => 'verify_password', 'id' => 'verify_password', 'class' => 'form-control g-color-black g-bg-white g-bg-white--focus g-brd-gray-light-v4 g-brd-primary--hover rounded g-py-15 g-px-15', 'label' => false, 'type' => 'password', 'autocomplete' => 'off', 'placeholder' => 'Confirm Password']) ?>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <?= $this->Form->button(__('Submit'), ['id' => 'resetPasswordBtn', 'class' => "btn btn-md btn-block u-btn-primary rounded g-py-13"]) ?>
                    </div>
                    
                    <div class="mb-4 text-center">
                        <a href="<?= $this->Url->build(['controller' => 'Users', 'action' => 'login']); ?>">Login</a>
                    </div>
                    <?= $this->Form->end() ?>
                    <!-- End Form -->
                
                </div>
            </div>
        </div>
    </div>
</section>
<!-- End Login -->

<script>
    $(function () {
        
        $("#resetPasswordForm").validate({
            rules: {
                password: {
                    required: true,
                    minlength: 8,
                },
                verify_password: {
                    required: true,
                    equalTo: '#password'
                }
            },
            messages: {
                password: {
                    required: 'Please enter password.',
                    minlength: 'Please enter atleast 8 characters.',
                },
                verify_password: {
                    required: 'Please verify your password.',
                    equalTo: 'Password does not match'
                }
            },
            submitHandler: function (form) {
                $.ajax({
                    url: SITE_URL + "/users/reset-password-api",
                    type: "POST",
                    data: $("#resetPasswordForm").serialize(),
                    dataType: "JSON",
                    success: function (response) {
                        if (response.code == 200) {
                            $('#setMessage').html('<h3>' + response.message + ' <a href="<?= SITE_URL ?>/users/login">Login</a></h3> ');
                            $('#resetPasswordBtn').remove();
                        } else {
                            $().showFlashMessage("error", response.message);
                        }
                    },
                    complete: function () {
                    }
                });
            }
        });
    });
</script>
