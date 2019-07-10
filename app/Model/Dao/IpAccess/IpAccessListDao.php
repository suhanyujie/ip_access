<?php
/**
 * Created by PhpStorm.
 * User: Samuel
 * Date: 2019/7/10
 * Time: 18:58
 */

namespace App\Model\Dao\IpAccess;

use Swoft\Db\DB;

class IpAccessListDao
{
    public function getList()
    {

    }

    public function add($params=[])
    {
        $isOk = DB::table($this->tableName)->insert($params);
        if (!$isOk) {
            return ['status'=>-1, 'msg'=>'新增失败！'];
        }
    }

    public function addAll($params=[])
    {

    }

    public function update($params=[])
    {

    }

    public function delete()
    {

    }
}
