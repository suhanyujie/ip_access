<?php
/**
 * Created by PhpStorm.
 * User: Samuel
 * Date: 2019/7/10
 * Time: 18:58
 */

namespace App\Model\Dao\IpAccess;

use Exception;
use ReflectionException;
use Swoft\Bean\Exception\ContainerException;
use Swoft\Db\DB;
use Swoft\Db\Exception\DbException;
use Swoft\Stdlib\Collection;

class IpAccessListDao
{
    protected $tableName = 'ip_access_list';

    protected $data = [];

    public function __construct()
    {
        $this->data['nowTime'] = date('Y-m-d H:i:s');
    }

    /**
     * 获取数据列表
     *
     * @param array $params
     * @return int|Collection
     * @throws ReflectionException
     * @throws ContainerException
     * @throws DbException
     */
    public function getList($params = [])
    {
        $options = [
            'id'          => 0,
            'ip_str'      => '',
            'type'        => 1,
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
        if (!empty($options['ip_str'])) {
            $dbObj = $dbObj->where('ip_str', '=', $options['ip_str']);
        }
        if (!empty($options['type'])) {
            $dbObj = $dbObj->where('type', '=', (int)$options['type']);
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

    /**
     * 新增数据
     *
     * @param array $params
     * @return array
     * @throws ReflectionException
     * @throws ContainerException
     * @throws DbException
     */
    public function add($params = [])
    {
        $nowTime = date('Y-m-d H:i:s');
        $options = [
            'ip_str'      => '',
            'type'        => 1,
            'data_status' => 1,
            'add_time'    => $nowTime,
        ];
        $options = array_merge($options, $params);
        if (empty($options['ip_str'])) {
            return ['status' => -1, 'msg' => '缺少参数 ip_str！'];
        }
        $isExist = DB::table($this->tableName)->where('ip_str', '=', $options['ip_str'])->first();
        if ($isExist) {
            return ['status' => -1, 'msg' => '这个 ip 数据已存在！'];
        }
        try {
            $isOk = DB::table($this->tableName)->insert($options);
        } catch (Exception $e) {
            return ['status' => $e->getCode(), 'msg' => $e->getMessage()];
        }
        if (!$isOk) {
            return ['status' => -1, 'msg' => '新增失败！'];
        } else {
            return ['status' => 1, 'msg' => '新增成功！'];
        }
    }

    public function addAll($params = [])
    {

    }

    /**
     * 只支持根据主键以及 ip_str 字段 update 数据
     *
     * @param array $where
     * @param array $params
     * @return array
     * @throws ReflectionException
     * @throws ContainerException
     * @throws DbException
     */
    public function update($where = [], $params = [])
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
            'ip_str'      => '',
            'type'        => '',
            'data_status' => '',
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

    /**
     * 支持主键删除
     *
     * @param int $id
     * @param int $isSoft
     * @return array
     */
    public function delete($id = 0, $isSoft = 1)
    {
        try {
            if ((int)$isSoft !== 2) {
                $isOk = DB::table($this->tableName)
                    ->where('id', '=', $id)
                    ->update([
                        'data_status' => 0,
                        'update_time' => $this->data['nowTime'],
                    ]);
            } else {
                $isOk = DB::table($this->tableName)
                    ->delete($id);
            }
        } catch (Exception $e) {
            return ['status' => -1, 'msg' => $e->getMessage()];
        }
        if (!$isOk) {
            return ['status' => -1, 'msg' => '删除失败！'];
        } else {
            return ['status' => 1, 'msg' => '删除成功！'];
        }
    }
}
