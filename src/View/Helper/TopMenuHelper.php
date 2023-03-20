<?php

namespace App\View\Helper;


use Cake\View\Helper;
use Cake\Routing\Router;

class TopMenuHelper extends Helper {
    
    public $singleLiClasses = "nav-item color-dark g-color-gray-dark-v1";
    public $singleAnchorClasses = "nav-link color-dark g-color-gray-dark-v1";
    public $controller;
    public $action;
    
    
    public function create($menuItems = null) {
        if (!empty($menuItems)) {
            
            foreach ($menuItems as $menuItem) {
                $this->menuItem($menuItem);
            }
            
            $this->controller = $this->getView()->getRequest()->getParam('controller');
            $this->action = $this->getView()->getRequest()->getParam('action');
            $this->controller = ($this->controller == "Routines") ? "Programs" : $this->controller;
            
            ?>
            <script>
                $(function () {
                    var _this = $('.topNav_<?= $this->controller . $this->action; ?>');
                    _this.children('a').addClass('active');
                });
            </script>
            <?php
        }
    }
    
    public function menuItem($item) {
        $item = $this->buildItem($item);
        $id = 'topNav_' . $item['controller'] . $item['action'];
        if (empty($item['url'])) {
            $url = Router::url(['controller' => $item['controller'], 'action' => $item['action']]);
        } else {
            $url = SITE_URL . $item['url'];
        }
        ?>
        <li class="<?= $this->singleLiClasses; ?> <?= $id; ?>">
            <a class="<?= $this->singleAnchorClasses; ?>" href="<?= $url; ?>"><?= $item['label']; ?></a>
        </li>
        <?php
    }
    
    public function buildItem($item) {
        return [
            'label' => empty($item['label']) ? $item['controller'] : $item['label'],
            'controller' => empty($item['controller']) ? 'Users' : $item['controller'],
            'action' => empty($item['action']) ? 'index' : $item['action'],
            'url' => empty($item['url']) ? [] : $item['url'],
        ];
    }
}
