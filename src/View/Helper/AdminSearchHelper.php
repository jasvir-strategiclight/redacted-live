<?php

namespace App\View\Helper;

use Cake\View\Helper;
use Cake\Utility\Inflector;

class AdminSearchHelper extends Helper {
    
    public $bulk;
    public $search;
    
    public function create($bulk = [], $search = []) {
        if (array_values($this->request->getParam('paging'))[0]['count'] > 0) {
            $this->setBulk($bulk);
            $this->setSearch($search);
            ?>
            <div class="row">
                <div class="col-md-5">
                    <form action="<?= SITE_URL; ?>admin/admins/bulk">
                    
                    </form>
                </div>
                <div class="col-md-7">
                
                </div>
            </div>
            
            <?php
        }
    }
    
    public function setBulk($bulk) {
        $defaultBulk = [
            'actions' => [
                'active' => 'Active',
                'inactive' => 'Inactive',
                'delete' => 'Delete',
            ],
        ];
        
        $bulk += $defaultBulk;
        
        $this->bulk = $bulk;
        
    }
    
    public function setSearch($search) {
        $model = empty($search['model']) ? array_keys($this->request->getParam('paging'))[0] : $search['model'];
        $match = [];
        if (empty($search['match'])) {
            $match = [$model . '.name'];
        } else {
            foreach ($search['match'] as $relatedModel => $m) {
                $match[] = (is_numeric($relatedModel) ? $model : $relatedModel) . '.' . $m;
            }
        }
        
        $finalSearch = [
            'controller' => empty($search['controller']) ? $this->request->getParam('controller') : $search['controller'],
            'action' => empty($search['action']) ? $this->request->getParam('action') : $search['action'],
            'model' => $model,
            'match' => $match,
            'placeholder' => empty($search['placeholder']) ? 'Search...' : $search['placeholder'],
        ];
        
        $this->search = $finalSearch;
    }
    
}
