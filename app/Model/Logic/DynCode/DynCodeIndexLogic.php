<?php
/**
 * Created by PhpStorm.
 * User: Samuel
 * Date: 2019/7/12
 * Time: 18:10
 */

namespace App\Model\Logic\DynCode;

use App\Model\Dao\DynCode\CommonDynCodeDao;
use App\Model\Logic\BaseLogic;
use App\Model\Logic\Tg\MessageLogic;
use App\Model\Logic\Tg\MsgTemplate;

class DynCodeIndexLogic extends BaseLogic
{
    // 默认 5 分钟
    protected $expTime = 300;

    protected $data = [];

    /**
     * @var CommonDynCodeDao
     */
    protected $dao;

    public function __construct()
    {
        parent::__construct();
        $this->dao = new CommonDynCodeDao();
        $this->data['nowTime'] = date('Y-m-d H:i:s');
    }

    /**
     * 检查动态码是否在有效期内
     *
     * @param array $params
     * @return array
     */
    public function checkDynCodeExpTime($params=[])
    {
        return $this->checkDynCodeIsOk($params);
    }

    /**
     * 检查用户动态码是否可用。1.是否存在 2.是否处于有效期
     */
    public function checkDynCodeIsOk($params=[])
    {
        $options = [
            'username'=>'',
        ];
        $options = array_merge($options, $params);
        if (empty($options['username']))return ['status'=>-3054, 'msg'=>'缺少参数'];
        $this->data['nowTime'] = date('Y-m-d H:i:s');
        $dynCodes = $this->dao->getList([
            'username'  => $options['username'],
            'exp_time1' => $this->data['nowTime'],
            'orderBy'   => ['id' => 'desc'],
            'limit'     => 1,
        ]);
        if (empty($dynCodes) || count($dynCodes) < 1) {
            return ['status'=>-1, 'msg'=>'不存在可用动态码！'];
        }
        $dynCodes = $this->dao->getList([
            'username'    => $options['username'],
            'exp_time1'   => $this->data['nowTime'],
            'verify_flag' => 1,
            'orderBy'     => ['id' => 'desc'],
            'limit'       => 1,
        ]);
        if (empty($dynCodes) || count($dynCodes) < 1) {
            return ['status'=>-1, 'msg'=>'动态码未验证！'];
        }
        $dynCodeData = array_pop($dynCodes);
        // 动态码过期
        if (strtotime($dynCodeData['exp_time']) < strtotime($this->data['nowTime'])) {
            $result['status'] = -2;
            $result['msg'] = '动态码已过期，请重新生成！';
            return $result;
        }
        $debug = json_encode($dynCodeData + [
            'curTime'=>$this->data['nowTime'],
            'realTime'=>date('Y-m-d H:i:s'),
            ]);
        return ['status'=>1, 'msg'=>"动态码ok{$debug}"];
    }

    /**
     * 生成动态码，用户名，过期时间信息。并存入数据库。然后发送给接收者。
     * 如果旧的尚未失效，则新的会进行覆盖
     */
    public function generateDynCodeInfo($params=[])
    {
        $options = [
            'app'      => '',
            'username' => '',
        ];
        $options = array_merge($options, $params);
        $resInfo = $this->getDynCodeInfo($options);
        if ($resInfo['status'] !== 1) {
            return $resInfo;
        }
        $codeInfo = $resInfo['data'];
        $codeInfo += $options;
        $isOkInfo = $this->dao->add($codeInfo);
        if ($isOkInfo['status'] != 1) {
            return $isOkInfo;
        }
        // 将动态码发送给接收者
        $tmMessageContent = MsgTemplate::getMessageSimpleStyle([
            'app'=>$options['app'],
            'userName' => $codeInfo['username'],
            'code'     => $codeInfo['code'],
        ]);
        $result = MessageLogic::sendSecurityCodeToTgChannel([
            'message' => $tmMessageContent,
        ]);
        if ($result['status'] != 1) {
            return $result;
        }

        return ['status'=>1, 'data'=>$codeInfo, 'msg'=>'生成动态码成功！'];
    }

    public function verifyDynCode($params=[])
    {
        $options = [
            'app'      => '',
            'username' => '',
            'dynCode'  => '',
        ];
        $options = array_merge($options, $params);
        $this->data['nowTime'] = date('Y-m-d H:i:s');
        $dynCodes = $this->dao->getList([
            'username'    => $options['username'],
            'exp_time1'   => $this->data['nowTime'],
            'verify_flag' => 2,
            'orderBy'     => ['id' => 'desc'],
            'limit'       => 1,
        ]);
        if (empty($dynCodes)) {
            return ['status'=>-1, 'msg'=>'错误的验证请求！'];
        }
        $dynCode = array_pop($dynCodes);
        $isOkInfo = $this->dao->update([
            'id'=>$dynCode['id'],
        ], [
            'verify_flag'=>1,
        ]);
        if ($isOkInfo['status'] != 1) {
            return $isOkInfo;
        }

        return ['status'=>1, 'msg'=>'验证动态码成功，后续的 5 分钟内将无需再次验证。'];
    }

    /**
     * @param array $params
     * @return array
     * 其中 data 结构如下 data=>[
     *  username=>'',
     *  code=>'',
     *  exp_time=>'',
     * ],
     */
    public function getDynCodeInfo($params=[]):array
    {
        $options = [
            'username'=>'',
        ];
        $options = array_merge($options, $params);
        if (empty($options['username'])) {
            return ['status'=>-30, 'msg'=>'缺少参数！'];
        }
        $code = self::getSecurityCode();
        $codeInfo = [
            'username' => $options['username'],
            'code'     => $code,
            'exp_time' => date('Y-m-d H:i:s', time()+$this->expTime),
        ];

        return ['status'=>1, 'data'=>$codeInfo];
    }

    /**
     * 生成动态码
     *
     * @return string
     */
    public static function getSecurityCode()
    {
        $seed = [0,1,2,3,4,5,6,7,8,9,0,1,2,3,4,5,6,7,8,9,0,1,2,3,4,5,6,7,8,9,0,1,2,3,4,5,6,7,8,9,];
        shuffle($seed);
        $arr = array_slice($seed, 0,6);
        $code = implode('', $arr);

        return $code;
    }
}
