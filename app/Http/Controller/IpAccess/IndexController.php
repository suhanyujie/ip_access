<?php
/**
 * Created by PhpStorm.
 * User: Samuel
 * Date: 2019/7/9
 * Time: 15:13
 */

namespace App\Http\Controller\IpAccess;

use App\Http\Controller\BaseController;
use ReflectionException;
use Swoft\Context\Context;
use Swoft\Http\Message\ContentType;
use Swoft\Http\Message\Request;
use Swoft\Http\Message\Response;
use Swoft\Http\Server\Annotation\Mapping\Controller;
use Swoft\Http\Server\Annotation\Mapping\RequestMapping;
use Swoft\Http\Server\Annotation\Mapping\Middleware;
use Throwable;
use App\Http\Middleware\ControllerMiddleware;
use Swoft\Http\Server\Annotation\Mapping\Middlewares;
use Swoft\Db\DB;
use App\Model\Entity\MongoPoly\IpAccessList;
use Swoft\Http\Server\Annotation\Mapping\RequestMethod;
use App\Exception\Handler\IpAccess\IpBanExceptionHandler;

/**
 * Class IndexController
 * @package App\Http\Controller\IpAccess
 * @Controller()
 * @Middlewares({
 *      @Middleware(ControllerMiddleware::class)
 * })
 */
class IndexController extends BaseController
{
    protected $tableName = 'ip_access_list';
    /**
     * ip 检查
     * @RequestMapping(route="/ip/check",method=RequestMethod::POST)
     * @throws Throwable
     */
    public function checkIp(Request $request)
    {
        $params = $request->post();
        if (empty($params['ip'])) {
            $result = [
                'status' => -1,
                'msg'    => "禁止",
            ];
            return $this->json($result);
        }

        return $this->json($params);
    }

    /**
     * 列表
     *
     * curl http://127.0.0.1:18306/test/index
     * @RequestMapping("/ip/index")
     * @throws Throwable
     */
    public function index(Request $request): Response
    {
        $params = $request->get();
        $page = $params['page'] ?? 1;
        $limit = 10;
        $start = $page > 0 ? $limit*$page : 0;
        $ips = DB::table($this->tableName)
            ->where('data_status', '=', 1)
            ->skip($start)
            ->limit($limit)
            ->get();
        $result = [
            'status'=>1,
            'data'=>$ips,
        ];

        return $this->json($result);
    }

    /**
     * 新增 ip
     *
     * @RequestMapping("/ip/add")
     * @throws Throwable
     */
    public function add()
    {

    }

    /**
     * @RequestMapping("/ip/edit")
     * @throws Throwable
     */
    public function edit()
    {

    }

    /**
     * @RequestMapping("/ip/delete")
     * @throws Throwable
     */
    public function delete()
    {

    }
}
