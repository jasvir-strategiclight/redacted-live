<?= $this->Html->css(['vendor/flatpickr/dist/css/flatpickr.min']) ?>
<?= $this->Html->script(['vendor/flatpickr/dist/js/flatpickr.min', 'components/hs.range-datepicker']) ?>
<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User[]|\Cake\Collection\CollectionInterface $users
 */
$this->Heading->create('Users');

    $fields = [
        ['name' => 'email'],
        [
            'name'  => 'reference_token',
            'label' => 'Referral Token',
        ],
        [
                'name' => 'affiliate_id',
                'label' => 'Affiliated By',
                'related_model_fields'=>['email']
        ],
        ['name' => 'created'],


        [
                'name' => 'ip',
                'label'=>'IP Address'
        ],
    ];


$params = [
    'fields' => $fields,
    'search' => [
        'match' => [
            'Users' => ['email'],
            'Affiliates' => ['email'],
        ]
    ]
];

$this->AdminListing->create($params, []);
?>
<script>
    $(function () {
        // initialization of range datepicker
        var moveDate = flatpickr('.js-range-datepicker', {
            altInput: true,
            altFormat: "F j, Y",
            dateFormat: "Y-m-d",
            static: true,
            onChange: function (selectedDates, dateStr, instance) {
                window.location.href = SITE_URL +'admin/users/referrals/?send_at='+dateStr;
            }
        });


        $('.hs-admin-calendar').click(function () {
            $('#selectDateWrapper input').focus();
        });
    });
</script>