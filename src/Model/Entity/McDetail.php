<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * McDetail Entity
 *
 * @property int $id
 * @property string $api_key
 * @property string $list_id
 * @property string $merged_fields_json
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\List $list
 */
class McDetail extends Entity {
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
        'title'            => true,
        'api_key'            => true,
        'list_id'            => true,
        'merged_fields_json' => true,
        'mc_merge_fields' => true,
        'created'            => true,
        'modified'           => true,
    ];
}
