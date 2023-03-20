<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Page Entity
 *
 * @property int $id
 * @property string $page
 * @property string $heading
 * @property string $content
 * @property int|null $image_id
 * @property string|null $menu_assigned
 * @property int|null $menu_item_order
 * @property string $plans
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\Image $image
 */
class Page extends Entity
{
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
        'page' => true,
        'heading' => true,
        'content' => true,
        'image_id' => true,
        'menu_assigned' => true,
        'menu_item_order' => true,
        'plans' => true,
        'created' => true,
        'modified' => true,
        'image' => true,
    ];
}
