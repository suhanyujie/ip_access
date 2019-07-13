<?php
/**
 * Created by PhpStorm.
 * User: Samuel
 * Date: 2019/7/12
 * Time: 17:30
 */

namespace App\Migration\DynCode;

use ReflectionException;
use Swoft\Bean\Exception\ContainerException;
use Swoft\Db\Exception\DbException;
use Swoft\Db\Schema;
use Swoft\Db\Schema\Blueprint;
use Swoft\Devtool\Migration\Contract\MigrationInterface;
use Swoft\Devtool\Annotation\Mapping\Migration;

/**
 * Class AddDynCodeTable
 *
 * @package App\Migration\DynCode
 * @Migration(time="20190712174214",pool="db.pool")
 */
class AddDynCodeTable implements MigrationInterface
{
    /**
     * @return void
     *
     * @throws ReflectionException
     * @throws ContainerException
     * @throws DbException
     */
    public function up(): void
    {
        Schema::getSchemaBuilder('db.pool')->createIfNotExists('common_dyn_code', function (Blueprint $blueprint) {
            $blueprint->increments('id')->comment("主键");
            $blueprint->string('app', 20)->comment("属于哪个应用，如 用户、订单、权限等等");// 属于哪个应用，如 用户、订单、权限等等
            $blueprint->string('username', 20)->comment("登陆的用户名");
            $blueprint->dateTime('exp_time')->comment("动态码过期时间");
            $blueprint->tinyInteger('verify_flag')->default(2)->comment("验证标识 1表示验证通过");
            $blueprint->tinyInteger('data_status')->comment("数据是否有效");
            $blueprint->dateTime('add_time')->comment("新增时间");
            $blueprint->dateTime('update_time')->comment("数据更新时间");
        });
    }

    public function down(): void
    {
        Schema::getSchemaBuilder('db.pool')->dropIfExists('common_dyn_code');
    }
}
