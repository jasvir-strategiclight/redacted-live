<?= $this->Html->css(['vendor/flatpickr/dist/css/flatpickr.min']) ?>
<?= $this->Html->script(['vendor/flatpickr/dist/js/flatpickr.min', 'components/hs.range-datepicker']) ?>
<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Customer[]|\Cake\Collection\CollectionInterface $customers
 */

$this->Heading->create('Not Seen Emails');

$params = [
    'fields' => [
        [
            'name'  => 'to_email',
            'label' => 'Email To',

        ],
        [
            'name'                 => 'no_of_emails',
            'label'                => 'Total Emails Sent',
            'sortable'                => false,

        ],
        [
            'name'                 => 'not_seen_emails',
            'sort_name'                 => 'ScheduledEmails__not_seen_emails',
            'label'                => 'Not Seen Email Count',
            //'sortable'                => false,

        ],
    ],
    'showSearch' => false
];

$this->AdminListing->create($params, []);
?>
<script>
    $(function () {
        // initialization of range datepicker
        $('#filterByWeeks').change(function(){
                window.location.href = SITE_URL +'admin/admins/notSeenEmails/?week='+$(this).val();

        });

        $('#filterByWeeks').val(<?= $week; ?>);


        $('.hs-admin-calendar').click(function () {
            $('#selectDateWrapper input').focus();
        });
    });
</script>

