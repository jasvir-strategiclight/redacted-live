<!-- Sidebar Nav u-side-nav-opened has-active active  -->
<?php

//$pages = [];
//foreach ($staticPages as $staticPage){
//    $pages[] =     [
//        'label' => $staticPage->page,
//        'controller' => 'Pages',
//        'action' => 'edit/'.$staticPage->id,
//        'icon_class' => 'fa fa-file',
//    ];
//}

$menuItems = [
    [
        'label'            => 'Dashboard',
        'controller'       => 'Admins',
        'action'           => 'dashboard',
        'icon_class'       => 'fa fa-dashboard',
        'default_sub_menu' => false
    ],
    [
        'controller' => 'Users',
        'icon_class' => 'fa fa-user',
        'custom_sub_menu' => [
            [
                'label'      => 'Latest Referrals',
                'controller' => 'Users',
                'action'     => 'referrals',
                'icon_class' => 'fa fa-users',
            ],
            [
                'label' => 'Import CSV',
                'controller' => 'Users',
                'action' => 'importCsv',
                'icon_class' => 'fa fa-upload',
            ]
        ]
    ],
    [
        'controller' => 'Rewards',
        'icon_class' => 'fa fa-trophy',
    ],
//    [
//        'label'            => 'Mail Chimp Settings',
//        'controller'       => 'MailChimp',
//        'icon_class'       => 'fa fa-envelope',
//        'default_sub_menu' => false,
//        'custom_sub_menu'  => [
//            [
//                'label'      => 'New Subscription',
//                'controller' => 'MailChimp',
//                'action'     => 'setting',
//                'slug'       => 'new-subscription',
//                'icon_class' => 'fa fa-check',
//            ],
//            [
//                'label'      => 'Newsletter',
//                'controller' => 'MailChimp',
//                'action'     => 'setting',
//                'slug'       => 'newsletter',
//                'icon_class' => 'fa fa-check',
//            ],
//            [
//                'label'      => '5 Referral Rewards',
//                'controller' => 'MailChimp',
//                'action'     => 'setting',
//                'slug'       => '5-referral-rewards',
//                'icon_class' => 'fa fa-check',
//            ],
//        ]
//    ],
    [
        'label' => 'Schedule Emails',
        'controller' => 'Emails',
        'action' => 'scheduleEmail',
        'icon_class' => 'fa fa-clock-o',
        'default_sub_menu' => false,
        'custom_sub_menu' => [
            [
                'label' => 'Schedule New Email',
                'controller' => 'Admins',
                'action' => 'scheduleEmail',
                'icon_class' => 'fa fa-clock-o',
            ],
            [
                'label' => 'Scheduled Emails',
                'controller' => 'Admins',
                'action' => 'scheduledEmails',
                'icon_class' => 'fa fa-clock-o',
            ],
            [
                'label' => 'Not Seen Emails',
                'controller' => 'Admins',
                'action' => 'notSeenEmails',
                'icon_class' => 'fa fa-eye-slash',
            ],
            [
                'label' => 'New Email Template',
                'controller' => 'EmailTemplates',
                'action' => 'add',
                'icon_class' => 'fa fa-envelope',
            ],
            [
                'label' => 'Email Templates',
                'controller' => 'EmailTemplates',
                'action' => 'index',
                'icon_class' => 'fa fa-envelope',
            ],
            [
                'label' => 'Sponsor Emails',
                'controller' => 'EmailTemplates',
                'action' => 'sponsorEmails',
                'icon_class' => 'fa fa-envelope',
            ],
            
        ]
    ],
    [
        'label' => 'Cron Jobs',
        'controller' => 'Admins',
        'action' => 'crons',
        'icon_class' => 'fa fa-gear',
        'default_sub_menu' => false
    ],
    // [
    //     'label' => 'Pause Campaign',
    //     'controller' => 'Admins',
    //     'action' => 'pauseCampaign',
    //     'icon_class' => 'fa fa-pause-circle',
    //     'default_sub_menu' => false
    // ],

];
?>
<div id="sideNav" class="col-auto u-sidebar-navigation-v1 u-sidebar-navigation box-shadow">
    <!-- hr style="background-color: #7484a8" / -->
    <?php $this->Sidebar->create($menuItems); ?>
</div>
<!-- End Sidebar Nav -->
