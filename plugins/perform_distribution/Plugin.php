<?php
namespace app\plugins\perform_distribution;


use app\handlers\BaseHandler;
use app\plugins\perform_distribution\handlers\HandlerRegister;

class Plugin extends \app\plugins\Plugin
{
    public function getIsSetToQuickMenu()
    {
        //这里去缓存里面查询
        return false; // TODO: Change the autogenerated stub
    }

    //为主菜单提供的菜单
    public function getMenuForMainMenu()
    {
        return [
            'key'         => $this->getName(),
            'name'        => $this->getDisplayName(),
            'route'       => $this->getIndexRoute(),
            'children'    => $this->getMenus(),
            'icon'        => 'statics/img/mall/nav/finance.png',
            'icon_active' => 'statics/img/mall/nav/finance-active.png',
        ];
    }

    public function getMenus()
    {
        return [
            [
                'name' => '等级设置',
                'route' => 'plugin/perform_distribution/mall/level/index',
                'icon' => 'el-icon-setting'
            ],
            [
                'name' => '商品设置',
                'route' => 'plugin/perform_distribution/mall/goods/index',
                'icon' => 'el-icon-setting'
            ],
            [
                'name' => '区域设置',
                'route' => 'plugin/perform_distribution/mall/region/index',
                'icon' => 'el-icon-setting'
            ],
            [
                'name' => '奖励明细',
                'route' => 'plugin/perform_distribution/mall/award/order',
                'icon' => 'el-icon-setting'
            ]
        ];
    }

    public function getIndexRoute()
    {
        $menus = $this->getMenus();
        return $menus[0]['route'];
    }

    /**
     * 插件唯一id，小写英文开头，仅限小写英文、数字、下划线
     * @return string
     */
    public function getName()
    {
        return 'perform_distribution';
    }

    /**
     * 插件显示名称
     * @return string
     */
    public function getDisplayName()
    {
        return '业绩分配奖';
    }


    public function getIsPlatformPlugin()
    {
        return true;
    }

    public function handler()
    {
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

    public function getLogo()
    {
        // TODO: Implement getLogo() method.
        return '';
    }

    public function getPriceTypeName($price_log_id = 0)
    {
        // TODO: Implement getPriceTypeName() method.
        return '';
    }
}
