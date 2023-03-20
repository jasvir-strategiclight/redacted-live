<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\Auth\DefaultPasswordHasher;

/**
 * User Entity
 *
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property string $password
 * @property string $reference_token
 * @property bool $active
 * @property int $no_of_affiliates
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\UserSticker[] $user_stickers
 */
class User extends Entity {
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
        'platform'              => true,
        'affiliated_by'         => true,
        'email'                 => true,
        'password'              => true,
        'first_name'            => true,
        'last_name'             => true,
        'address'               => true,
        'city'                  => true,
        'state'                 => true,
        'zip'                   => true,
        'country'               => true,
        'reference_token'       => true,
        'active'                => true,
        'opt_out'               => true,
        'no_of_affiliates'      => true,
        'created'               => true,
        'modified'              => true,
        'user_stickers'         => true,
        'updated_on_mail_chimp' => true,
        'ip'                    => true,
        'lead_from'             => true,
        'campaign'              => true,
    ];

    /**
     * Fields that are excluded from JSON versions of the entity.
     *
     * @var array
     */
    protected $_hidden = [
        'password',
    ];

    protected function _setPassword($password) {
        return (new DefaultPasswordHasher)->hash($password);
    }
}
