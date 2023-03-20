<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * UserSticker Entity
 *
 * @property int $id
 * @property int $sticker_id
 * @property int $user_id
 * @property string $delivery_status
 * @property bool $status
 * @property \Cake\I18n\FrozenDate $will_deliver_by
 * @property \Cake\I18n\FrozenDate $delivered_at
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\Sticker $sticker
 * @property \App\Model\Entity\User $user
 */
class UserSticker extends Entity {
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'reward_id'      => true,
        'user_id'         => true,
        'delivery_status' => true,
        'status'          => true,
        'will_deliver_by' => true,
        'delivered_at'    => true,
        'created'         => true,
        'modified'        => true,
        'sticker'         => true,
        'user'            => true,
    ];
}
