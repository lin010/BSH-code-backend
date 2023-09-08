<?php
/**
 * @link:http://www.gdqijianshi.com/
 * @copyright: Copyright (c) 2020 广东七件事集团
 * 后台首页
 * Author: zal
 * Date: 2020-04-08
 * Time: 16:12
 */

namespace app\controllers\admin;

use app\core\ApiCode;
use app\forms\admin\AdminForm;
use app\forms\admin\AdminEditForm;
use app\forms\common\AttachmentForm;
use app\helpers\SerializeHelper;
use app\logic\AuthLogic;
use app\models\Admin;

use app\events\OrderEvent;
use app\forms\common\order\OrderCommon;
use app\logic\VideoLogic;
use app\models\Order;
use app\models\User;
use app\models\UserChildren;
use app\models\UserInfo;
use app\models\UserParent;
use app\plugins\distribution\models\Distribution;
use FFMpeg\Coordinate\Dimension;
use FFMpeg\Coordinate\TimeCode;
use FFMpeg\FFMpeg;
use FFMpeg\FFProbe;
use yii\db\Exception;
use yii\web\Controller;
use yii\db\Query;

class TestController extends Controller
{
    /**
     * 人人商城迁移禾匠数据
     * @return void
     */
    public function actionMigrate()
    {
        $query = new Query;
        $limit = 1000;
        //总数据
        $counts = $query->where([
            'jxmall_user_id' => 0
        ])->count();
        $maxPage = ceil($counts / $limit);
        $mallId = 5;
        //人人的分销等级
        $renrenLevelName = [
            13 => '会员',
            14 => '门店',
            15 => '合伙人',
            16 => '代理商',
            17 => '分公司',
        ];

        //人人会员等级
        $renrenMemberLevel = [
            5 => '会员',
            6 => '店主',
            7 => '区域经销商',
            8 => '门店',
            13 => '经销商',
        ];
        //禾匠的会员等级
        $jxmallLevelName = [
            'user' => 'VIP会员',
            'branch_office' => '城市服务商',
            'partner' => '区域服务商',
            'store' => 'VIP代理商',
        ];
        // VIP会员---游客
        // VIP代理---店主
        // 区域服务商---经销商
        // 城市服务商---区域经销商
        //左边是补商汇等级,对应右边兰亭的等级

        //人人会员等级对应禾匠的会员等级，不在匹配内的全都 设为 user
        $memberLevelLinkJxmallLevel = [
            6 => 'store',
            7 => 'branch_office',
            13 => 'partner',
        ];

        $vcshopMemberTable = 'ims_vcshop_member';
        $time = time();
        set_time_limit(0);
        for ($i = 0; $i <= $maxPage; $i++) {
            $vcshopMembers = $query->select('*')
                ->from($vcshopMemberTable)
                ->where([
                    'jxmall_user_id' => 0
                ])
                ->all();
            if (empty($vcshopMembers)) {
                echo '没有数据了';
                break;
            }
            $num = 0;
            $okNumber = 0;
            foreach ($vcshopMembers as $vcshopMember) {
                $t = \Yii::$app->db->beginTransaction();
                try {
                    $userModel = new User();
                    if ($vcshopMember['mobile']) {
                        $userActiveModel = $userModel::find()->where(['mobile' => $vcshopMember['mobile']])->one();
                        if (!is_null($userActiveModel)) {
                            $userModel = $userActiveModel;
                        }
                    }
                    if (array_key_exists($vcshopMember['level'], $memberLevelLinkJxmallLevel)) {
                        $userModel->role_type = $memberLevelLinkJxmallLevel[$vcshopMember['level']];
                    } else {
                        $userModel->role_type = 'user';
                    }
                    $userModel->mall_id = $mallId;
                    $userModel->username = $vcshopMember['mobile'] ?: 'wechat_user';
                    $userModel->access_token = \Yii::$app->security->generateRandomString();
                    $userModel->auth_key = \Yii::$app->security->generateRandomString();
                    $userModel->mobile = $vcshopMember['mobile'] ?: NULL;
                    $userModel->realname = $vcshopMember['realname'];
                    $userModel->nickname = $vcshopMember['nickname_wechat'];
                    $userModel->avatar_url = $vcshopMember['avatar_wechat'];
                    $userModel->vcshop_member_id = $vcshopMember['id'];
                    $userModel->vcshop_member_agentid = $vcshopMember['agentid'];
                    $userModel->created_at = $time;
                    $userModel->password = \Yii::$app->getSecurity()->generatePasswordHash(mt_rand(1000000, 1999494900));
                    $res = $userModel->save();
                    if (!$res) throw new Exception($vcshopMember['id'] . ":插入失败!" . var_export($userModel->errors, true) . "\n");
                    //用户信息
                    $userId = $userModel->primaryKey;
                    $userInfoModel = new UserInfo();
                    $userInfoActiveModel = $userInfoModel::find()->where(['user_id' => $userId])->one();
                    if (!is_null($userInfoActiveModel)) {
                        $userInfoModel = $userInfoActiveModel;
                    }
                    $userInfoModel->mall_id = $mallId;
                    $userInfoModel->user_id = $userId;
                    $userInfoModel->platform = 'wechat';
                    $userInfoModel->openid = $vcshopMember['openid'];
                    $userInfoModel->platform_data = json_encode([
                        'openid' => $vcshopMember['openid'],
                        'nickname' => $vcshopMember['nickname_wechat'],
                        'sex' => 0,
                        'language' => 'zh_CN',
                        'city' => '',
                        'province' => '',
                        'country' => '',
                        'headimgurl' => $vcshopMember['avatar_wechat'],
                        'privilege' => [],
                        'unionid' => '',
                    ]);
                    $userInfoModel->created_at = $time;
                    $userInfoModel->save();
                    //更新用户表
                    $res = $query->createCommand()->update($vcshopMemberTable, [
                        'jxmall_user_id' => $userId,
                    ], ['id' => $vcshopMember['id']])->execute();
                    if (!$res) throw new Exception($vcshopMember['id'] . ":修改原表失败" . $res . "\n");
                    //添加到分销商权限
                    if ($vcshopMember['status'] && $vcshopMember['isagent']) {
                        $distributionModel = new Distribution();
                        $distributionActiveModel = $distributionModel::find()->where(['user_id' => $userId])->one();
                        if (!is_null($distributionActiveModel)) {
                            $distributionModel = $distributionActiveModel;
                        }
                        $distributionModel->mall_id = $mallId;
                        $distributionModel->user_id = $userId;
                        $distributionModel->created_at = $time;
                        $distributionModel->upgrade_status = 3;
                        $distributionModel->save();
                        if (!$res) throw new Exception($vcshopMember['id'] . ":更新分销商失败" . var_export($distributionModel->errors, true) . "\n");

                    }
                    $t->commit();
                    ++$okNumber;
                } catch (\Exception $e) {
                    file_put_contents("vcshop_member.log", $e->getMessage(), FILE_APPEND);
                    $t->rollBack();
                }
                ++$num;

            }
            $msg = '运行成功...成功:' . $okNumber . ',失败：' . ($num - $okNumber);
            file_put_contents("vcshopMember.log", $msg, FILE_APPEND);
            echo '迁移会员信息' . $msg;
        }
    }

