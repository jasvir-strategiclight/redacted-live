<?php

$menuItems = [
    'edit' => '',
    'editAttributes' => '',
    'editImages' => ''
];

$menuItems[$this->request->getParam('action')] = 'active';
?>
<div class=" g-brd-around g-brd-gray-light-v4 g-rounded-4 g-pa-15 g-pa-20--md">
        <h3 >Edit Exercise</h3>
    <!-- User Information -->
        <!-- Profile Sidebar -->
        <section>
            <ul class="list-unstyled mb-0 realtor-menu">
                <li class="g-brd-top g-brd-gray-light-v7 mb-0">
                    <a class="d-flex align-items-center u-link-v5 g-parent g-color-gray-dark-v7 <?= $menuItems['edit']; ?>"
                       href="<?= $this->Url->build(['controller' => 'Exercises', 'action' => 'edit', $exercise->id]); ?>">
                        <i class="hs-admin-user"></i> &nbsp; Edit Exercise
                    </a>
                </li>
                <li class="g-brd-top g-brd-gray-light-v7 mb-0">
                    <a class="d-flex align-items-center u-link-v5 g-parent g-py-15 g-color-gray-dark-v7 <?= $menuItems['editImages']; ?>"
                       href="<?= $this->Url->build(['controller' => 'Exercises', 'action' => 'editImages', $exercise->id]); ?>">
                        <i class="fa fa-image"></i> &nbsp; Update Images
                    </a>
                </li>
                <li class="g-brd-top g-brd-gray-light-v7 mb-0">
                    <a class="d-flex align-items-center u-link-v5 g-parent g-color-gray-dark-v7 <?= $menuItems['editImages']; ?>"
                       href="<?= $this->Url->build(['controller' => 'Exercises', 'action' => 'editCategories', $exercise->id]); ?>">
                        <i class="fa fa-list-ul"></i> &nbsp; Update Categories
                    </a>
                </li>
            </ul>
        </section>
    </div>
    <script type="text/javascript">
        $(function () {
            $('#profilePicRealtorBtn').hide();
            if (!$('#sideNav').hasClass('u-sidebar-navigation-v1--mini')) {
                $('#hideShowSidebar').click();
            }
            
            $('#imageMedia').on('click', '#chooseSelectedImage', function () {
                $('#profilePicRealtorBtn').fadeIn();
            });
            
            if (localStorage.getItem('realtorEditMenu') != null) {
                $(".realtor-menu li:nth-child(" + (parseInt(localStorage.getItem('realtorEditMenu')) + 1) + ")").click();
            }
        });
        
        function saveProfileImage() {
            var btnHtml = $("#profilePicRealtorBtn").html();
            $("#profilePicRealtorBtn").html("<i class=\'fa fa-spinner \'></i> processing...").attr("disabled", "disabled");
            $.ajax({
                url: $("#profilePicRealtorForm").attr("action"),
                type: "POST",
                data: $("#profilePicRealtorForm").serialize(),
                dataType: "json",
                success: function (response) {
                    if (response.code == 200) {
                        $("#profilePicRealtorBtn").html(btnHtml).removeAttr("disabled");
                    }
                }
            });
            return false;
        }
    </script>
