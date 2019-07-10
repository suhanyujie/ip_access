<?php
/**
 * Created by PhpStorm.
 * User: Samuel
 * Date: 2019/7/10
 * Time: 14:03
 */

namespace App\Model\Entity\MongoPoly;

use Swoft\Db\Annotation\Mapping\Entity;
use Swoft\Db\Annotation\Mapping\Column;
use Swoft\Db\Annotation\Mapping\Id;
use Swoft\Db\Eloquent\Model;

/**
 * Class IpAccessList
 * @package App\Model\Entity\MongoPoly
 * @Entity(table="ip_access_list",pool="db")
 */
class IpAccessList extends Model
{
    /**
     * @Id(incrementing=true)
     *
     * @Column(name="id", prop="id")
     * @var int|null
     */
    private $id;
}