    /***
     * 更新人人商城对应的会员的新上级
     * @return void
     * @throws Exception
     */
    public function actionUpdateParent()
    {
        $sql = 'UPDATE `jxmall_user` u1 JOIN 
(SELECT u.id,u.parent_id,u.vcshop_member_id,u.vcshop_member_agentid,IFNULL(m.jxmall_user_id,0) new_parent_id FROM `jxmall_user` u LEFT JOIN `ims_vcshop_member` m on u.vcshop_member_agentid=m.id WHERE u.vcshop_member_id>0) t1 ON u1.id=t1.id 
SET u1.parent_id=t1.new_parent_id';
        $res = \Yii::$app->db->createCommand($sql)
            ->execute();
        $msg = $res ? '成功更新[' . $res . ']个会员的上级' : '未更新';
        file_put_contents("updateParent.log", $msg, FILE_APPEND);
        echo "更新会员上级：" . $msg . '<b/>';
        $sql = 'UPDATE jxmall_user u
JOIN ( SELECT * FROM ims_vcshop_member WHERE jxmall_user_id > 0 ) m ON u.id = m.jxmall_user_id 
SET u.cert_no = m.cert_no,
u.cert_validity_type = m.cert_validity_type,
u.cert_begin_date = m.cert_begin_date,
u.cert_end_date = m.cert_end_date,
u.huifu_id = m.huifu_id,
u.huifu_login_name = m.huifu_login_name,
u.huifu_login_password = m.huifu_login_password,
u.huifu_create_time = m.huifu_create_time,
u.huifu_update_time = m.huifu_update_time,
u.bank_name = m.bankname,
u.bank_account = m.bankcard,
u.bankbranch = m.bankbranch,
u.bankprovinceid = m.bankprovinceid,
u.bankprovince = m.bankprovince,
u.bankcityid = m.bankcityid,
u.bankcity = m.bankcity,
u.huifu_bank_token_no = m.huifu_bank_token_no,
u.huifu_cash_type = m.huifu_cash_type 
WHERE
     u.vcshop_member_id>0';
        $res = \Yii::$app->db->createCommand($sql)
            ->execute();
        $msg = $res ? '成功更新[' . $res . ']个会员的资料' : '未更新';
        file_put_contents("vcshopMemberInfo.log", $msg, FILE_APPEND);
        echo "更新银行信息资料：" . $msg . '<b/>';
    }

}

