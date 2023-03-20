<?= $this->Html->script(['jquery-ui']); ?>
<?php $inputClasses = 'form-control g-color-black g-bg-white g-bg-white--focus g-brd-gray-light-v4 g-brd-primary--hover rounded g-py-15 g-px-15'; ?>
<!-- Login -->
<style>
    .message {
        text-align: center !important;
        position: relative;
        margin: 0;
        color: #000000;
    }
 
</style>
<section class=" mt-0 ">
    <div class="container g-py-10">
        <div class="row justify-content-center g-box-shadow">
            <div class="col-sm-12 col-lg-10">
                <div class="u-shadow-v21 g-bg-white rounded g-py-40 g-px-30">
                    <header class="text-center mb-4">
                        <h2 class="h2 g-color-black g-font-weight-600">Sign Up</h2>
                    </header>
                    <?= $this->Form->create(null, ['url' => ['controller'=>'Users', 'action'=>'register'], 'id' => 'signUpForm', 'class' => "g-py-15"]) ?>
                    <div class="row">
                        <div class="col-xs-12 col-sm-6 mb-4">
                            <label class="g-color-gray-dark-v2 g-font-weight-600 g-font-size-13">First Name:</label>
                            <?= $this->Form->control('first_name', ['class' => $inputClasses, 'label' => false, 'id' => 'FirstName', 'placeholder' => 'First Name']) ?>
                        </div>
                        
                        <div class="col-xs-12 col-sm-6 mb-4">
                            <label class="g-color-gray-dark-v2 g-font-weight-600 g-font-size-13">Last Name:</label>
                            <?= $this->Form->control('last_name', ['class' => $inputClasses, 'label' => false, 'id' => 'LastName', 'placeholder' => 'Last Name']) ?>
                        </div>
                    </div>
                    <?php
                    $classes = "form-control u-select--v3-select u-sibling w-100 u-select--v3 g-pos-rel g-brd-gray-light-v4 g-rounded-4 mb-2";
                    $dropIconClasses = "d-flex align-items-center g-absolute-centered--y g-right-0 g-color-gray-light-v6 g-color-lightblue-v9--sibling-opened g-mr-15";
                    ?>
                    <div class="row">
                        <!-- div class="col-xs-12 col-sm-6">
                            <label class="g-color-gray-dark-v2 g-font-weight-600 g-font-size-13">Years Experience:</label>
                            <?= $this->Form->control('experience_years', ['type'=>'number','class' => $inputClasses, 'label' => false, 'placeholder' => 'Years Experience', 'id' => 'YearsExperience']) ?>
                        </div -->
                        <div class="col-xs-12 col-sm-12 ">
                            <label class="g-color-gray-dark-v2 g-font-weight-600 g-font-size-13">Occupation:</label>
                            
                            <div class="form-group g-pos-rel g-brd-gray-light-v4 g-rounded-4 mb-0">
                                <?= $this->Form->control('occupation', ['type'=>'text','class' => $inputClasses, 'label' => false, 'placeholder' => 'Occupation', 'id' => 'YearsExperience']) ?>
