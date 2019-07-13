<?php
/**
 * Created by PhpStorm.
 * User: Samuel
 * Date: 2019/7/12
 * Time: 18:00
 */

namespace App\Model\Dao\DynCode;

use Exception;
use Swoft\Db\DB;

class CommonDynCodeDao
{
    protected $tableName = 'common_dyn_code';

    protected $data = [];

    public function __construct()
    {
        $this->data['nowTime'] = date('Y-m-d H:i:s');
    }

    public function getList($params = [])
    {
        $options = [
            'id'          => 0,
            'username'    => '',
            'verify_flag' => '',
            'exp_time1'   => '',
            'app'         => '',
            'data_status' => '',
            'orderBy'     => [],
            'isCount'     => 0,
            'offset'      => 0,
            'limit'       => 1,
        ];
        $options = array_merge($options, $params);
        $dbObj = DB::table($this->tableName);
        if (!empty($options['id'])) {
            $dbObj = $dbObj->where('id', '=', $options['id']);
        }
        if (!empty($options['username'])) {
            $dbObj = $dbObj->where('username', '=', $options['username']);
        }
        if (!empty($options['app'])) {
            $dbObj = $dbObj->where('app', '=', $options['app']);
        }
        if ($options['data_status'] !== '') {
            $dbObj = $dbObj->where('data_status', '=', $options['data_status']);
        }
        if ($options['verify_flag'] !== '') {
            $dbObj = $dbObj->where('verify_flag', '=', $options['verify_flag']);
        }
        if ($options['exp_time1'] !== '') {
            $dbObj = $dbObj->where('exp_time', '>', $options['exp_time1']);
        }

        if ($options['isCount'] === 1) {
            return $dbObj->count();
        }
        // 排序
        if (!empty($options['orderBy']) && is_array($options['orderBy'])) {
            foreach ($options['orderBy'] as $column => $orderType) {
                $dbObj = $dbObj->orderBy($column, $orderType);
            }
        }
        $data = $dbObj->skip($options['offset'])->limit($options['limit'])->get()->toArray();
        return $data;
    }

    public function add($params=[]):array
    {
        $options = [
            'app'         => '',
            'username'    => '',
            'exp_time'    => '',
            'verify_flag' => '',
            'add_time'    => '',
        ];
        $options = array_merge($options, $params);
        if (empty($options['app'])) {
            return ['status'=>-10, 'msg'=>'缺少参数'];
        }
        if (empty($options['username'])) {
            return ['status'=>-11, 'msg'=>'缺少参数'];
        }
        if (empty($options['exp_time'])) {
            return ['status'=>-12, 'msg'=>'缺少参数'];
        }
        $dbObj = DB::table($this->tableName);
        $isOk = $dbObj->insert([
            'app'         => $options['app'],
            'username'    => $options['username'],
            'exp_time'    => $options['exp_time'],
            'verify_flag' => 2,
            'add_time'    => $this->data['nowTime'],
        ]);
        if ($isOk) {
            return ['status'=>1, 'msg'=>'新增成功！'];
        } else{
            return ['status'=>-2, 'msg'=>'新增失败！'];
        }
    }

    public function update($where=[],$params=[])
    {
        if (empty($params)) {
            return ['status' => -1, 'msg' => '更新数据为空！'];
        }
        $where = array_filter($where);
        if (empty($where)) {
            return ['status' => -1, 'msg' => '条件参数为空！'];
        }
        $nowTime = $this->data['nowTime'];
        $options = [
            'verify_flag' => '',
            'update_time' => $nowTime,
        ];
        $options = array_merge($options, $params);
        unset($options['id']);
        $options = array_filter($options);
        try {
            $isOk = DB::table($this->tableName)
                ->where($where)
                ->update($options);
        } catch (Exception $e) {
            return ['status' => -1, 'msg' => $e->getMessage()];
        }
        if (!$isOk) {
            return ['status' => -1, 'msg' => '更新失败！'];
        } else {
            return ['status' => 1, 'msg' => '更新成功！'];
        }
    }

    public function delete()
    {

    }
}
