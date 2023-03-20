<?php

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User[]|\Cake\Collection\CollectionInterface $users
 */
$this->Heading->create('Users');

$params = [
    'fields' => [
        ['name' => 'email'],
        [
            'name' => 'reference_token',
            'label' => 'Referral Token',
        ],
        [
            'name'       => 'no_of_affiliates',
            'type'       => 'link',
            'url'        => ['controller' => 'Users', 'action' => 'affiliates'],
            'label'      => 'No Of Affiliates',
            'class'      => 'btn-u btn-u-dark-green btn-u-sm rounded get-affiliates',
        ],
        // [
        //     'name' => 'active',
        //     'label' => 'Status',
        //     'type' => 'status',
        //     'model' => 'Users',
        // ],
        [
            'name'          => 'opt_out',
            'label'         => 'Status',
            'type'          => 'status',
            'model'         => 'Users',
            'active_text'   => 'Opt Out',
            'inactive_text' => 'Opt In',
            'reverse' => true,
        ],
        ['name' => 'lead_from',
        'label' => 'Source'],
        ['name' => 'campaign'],
        ['name' => 'created'],
    ],
    'search' => [
        'match'             => [
            'Users' => ['email']
        ],
        'bulk'              => 'select',
        'bulk_field'        => [
            'name'    => 'category',
            'label'   => 'User Category',
            'id'      => 'userCategory',
            'options' => [
                'subscribers'            => 'Subscribers',
                'unsubscribers'          => 'Unsubscribers',
                'affiliated'          => 'Affiliated Users',
                'newsletter subscribers' => 'Sunday Newsletter Subscribers',
                '>3'                     => 'Having 3 or greater affiliates',
                '>5'                     => 'Having 5 or greater affiliates',
                'leads'                     => 'Leads with setting Source and Campaign',
                 'leads_optin'            => 'leads with optin Campaign',
                'leads_optout'           => 'leads with optout Campaign',
            ],
            'value'   => $category,
        ],
        'export'            => true,
        'export_controller' => "Users",
    ]
];

$this->AdminListing->create($params);
?>

<script>
    $(function () {
        var ID = 0;
        $('.get-affiliates').click(function (e) {
            e.preventDefault();
            $('.send-detail-boxes').hide();
            $('#sendDetailBox').fadeIn();
            ID = $(this).attr('data-id');
            var newModal = new Custombox.modal({
                content: {
                    target: '#affiliateModal',
                    positionX: 'center',
                    positionY: 'center',
                    speedIn: false,
                    speedOut: false,
                    fullscreen: false,
                    onClose:function () {
                        $('#userAffiliates').html('Loading .. <i class="fa fa-spinner fa-spin"></i>');
                    }

                }
            });
            $.get(SITE_URL + "admin/users/affiliates/" + ID, function (data) {
                $('#userAffiliates').html(data);
            });

            newModal.open();
        });
    });

</script>
<div id="affiliateModal"
     class="text-left g-color-gray-dark-v1 g-bg-white g-overflow-y-auto  g-pa-20"
     style="display: none; width: 600px; height: auto; max-height: 600px padding: 10%; background-color: #007eef !important; color: #ffffff !important;">
    <button type="button" class="close" onclick="Custombox.modal.close();">
        <i class="hs-icon hs-icon-close"></i>
    </button>
    <h4 class="h4 g-mb-20">Affiliates</h4>
    <div calss="modal-body" style="position: relative;">

        <div class="row send-detail-boxes" id="sendDetailBox">
            <div class="col-md-12" id="userAffiliates">
                Loading .. <i class="fa fa-spinner fa-spin"></i>
            </div>
            <div class="col-md-12">
                <button type="button" class="btn btn-u-dark-blue btn-lg pull-right rounded send-detail mr-4" onclick="Custombox.modal.close();">
                    <i class="fa fa-check"></i> OK
                </button>
            </div>
        </div>
    </div>
    <div class="clear-both"></div>
</div>
