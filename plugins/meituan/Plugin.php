<?php


namespace app\plugins\meituan;


use app\handlers\BaseHandler;
use app\helpers\PluginHelper;
use app\plugins\meituan\handlers\HandlerRegister;

class Plugin extends \app\plugins\Plugin
{
    public function getMenus()
    {
        return [
            [
                'name' => '基本设置',
                'route' => 'plugin/meituan/mall/setting/index',
                'icon' => 'el-icon-star-on',
            ],
            $this->getStatisticsMenus(false)
        ];
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


    /**
     * 插件唯一id，小写英文开头，仅限小写英文、数字、下划线
     * @return string
     */
    public function getName()
    {
        return 'meituan';
    }

    /**
     * 插件显示名称
     * @return string
     */
    public function getDisplayName()
    {
        return '美团';
    }

    public function getLogo()
    {
        // TODO: Implement pluginLogo() method.
        return '';
    }

    public function getAppConfig()
    {
        $imageBaseUrl = PluginHelper::getPluginBaseAssetsUrl($this->getName()) . '/img';
        return [];
    }

    public function getIndexRoute()
    {
        return 'plugin/meituan/mall/setting/index';
    }

    /**
     * 插件可共用的跳转链接
     * @return array
     */
    public function getPickLink()
    {
        $iconBaseUrl = PluginHelper::getPluginBaseAssetsUrl($this->getName()) . '/img';

        return [
            [
                'key' => 'mt_waimai',
                'name' => '美团外卖',
                'open_type' => 'navigate',
                'icon' => $iconBaseUrl . '/mt_waimai.png',
                'value' => '/meituan/waimai',
            ],
        ];
    }

    public function getPriceTypeName($price_log_id = 0){
        return;
    }


}

