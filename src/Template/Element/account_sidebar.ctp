<!-- Profile Settings -->
<?php
$menuItems = [
    'Users_hepBuilder' => '',
    'Users_profile' => '',
    'Exercises_index' => '',
    'Exercises_add' => '',
    'Routines_index' => '',
    'Routines_add' => '',
    'Exercises_favorites' => '',
    'Subscription_index' => ''

];

$menuItems[$this->request->getParam('controller') . "_" . $this->request->getParam('action')] = 'active';

$menuItems = [
    [
        'label' => 'Hep Builder',
        'controller' => 'Users',
        'action' => 'hepBuilder',
        'icon_class' => 'fa fa-dashboard',
        'default_sub_menu' => false
    ],
    
    [
        'controller' => 'Categories',
        'icon_class' => 'fa fa-list-ul',
    ],
//    [
//        'controller' => 'Exercises',
//        'icon_class' => 'fa fa-child',
//    ],
    
    [
        'controller' => 'Programs',
        'icon_class' => 'fa fa-superpowers',
    ],
    [
        'label' => 'Favorite Exercises',
        'controller' => 'FavoriteExercises',
        'action' => 'index',
        'icon_class' => 'fa fa-heart',
        'default_sub_menu' => false
    ],
    [
        'label' => 'Subscriptions',
        'controller' => 'Users',
        'action' => 'subscriptions',
        'icon_class' => 'fa fa-credit-card',
        'default_sub_menu' => false
    ],
    [
        'label' => 'Profile',
        'controller' => 'Users',
        'action' => 'profile',
        'icon_class' => 'fa fa-user',
        'default_sub_menu' => false
    ],
    [
        'label' => 'Change Password',
        'controller' => 'Users',
        'action' => 'changePassword',
        'icon_class' => 'fa fa-ellipsis-h',
        'default_sub_menu' => false
    ],
    [
        'label' => 'Sign Out',
        'controller' => 'Users',
        'action' => 'logout',
        'icon_class' => 'fa fa-sign-out',
        'default_sub_menu' => false
    ],

];
?>
<div class="col-lg-2 g-mb-50 p-0" style="">
    <aside class="g-brd-around g-brd-gray-light-v4 rounded g-px-20 g-py-30" >
        <!-- Profile Picture -->
        <div class="text-center g-pos-rel g-mb-30" >
            <div class="g-width-100 g-height-100 mx-auto mb-3">
                <?php $image = SITE_URL . (($Auth->user('image')) ? $Auth->user('image')->small_thumb : 'files/images/default.png'); ?>
                <img src="<?= $image; ?>" alt=""
                     class="img-fluid rounded-circle" style="width: 100px; height: 100px;">
            </div>
            <span
                class="d-block g-font-weight-500"><?= $Auth->user('first_name') . ' ' . $Auth->user('last_name'); ?></span>
        </div>
        <!-- End Profile Picture -->
        
        <hr class="g-brd-gray-light-v4 g-my-30">
        
        <div id="sideNav" class="col-auto u-sidebar-navigation-v1 p-0" style="    overflow-x: hidden !important;  background-color: #fff;">
            <?php $this->Sidebar->create($menuItems); ?>
        </div>
    </aside>
</div>
<script>
    $(function () {
        $('.u-sidebar-navigation-v1-menu-item').click(function () {
            $('.u-sidebar-navigation-v1-menu-item').removeClass('u-side-nav-opened');
            $(this).addClass('u-side-nav-opened');
        });
    });
</script>