<!--                                <select name="state_id" class="--><?//= $classes; ?><!--" title="Occupation" style="height: 52px !important;" >-->
<!--                                    --><?php //foreach ($occupations as $value => $label) { ?>
<!--                                        <option value="--><?//= $value; ?><!--">-->
<!--                                            --><?//= $label; ?>
<!--                                        </option>-->
<!--                                    --><?php //} ?>
<!--                                </select>-->
                            </div>
                            <label for="apartmentStateId" class="error"></label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-6 mb-4">
                            <label class="g-color-gray-dark-v2 g-font-weight-600 g-font-size-13">Phone:</label>
                            <?= $this->Form->control('phone', ['class' => $inputClasses, 'id' => 'Phone', 'label' => false, 'placeholder' => 'Phone']) ?>
                        </div>
                        <div class="col-xs-12 col-sm-6 mb-4">
                            <label class="g-color-gray-dark-v2 g-font-weight-600 g-font-size-13">Email:</label>
                            <?= $this->Form->control('email', ['class' => $inputClasses, 'label' => false, 'id' => 'Email', 'placeholder' => 'Email']) ?>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-xs-12 col-sm-6 mb-4">
                            <label class="g-color-gray-dark-v2 g-font-weight-600 g-font-size-13">Password:</label>
                            <?= $this->Form->control('password', ['class' => $inputClasses, 'label' => false, 'id' => 'Password', 'placeholder' => 'Password']) ?>
                        </div>
                        
                        <div class="col-xs-12 col-sm-6 mb-4">
                            <label class="g-color-gray-dark-v2 g-font-weight-600 g-font-size-13">Confirm
                                Password:</label>
                            <?= $this->Form->control('confirm_password', ['type' => 'password', 'class' => $inputClasses, 'label' => false, 'placeholder' => 'Confirm Password', 'id' => 'ConfirmPassword']) ?>
                        </div>
                    </div>
                    
                    <div class="row justify-content-between mb-5">
                        <div class="col-8 align-self-center">
                            <label class="form-check-inline u-check g-color-gray-dark-v5 g-font-size-13 g-pl-25">
                                
                                <input name="i_accept" class="g-hidden-xs-up g-pos-abs g-top-0 g-left-0" type="checkbox"
                                       id="apartmentIAccept">
                                <div class="u-check-icon-checkbox-v6 g-absolute-centered--y g-left-0">
                                    <i class="fa" data-check-icon="&#xf00c"></i>
                                </div>
                                I accept the <a
                                    href="<?= $this->Url->build(['controller' => 'Pages', 'action' => 'termsAndConditions']); ?>"
                                    target="_blank">
                                    Terms and Conditions
                                </a>
                            </label>
                            <br/>
                            <label for="i_accept" class="error" style="display: none">Please accept terms and
                                conditions.</label>
                        </div>
                        <div class="col-4 align-self-center text-right">
                            <?= $this->Form->button(__('Sign Up'), ['id' => 'RegisterBtn', 'class' => "btn btn-md u-btn-primary rounded g-py-13 g-px-25 g-font-weight-600"]) ?>
                        </div>
                    </div>
                    <?= $this->form->end(); ?>
                    <!-- End Form -->
                    
                    <footer class="text-center">
                        <p class="g-color-gray-dark-v5 g-font-size-13 mb-0">I already have an account? <a
                                class="g-font-weight-600"
                                href="<?= $this->Url->build(['controller' => 'Users', 'action' => 'login']); ?>">Sign In</a>
                        </p>
                    </footer>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- End Login -->

<script>
    $(document).ready(function () {
        
        $.validator.addMethod("pwcheck", function (value) {
        return /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).*$/.test(value) // consists of only these
            &&
            /[A-Z]/.test(value) // has a uppercase letter
            &&
            /\d/.test(value); // has a digit
    }, "Password must contain atleast one capital character and one numeric.");
    
        $('#Phone').usPhoneFormat();
    
        $('.js-select').selectpicker();
        
        $("#signUpForm").validate({
            ignore: ":hidden:not(#apartmentIAccept)",
            rules: {
                first_name:{
                    required: true,
                    maxlength:50,
                },
                last_name:{
                    required: true,
                    maxlength:50,
                },
                experience_years:{
                    required: true,
                    max:50,
                    min:0,
                },
                occupation_id:{
                    required: true
                },
                phone: {
                    required: true
                },
                email: {
                    required: true,
                    email: true,
                    remote: SITE_URL + '/users/isUniqueEmail'
                },
                password: {
                    required: true,
                minlength: 8,
                pwcheck: true
                },
                confirm_password:{
                    required: true,
                    equalTo: "#Password"
                },
                i_accept: {
                    required: true
                }
            },
            messages: {
                first_name: {
                    required: "Please enter first name.",
                    maxlength:"First name mus be less than 50 characters.",
                },
                last_name: {
                    required: "Please enter last name.",
                    maxlength:"Last name mus be less than 50 characters.",
                },
                email: {
                    required: "Please enter email.",
                    email: "Please enter valid email.",
                    remote: "Email already exists"
                },
                password: {
                    required: "Please enter password.",
                    minlength: "Password must be greater than 8 characters",
                    pwcheck: "Password must contain atleast one capital character and one numeric."
                },
                confirm_password: {
                    required: "Please confirm password.",
                    equalTo: "Password does not match."
                },
                phone: {
                    required: "Please enter phone number."
                },
                experience_years: {
                    required: "Please enter years experience."
                },
                occupation_id: {
                    required: "Please select city name."
                },
                i_accept: {
                    required: "Please accept terms and conditions."
                }
            }
        });
        
    });
</script>


