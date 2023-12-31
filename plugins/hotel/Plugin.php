<?php
namespace app\plugins\hotel;

use app\handlers\BaseHandler;
use app\plugins\agent\handlers\HandlerRegister;

class Plugin extends \app\plugins\Plugin{

    public function getIsSetToQuickMenu(){
        //这里去缓存里面查询
        return false; // TODO: Change the autogenerated stub
    }

    //为主菜单提供的菜单
    public function getMenuForMainMenu(){
        return [
            'key'           => $this->getName(),
            'name'          => '酒店',
            'route'         => $this->getIndexRoute(),
            'children'      => $this->getMenus(),
            'icon'          => 'statics/img/mall/nav/finance.png',
            'icon_active'   => 'statics/img/mall/nav/finance-active.png',
        ]; // TODO: Change the autogenerated stub
    }

    public function getMenus(){
        return [
            [
                'name' => '平台设置',
                'route' => 'plugin/hotel/mall/platforms/setting',
                'icon' => 'el-icon-setting',
                'action' => []
            ],
            [
                'name' => '订单管理',
                'route' => 'plugin/hotel/mall/order/list',
                'icon' => 'el-icon-setting',
                'action' => []
            ],
            [
                'name' => '售后管理',
                'route' => 'plugin/hotel/mall/order/refund',
                'icon' => 'el-icon-setting',
                'action' => []
            ],
        ];
    }

    public function getIndexRoute(){
        return 'plugin/hotel/mall/order/list';
    }


    /**
     * 插件唯一id，小写英文开头，仅限小写英文、数字、下划线
     * @return string
     */
    public function getName(){
        return 'hotel';
    }

    /**
     * 插件显示名称
     * @return string
     */
    public function getDisplayName(){
        return '酒店';
    }


    public function getIsPlatformPlugin(){
        return true;
    }

    public function handler(){
        $register = new HandlerRegister();
        $HandlerClasses = $register->getHandlers();
        foreach ($HandlerClasses as $HandlerClass) {
            $handler = new $HandlerClass();
            if ($handler instanceof BaseHandler) {
                /** @var BaseHandler $handler */
                $handler->register();
            }
        }
        return $this;
    }

    public function getLogo(){
        // TODO: Implement pluginLogo() method.

        return '';
    }

    public function getPriceTypeName($price_log_id = 0){
        return '未知类型';
    }
}
