<?php

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Images Model
 *
 * @property \App\Model\Table\AdminsTable&\Cake\ORM\Association\HasMany $Admins
 * @property \App\Model\Table\PagesTable&\Cake\ORM\Association\HasMany $Pages
 * @property \App\Model\Table\StickersTable&\Cake\ORM\Association\HasMany $Stickers
 *
 * @method \App\Model\Entity\Image get($primaryKey, $options = [])
 * @method \App\Model\Entity\Image newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Image[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Image|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Image saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Image patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Image[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Image findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ImagesTable extends Table {
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config) {
        parent::initialize($config);

        $this->setTable('images');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('Admins', [
            'foreignKey' => 'image_id',
        ]);
        $this->hasMany('Pages', [
            'foreignKey' => 'image_id',
        ]);
        $this->hasMany('Stickers', [
            'foreignKey' => 'image_id',
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator) {
        $validator
            ->integer('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->scalar('category')
            ->maxLength('category', 255)
            ->requirePresence('category', 'create')
            ->notEmptyString('category');

        $validator
            ->scalar('image')
            ->maxLength('image', 255)
            ->requirePresence('image', 'create')
            ->notEmptyFile('image');

        $validator
            ->scalar('small_thumb')
            ->maxLength('small_thumb', 255)
            ->requirePresence('small_thumb', 'create')
            ->notEmptyString('small_thumb');

        $validator
            ->scalar('medium_thumb')
            ->maxLength('medium_thumb', 255)
            ->requirePresence('medium_thumb', 'create')
            ->notEmptyString('medium_thumb');

        $validator
            ->scalar('large_thumb')
            ->maxLength('large_thumb', 255)
            ->requirePresence('large_thumb', 'create')
            ->notEmptyString('large_thumb');

        return $validator;
    }
}
