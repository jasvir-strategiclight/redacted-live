<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Image $image
 */
?>
<div class="images view large-9 medium-8 columns content">
    <h3><?= h($image->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Category') ?></th>
            <td><?= h($image->category) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Image') ?></th>
            <td><?= h($image->image) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Small Thumb') ?></th>
            <td><?= h($image->small_thumb) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Medium Thumb') ?></th>
            <td><?= h($image->medium_thumb) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Large Thumb') ?></th>
            <td><?= h($image->large_thumb) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($image->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($image->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($image->modified) ?></td>
        </tr>
    </table>
    <div class="related">
        <h4><?= __('Related Admins') ?></h4>
        <?php if (!empty($image->admins)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Name') ?></th>
                <th scope="col"><?= __('Email') ?></th>
                <th scope="col"><?= __('Password') ?></th>
                <th scope="col"><?= __('Forgot Password Token') ?></th>
                <th scope="col"><?= __('Image Id') ?></th>
                <th scope="col"><?= __('Created') ?></th>
                <th scope="col"><?= __('Modified') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($image->admins as $admins): ?>
            <tr>
                <td><?= h($admins->id) ?></td>
                <td><?= h($admins->name) ?></td>
                <td><?= h($admins->email) ?></td>
                <td><?= h($admins->password) ?></td>
                <td><?= h($admins->forgot_password_token) ?></td>
                <td><?= h($admins->image_id) ?></td>
                <td><?= h($admins->created) ?></td>
                <td><?= h($admins->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Admins', 'action' => 'view', $admins->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Admins', 'action' => 'edit', $admins->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Admins', 'action' => 'delete', $admins->id], ['confirm' => __('Are you sure you want to delete # {0}?', $admins->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
    <div class="related">
        <h4><?= __('Related Apartment Images') ?></h4>
        <?php if (!empty($image->apartment_images)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Apartment Id') ?></th>
                <th scope="col"><?= __('Image Id') ?></th>
                <th scope="col"><?= __('Status') ?></th>
                <th scope="col"><?= __('Created') ?></th>
                <th scope="col"><?= __('Modified') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($image->apartment_images as $apartmentImages): ?>
            <tr>
                <td><?= h($apartmentImages->id) ?></td>
                <td><?= h($apartmentImages->apartment_id) ?></td>
                <td><?= h($apartmentImages->image_id) ?></td>
                <td><?= h($apartmentImages->status) ?></td>
                <td><?= h($apartmentImages->created) ?></td>
                <td><?= h($apartmentImages->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'ApartmentImages', 'action' => 'view', $apartmentImages->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'ApartmentImages', 'action' => 'edit', $apartmentImages->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'ApartmentImages', 'action' => 'delete', $apartmentImages->id], ['confirm' => __('Are you sure you want to delete # {0}?', $apartmentImages->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
    <div class="related">
        <h4><?= __('Related Apartments') ?></h4>
        <?php if (!empty($image->apartments)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('User Id') ?></th>
                <th scope="col"><?= __('Name') ?></th>
                <th scope="col"><?= __('Address') ?></th>
                <th scope="col"><?= __('City Id') ?></th>
                <th scope="col"><?= __('State Id') ?></th>
                <th scope="col"><?= __('Zip') ?></th>
                <th scope="col"><?= __('Lat') ?></th>
                <th scope="col"><?= __('Lng') ?></th>
                <th scope="col"><?= __('Market Place Id') ?></th>
                <th scope="col"><?= __('Image Id') ?></th>
                <th scope="col"><?= __('Status') ?></th>
                <th scope="col"><?= __('Is Premium') ?></th>
                <th scope="col"><?= __('Is Available') ?></th>
                <th scope="col"><?= __('Has Floor Plan') ?></th>
                <th scope="col"><?= __('Last Searched At') ?></th>
                <th scope="col"><?= __('Created') ?></th>
                <th scope="col"><?= __('Modified') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($image->apartments as $apartments): ?>
            <tr>
                <td><?= h($apartments->id) ?></td>
                <td><?= h($apartments->user_id) ?></td>
                <td><?= h($apartments->name) ?></td>
                <td><?= h($apartments->address) ?></td>
                <td><?= h($apartments->city_id) ?></td>
                <td><?= h($apartments->state_id) ?></td>
                <td><?= h($apartments->zip) ?></td>
                <td><?= h($apartments->lat) ?></td>
                <td><?= h($apartments->lng) ?></td>
                <td><?= h($apartments->market_place_id) ?></td>
                <td><?= h($apartments->image_id) ?></td>
                <td><?= h($apartments->status) ?></td>
                <td><?= h($apartments->is_premium) ?></td>
                <td><?= h($apartments->is_available) ?></td>
                <td><?= h($apartments->has_floor_plan) ?></td>
                <td><?= h($apartments->last_searched_at) ?></td>
                <td><?= h($apartments->created) ?></td>
                <td><?= h($apartments->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Apartments', 'action' => 'view', $apartments->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Apartments', 'action' => 'edit', $apartments->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Apartments', 'action' => 'delete', $apartments->id], ['confirm' => __('Are you sure you want to delete # {0}?', $apartments->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
    <div class="related">
        <h4><?= __('Related Users') ?></h4>
        <?php if (!empty($image->users)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('First Name') ?></th>
                <th scope="col"><?= __('Last Name') ?></th>
                <th scope="col"><?= __('Reporting Name') ?></th>
                <th scope="col"><?= __('Email') ?></th>
                <th scope="col"><?= __('Reporting Email') ?></th>
                <th scope="col"><?= __('Password') ?></th>
                <th scope="col"><?= __('Forgot Password Token') ?></th>
                <th scope="col"><?= __('Image Id') ?></th>
                <th scope="col"><?= __('Phone') ?></th>
                <th scope="col"><?= __('Address') ?></th>
                <th scope="col"><?= __('City Id') ?></th>
                <th scope="col"><?= __('State Id') ?></th>
                <th scope="col"><?= __('Zip') ?></th>
                <th scope="col"><?= __('Role') ?></th>
                <th scope="col"><?= __('Registration Steps Done') ?></th>
                <th scope="col"><?= __('Active') ?></th>
                <th scope="col"><?= __('No Of Apartments') ?></th>
                <th scope="col"><?= __('No Of Customers') ?></th>
                <th scope="col"><?= __('Created') ?></th>
                <th scope="col"><?= __('Modified') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($image->users as $users): ?>
            <tr>
                <td><?= h($users->id) ?></td>
                <td><?= h($users->first_name) ?></td>
                <td><?= h($users->last_name) ?></td>
                <td><?= h($users->reporting_name) ?></td>
                <td><?= h($users->email) ?></td>
                <td><?= h($users->reporting_email) ?></td>
                <td><?= h($users->password) ?></td>
                <td><?= h($users->forgot_password_token) ?></td>
                <td><?= h($users->image_id) ?></td>
                <td><?= h($users->phone) ?></td>
                <td><?= h($users->address) ?></td>
                <td><?= h($users->city_id) ?></td>
                <td><?= h($users->state_id) ?></td>
                <td><?= h($users->zip) ?></td>
                <td><?= h($users->role) ?></td>
                <td><?= h($users->registration_steps_done) ?></td>
                <td><?= h($users->active) ?></td>
                <td><?= h($users->no_of_apartments) ?></td>
                <td><?= h($users->no_of_customers) ?></td>
                <td><?= h($users->created) ?></td>
                <td><?= h($users->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Users', 'action' => 'view', $users->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Users', 'action' => 'edit', $users->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Users', 'action' => 'delete', $users->id], ['confirm' => __('Are you sure you want to delete # {0}?', $users->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>
