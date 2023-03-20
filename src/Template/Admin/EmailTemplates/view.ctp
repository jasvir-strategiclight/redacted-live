<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\EmailTemplate $emailTemplate
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Email Template'), ['action' => 'edit', $emailTemplate->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Email Template'), ['action' => 'delete', $emailTemplate->id], ['confirm' => __('Are you sure you want to delete # {0}?', $emailTemplate->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Email Templates'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Email Template'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Scheduled Emails'), ['controller' => 'ScheduledEmails', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Scheduled Email'), ['controller' => 'ScheduledEmails', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="emailTemplates view large-9 medium-8 columns content">
    <h3><?= h($emailTemplate->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('User') ?></th>
            <td><?= $emailTemplate->has('user') ? $this->Html->link($emailTemplate->user->id, ['controller' => 'Users', 'action' => 'view', $emailTemplate->user->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Label') ?></th>
            <td><?= h($emailTemplate->label) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($emailTemplate->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Subject') ?></th>
            <td><?= $this->Number->format($emailTemplate->subject) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($emailTemplate->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($emailTemplate->modified) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Status') ?></th>
            <td><?= $emailTemplate->status ? __('Yes') : __('No'); ?></td>
        </tr>
    </table>
    <div class="row">
        <h4><?= __('Template') ?></h4>
        <?= $this->Text->autoParagraph(h($emailTemplate->template)); ?>
    </div>
    <div class="row">
        <h4><?= __('Note') ?></h4>
        <?= $this->Text->autoParagraph(h($emailTemplate->note)); ?>
    </div>
    <div class="related">
        <h4><?= __('Related Scheduled Emails') ?></h4>
        <?php if (!empty($emailTemplate->scheduled_emails)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('From') ?></th>
                <th scope="col"><?= __('To') ?></th>
                <th scope="col"><?= __('Email Template Id') ?></th>
                <th scope="col"><?= __('Send After') ?></th>
                <th scope="col"><?= __('Send After Type') ?></th>
                <th scope="col"><?= __('Status') ?></th>
                <th scope="col"><?= __('Created') ?></th>
                <th scope="col"><?= __('Modified') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($emailTemplate->scheduled_emails as $scheduledEmails): ?>
            <tr>
                <td><?= h($scheduledEmails->id) ?></td>
                <td><?= h($scheduledEmails->from) ?></td>
                <td><?= h($scheduledEmails->to) ?></td>
                <td><?= h($scheduledEmails->email_template_id) ?></td>
                <td><?= h($scheduledEmails->send_after) ?></td>
                <td><?= h($scheduledEmails->send_after_type) ?></td>
                <td><?= h($scheduledEmails->status) ?></td>
                <td><?= h($scheduledEmails->created) ?></td>
                <td><?= h($scheduledEmails->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'ScheduledEmails', 'action' => 'view', $scheduledEmails->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'ScheduledEmails', 'action' => 'edit', $scheduledEmails->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'ScheduledEmails', 'action' => 'delete', $scheduledEmails->id], ['confirm' => __('Are you sure you want to delete # {0}?', $scheduledEmails->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>
