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

class DynCodeIndexLogic extends BaseLogic
{
    /**
     * @var CommonDynCodeDao
     */
    protected $dao;

    public function __construct()
    {
        parent::__construct();
        $this->dao = new CommonDynCodeDao();
    }

    /**
     * 检查用户动态码是否在有效期内
     */
    public function checkDynCodeIsOk($params=[])
    {
        $options = [
            'username'=>'',
        ];
        $options = array_merge($options, $params);
        if (empty($options['username']))return ['status'=>-1, 'msg'=>'缺少参数'];
        $dynCodes = $this->dao->getList([
            'username'=>$options['username'],
            'limit'=>1,
        ]);
        var_dump($dynCodes);
        if (empty($dynCodes)) {

        }

    }
}
