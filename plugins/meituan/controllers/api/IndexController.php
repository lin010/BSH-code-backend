<?php
namespace app\plugins\meituan\controllers\api;

use app\controllers\api\ApiController;

class IndexController extends ApiController
{
    public function actionLoginFree(){
        try {
            $accessKey = "D2TU10C72LFM-TK";
            $secretKey = "4DuQcugQsVz603AnkNkhVQ==";

            $contents['ts'] = time() * 1000;
            $contents['entId'] = 103713;
            $contents['nonce'] = md5(uniqid());
            $contents['productType'] = 'mt_waimai';
            $contents['sceneType'] = 4;
            $contents['bizParam'] = [
                'longitude' =>  [
                    'longitude' => '116.480881',
                    'latitude' => '39.989410',
                    'geotype' => 'gcj02',
                    'address' => '北京市朝阳区阜通东大街6号',
                ],
                'tmcApplyExtraJson' => [
                    'applyNo' => 33006,
                    'externalApplyNo' => "xxx-test-4",
                    'tripId' => 16758,
                    'externalTripId' => 'externalTripId1'
                ],
                'RepastApplyExtraJson' => [
                    'applyNo' => '5NQZ0NBUDZPD',
                    'externalApplyNo' => '5N9D0AD96RY92',
                ]
            ];
            $contents['staffInfo'] = [
                'staffPhone' => '15603309067',
                'staffNum' => 100000
            ];

            $encryptContent = openssl_encrypt(json_encode($contents,JSON_UNESCAPED_UNICODE), 'AES-128-ECB', base64_decode($secretKey));
            $encryptContent = str_replace('/', '_', $encryptContent);
            $encryptContent = str_replace('+', '-', $encryptContent);
            $encryptContent = str_replace('=', '', $encryptContent);

?>
  <form method="post" action="https://waimai-openapi.apigw.test.meituan.com/api/sqt/open/login/h5/loginFree/redirection?test_open_swimlane=test-open">
      <div style="display: flex;flex-direction: column;">
          <input name="accessKey" value="<?php echo $accessKey; ?>"  style="margin-top:5px;"/>
          <input name="content" value="<?php echo $encryptContent; ?>" style="margin-top:5px;"/>
          <input type="submit" value="提交"  style="margin-top:5px;">
      </div>

  </form>
<?php
        }catch (\Exception $e){
            die($e->getMessage());
        }
    }
}
?>