<?php $this->assign('title', __('Forgot Password')); ?>

<section class="g-bg-gray-light-v5">
    <div class="container g-py-100">
        <div class="row justify-content-center">
            <div class="col-sm-8 col-lg-5">
                <div class="u-shadow-v21 g-bg-white rounded g-py-40 g-px-30">
                    <header class="text-center mb-4">
                        <h2 class="h2 g-color-black g-font-weight-600">Forgot Password</h2>
                    </header>
                     <div class="login-input-row">
                    <!-- Form -->
                    <?= $this->Form->create('Users', ['url' => ['controller' => 'Users', 'action' => 'forgotPassword'], 'class' => 'g-py-15', 'id' => 'forgotPasswordForm']) ?>
                    
                    <div class="mb-4">
                        <p>Enter your email address that you used to register. We'll send you an email with a
                            link to reset your password.</p>
                    </div>
                    
                    <div class="mb-4">
                        <label class="g-color-gray-dark-v2 g-font-weight-600 g-font-size-13">Email:</label>
                        <?= $this->Form->control('email', ['class' => 'form-control g-color-black g-bg-white g-bg-white--focus g-brd-gray-light-v4 g-brd-primary--hover rounded g-py-15 g-px-15', 'type' => 'text', 'label' => false, 'placeholder' => 'Email']) ?>
                        <label class="error" id="emailErrorMessage" style="display: none;"></label>
                    </div>
                    
                    
                    <div class="mb-4">
                        <?= $this->Form->button(__('Submit'), ['id' => 'submitBtn', 'class' => "btn btn-md btn-block u-btn-primary rounded g-py-13"]) ?>
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
    </div>
</section>
<!-- End Login -->

<script>
    $(function () {
        
        $("#forgotPasswordForm").validate({
            rules: {
                email: {
                    required: true,
                    email: true
                }
            },
            messages: {
                email: {
                    required: 'Please enter email.',
                    minlength: 'Please enter valid email.',
                }
            },
            submitHandler: function (form) {
                $('#emailErrorMessage').hide();
                $.ajax({
                    url: SITE_URL + "users/forgot-password-api",
                    type: "POST",
                    data: $("#forgotPasswordForm").serialize(),
                    dataType: "json",
                    beforeSend:function(){
                        $("#submitBtn").attr('disabled','disabled');
                        $("#submitBtn").html('<i class="fa fa-spin fa-spinner"></i> please wait...');
                    },
                    success: function (response) {
                        if (response.code == 200) {
                            $('.login-input-row').html('<h3>' + response.message + '</h3>');
                            $('#resetPasswordBtn, .main-instruction').remove();
                        } else {
                            $('#emailErrorMessage').html(response.message).fadeIn();
                            $("#submitBtn").find(":input").filter(function () {
                                return !this.value;
                            }).removeAttr("disabled");
                        }
                    },
                    complete: function () {
                    }
                });
                
                $('#resetPasswordBtn').button('loading');
                $("#submitBtn").find(":input").filter(function () {
                    return !this.value;
                }).attr("disabled", "disabled");
                
                return false;
            }
        });
        
        
    });
</script>
