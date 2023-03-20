<?= $this->Html->css([
        'vendor/custombox/custombox.min',
        
    ]) ?>
<?php
//pr($authUser);
//die;

$referralUrl = AFFILIATE_URL . $authUser['reference_token'];
$maxReferralRequired = 0;
$product = "NA";
?>
<style>
    div#socialSharing a span.fa-lg {
        border-radius: 50%;
        margin: 1%;
        color: #FFFFFF;
    }

    div#socialSharing a span.fa-lg i {
        font-style: normal;
    }

    div#socialSharing a span#facebook {
        background-color: #3b5998;
    }

    div#socialSharing a span#facebook:hover {
        background-color: #133783;
    }

    div#socialSharing a span#twitter {
        background-color: #1da1f2;
    }

    div#socialSharing a span#twitter:hover {
        background-color: #2582bb;
    }

    div#socialSharing a span#linkedin {
        background-color: #0077b5;
    }

    div#socialSharing a span#linkedin:hover {
        background-color: #02689d;
    }

    .bg-primary, .btn-primary {
        background-color: #282561 !important;
    }
</style>
<div class="row">
    <div class="col-md-1">&nbsp;</div>
    <div class="col-md-10">
        <div class="row">
            <div class="col-md-12 g-pb-25">
                <h2>Share <?= SITE_TITLE; ?> <i class="fa fa-arrow-right"></i> Earn Rewards </h2>
            </div>

            <div class="col-md-12 box-shadow g-rounded-5 g-pa-40--lg">
                <div class="row">
                    <?php foreach ($rewards as $s) { ?>
                        <div class="col-md-2 bg-primary g-py-10 text-center box-shadow g-rounded-3 ml-4">
                            <div style="height: 120px; ">
                                <img src="<?= SITE_URL . $s->image->small_thumb; ?>"
                                     style="max-height: 120px; max-width: 80px">
                            </div>
                            <h6 class="text-center g-color-white"><?= $s->name; ?></h6>
                            <h3 class="text-center g-color-white text-bold"><?= $s->no_of_affiliate_required; ?></h3>
                        </div>
                        <?php
                        if (($authUser['no_of_affiliates'] < $s->no_of_affiliate_required) && $maxReferralRequired <= 0) {
                            $maxReferralRequired = $s->no_of_affiliate_required;
                            $product = $s->name;
                        }
                        ?>
                    <?php } ?>
                </div>
            </div>

            <?php $referralRequired = $maxReferralRequired - $authUser['no_of_affiliates']; ?>

            <div class="col-md-12 g-pa-40--lg">
                <div class="row">
                    <div class="col-md-3 bg-primary g-rounded-50 g-pa-10">
                        <span class="g-color-white g-pl-20 pull-left g-mt-10"><b>Your Referral Count</b></span>
                        <span class="g-color-primary  bg-white g-rounded-50 g-px-20 g-py-10 pull-right text-center"><b><?= $authUser['no_of_affiliates']; ?></b></span>
                    </div>
                    <div class="col-md-9 g-pa-10 g-pl-30">
                        <?php if ($product == "NA") { ?>
                            Congratulations! You have achieved all your Rewards.
                        <?php } else { ?>
                            You're only <b><?= $referralRequired; ?>
                                referrals </b> away from receiving <br/>

                            <b><?= $product; ?>!</b>
                        <?php } ?>
                    </div>
                </div>
            </div>

            <?php if (
                $authUser['no_of_affiliates'] >= 5 && (
                    empty($authUser['first_name'])
                    || empty($authUser['address'])
                    || empty($authUser['city'])
                    || empty($authUser['zip'])
                )) { ?>
                <?php
                $params = [
                    'object' => $authUser,
                    'form'   => [
                        'options' => [
                            'url'        => ['controller' => 'Users', 'action' => 'edit', $authUser['id']],
                            'type'       => 'post',
                            'novalidate' => true,
                            'id'         => 'editUserForm',

                        ],
                        'heading' => 'Please provide your details to claim the Reward.',
                        'cancel'  => false,
                        'submit'  => [
                            'label' => 'Claim Reward',
                            'icon'  => 'fa fa-check',
                        ],
                    ],
                    'fields' => [
                        ['name' => 'first_name'],
                        ['name' => 'last_name'],
                        ['name' => 'address'],
                        ['name' => 'city'],
                        ['name' => 'state'],
                        ['name' => 'zip'],
                        [
                            'name'    => 'country',
                            'type'    => 'select',
                            'options' => array_combine(array_values($countries), array_values($countries))
                        ],
                    ],
                ];

                $this->CustomForm->create($params);

                ?>
            <?php } else { ?>
                <div class="col-md-12 g-pa-40--lg">
                    <div class="row">
                        <div class="col-md-12">
                            <h3>Share your link</h3>
                            <p>Rack up referrals by sharing your personal referral link with others:</p>
                        </div>
                        <div class="col-md-8">
                            <input id="sharableLink" value="<?= $referralUrl; ?>"
                                   class="g-rounded-5 form-control g-bg-white" style="height: 52px;"
                                   readonly="readonly">
                        </div>
                        <div class="col-md-4">
                            <button class="btn btn-primary btn-lg g-rounded-5" style="height: 52px" onclick="copyMe();">
                                <i
                                        class="fa fa-copy"></i> Copy
                            </button>
                        </div>
                    </div>
                </div>

                <div class="col-md-12 g-pa-40--lg">
                    <form id="sendViaEmail" action="javascript:void(0);">
                        <input type="hidden" name="token" value="<?= $authUser['reference_token']; ?>">
                        <div class="row">
                            <div class="col-md-12">
                                <h3>Share via email</h3>
                                <p>Invite people to subscribe to <?= SITE_TITLE; ?> by entering in their emails. (We'll
                                    automatically
                                    add your referral link!) </p>
                            </div>
                            <div class="col-md-8">
                                <input id="shareWith" value="" name="share_with"
                                       placeholder="To: (enter contact's email)"
                                       class="g-rounded-5 form-control g-bg-white" style="height: 52px;">
                                <span class="g-font-size-11">Separate multiple emails with commas.</span>
                            </div>
                            <div class="col-md-4">
                                <button class="btn btn-primary btn-lg g-rounded-5" style="height: 52px"
                                        onclick="auth();"><i
                                            class="fa fa-address-book"></i> Add From Google Contacts
                                </button>
                            </div>

                            <div class="col-md-8 mt-4">
                        <textarea class="form-control" name="message" style="height: 120px;">Hey, I highly recommend giving the <?= SITE_TITLE; ?>  newsletter a read. It's an awesome daily email that delivers the top business news in a way that's informative and entertaining. Best of all, it's free, and only takes 5 minutes to read each morning. Give it a try and subscribe using my personal invite link below:
                        </textarea>
                            </div>
                            <div class="col-md-4">
                                &nbsp;
                            </div>

                            <div class="col-md-12 mt-4">
                                <button class="btn btn-primary btn-lg g-rounded-5" style="height: 52px"
                                        id="shareViaEmailBtn">
                                    <i class="fa fa-send"></i> Send The Invite
                                </button>
                                <br/>
                                <h4 id="shareViaEmailMsg" class="g-color-green" style="display: none"></h4>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="col-md-12 g-pa-40--lg">
                    <div class="row">
                        <div class="col-md-12">
                            <h3>Share on social</h3>
                            <p>Rack up referrals by sharing your personal referral link to your network: </p>
                        </div>
                        <div class="col-md-8">
                            <div id="socialSharing">
                                <a href="http://www.facebook.com/sharer.php?u=<?= $referralUrl; ?>">
        <span id="facebook" class="fa-stack fa-lg">
            <i class="fa fa-facebook fa-stack-1x"></i>
        </span>
                                </a>
                                <a href="http://twitter.com/share?text=<?= SITE_TITLE; ?>&url=<?= $referralUrl; ?>&hashtags=MorningInvest">
        <span id="twitter" class="fa-stack fa-lg">
            <i class="fa fa-twitter fa-stack-1x"></i>
        </span>
                                </a>

                                <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?= $referralUrl; ?>&title=<?= SITE_TITLE; ?>&source=[SITE_NAME]">
        <span id="linkedin" class="fa-stack fa-lg">
            <i class="fa fa-linkedin fa-stack-1x"></i>
        </span>
                                </a>

                            </div>

                        </div>

                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
    <div class="col-md-1">&nbsp;</div>
</div>

<script src="https://apis.google.com/js/client.js"></script>
<script>
    function auth() {
        var config = {
            'client_id': '697750116346-c3il4i0ojchub17upqbroa9fp3ra567o.apps.googleusercontent.com',
            'scope': 'https://www.googleapis.com/auth/contacts.readonly'
        };
        gapi.auth.authorize(config, function () {
			//console.log(gapi.auth.getToken());
            fetch(gapi.auth.getToken());
        });
    }

    function fetch(token) {
        token['max-results'] = 500;
        $.ajax({
            url: 'https://www.google.com/m8/feeds/contacts/default/full?alt=json',
            dataType: 'json',
            data: token
        }).done(function (data) {
            console.log(data.feed.entry);
            if (data.feed.entry.length > 0) {
                var contact = '<div class="col-md-1 mt-3"><input type="checkbox" name="contacts[]" class="form-control google-contact" value="[email1]" [checked]></div><div class="col-md-11 mt-2"><label><b>[email]</b></label></div>';
                var contacts = [];

                var shareWithContacts = [];
                if ($('#shareWith').val().length > 0) {
                    shareWithContacts = $('#shareWith').val().split(",");
                }

                $.each(data.feed.entry, function (ind, ent) {
                    if (typeof  ent.gd$email != "undefined") {
                        c = contact.replace("[email1]", ent.gd$email[0].address)
                        c = c.replace("[email]", ent.gd$email[0].address)

                        if ($.inArray(ent.gd$email[0].address, shareWithContacts) == -1) {
                            c = c.replace("[checked]", "")
                        } else {
                            c = c.replace("[checked]", "checked")
                        }
                        contacts.push(c);
                    }
                });

                $('#googleContacts').html(contacts.join("\n"));

                var newModal = new Custombox.modal({
                    overlay: {
                        close: false
                    },
                    content: {
                        target: "#contactModal",
                        positionX: "center",
                        positionY: "center",
                        speedIn: 300,
                        speedOut: 300,
                        fullscreen: false,
                        onClose: function () {
                            //
                        }
                    }
                });
                newModal.open();
            }
        });
    }

    $(function () {
        $('#chooseContactBtn').click(function () {

            var contacts = [];
            if ($('#shareWith').val().length > 0) {
                contacts = $('#shareWith').val().split(",");
            }

            $('.google-contact:checked').each(function () {


                if ($.inArray($(this).val(), contacts) == -1) {
                    contacts.push($(this).val());
                }
            });

            $('#shareWith').val(contacts.join(","));
            Custombox.modal.close();
        });

        $('#socialSharing a').click(function (e) {
            e.preventDefault();
            //console.log($(this).attr('href')); return false;
            window.open($(this).attr('href'), '_blank', 'location=no,height=570,width=520,scrollbars=yes,status=yes');

        });

        $('#sendViaEmail').validate({
            rules: {
                share_with: {
                    required: true,
                },
                message: {
                    required: true,
                }
            },
            messages: {
                share_with: {
                    required: "Please enter email(s)",
                },
                message: {
                    required: "Please enter message",
                }

            },
            submitHandler: function () {
                $('#shareViaEmailMsg').hide();
                $.ajax({
                    url: SITE_URL + 'users/share-via-email',
                    type: "POST",
                    data: $('#sendViaEmail').serialize(),
                    dataType: "json",
                    beforeSend: function () {
                        $('#shareViaEmailBtn').html('Sending ... <i class="fa fa-spinner fa-spin"></i>');
                    },
                    success: function (resp) {
                        $('#shareViaEmailBtn').html('<i class="fa fa-send"></i> Send The Invite');
                        $('#shareViaEmailMsg').html(resp.message).fadeIn();
                    }
                });
            }
        });
    });

    function copyMe() {
        /* Get the text field */
        var copyText = document.getElementById("sharableLink");

        /* Select the text field */
        copyText.select();
        copyText.setSelectionRange(0, 99999);
        /*For mobile devices*/

        /* Copy the text inside the text field */
        document.execCommand("copy");

    }
</script>

<div id="contactModal" class="text-left u-shadow-v1-5 g-bg-white g-overflow-y-auto  g-pa-20"
     style="max-height: 600px; max-width: 800px; display: none; width: 100%;  min-width: 400px; min-height: auto; ">
    <button type="button" class="close" onclick="Custombox.modal.close();">
        <i class="hs-icon hs-icon-close"></i>
    </button>
    <h4 class="g-mb-20 g-color-primary">Contacts</h4>
    <div calss="modal-body" id="chooseAmenitiesPage" style="position: relative;">
        <div class="row" id="googleContacts"></div>
        <br/><br/>
        <div class="row">
            <div class="col-md-12 text-left">
                <button type="submit" class="btn btn-primary btn-lg pull-right g-bg-primary"
                        id="chooseContactBtn">
                    <i class="fa fa-check"></i> Choose
                </button>
            </div>
        </div>
    </div>
    <div class="clear-both"></div>
</div>
