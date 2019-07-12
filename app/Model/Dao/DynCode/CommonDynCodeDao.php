<?php
/**
 * Created by PhpStorm.
 * User: Samuel
 * Date: 2019/7/12
 * Time: 18:00
 */

namespace App\Model\Dao\DynCode;

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

        if ($options['isCount'] === 1) {
            return $dbObj->count();
        }
        // 排序
        if (!empty($options['orderBy']) && is_array($options['orderBy'])) {
            foreach ($options['orderBy'] as $column => $orderType) {
                $dbObj = $dbObj->orderBy($column, $orderType);
            }
        }
        $data = $dbObj->skip($options['offset'])->limit($options['limit'])->get();
        return $data;
    }

    public function add($params=[])
    {

    }

    public function update($where=[],$params=[])
    {

    }

    public function delete()
    {

    }
}
