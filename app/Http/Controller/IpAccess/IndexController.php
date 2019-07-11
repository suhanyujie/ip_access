<?php
/**
 * Created by PhpStorm.
 * User: Samuel
 * Date: 2019/7/9
 * Time: 15:13
 */

namespace App\Http\Controller\IpAccess;

use App\Http\Controller\BaseController;
use App\Http\Middleware\ControllerMiddleware;
use App\Model\Dao\IpAccess\IpAccessListDao;
use Swoft\Db\DB;
use Swoft\Http\Message\Request;
use Swoft\Http\Message\Response;
use Swoft\Http\Server\Annotation\Mapping\Controller;
use Swoft\Http\Server\Annotation\Mapping\Middleware;
use Swoft\Http\Server\Annotation\Mapping\Middlewares;
use Swoft\Http\Server\Annotation\Mapping\RequestMapping;
use Swoft\Http\Server\Annotation\Mapping\RequestMethod;
use Throwable;

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
     * @var IpAccessListDao
     */
    protected $ipDao;

    public function __construct()
    {
        parent::__construct();
        $this->ipDao = new IpAccessListDao();
    }

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
                'msg'    => "缺少参数！",
            ];
            return $this->json($result);
        }
        $ips = $this->ipDao->getList([
            'ip_str' => $params['ip'],
            'type'   => 1,
            'limit'  => 1,
        ]);
        if (empty($ips)) {
            $result = ['status'=>-1, 'msg'=>'禁止该 ip'];
        } else {
            $result = ['status'=>1, 'msg'=>'允许该 ip'];
        }

        return $this->json($result);
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
    public function add(Request $request)
    {
        $postData = $request->post();
        $result = $this->ipDao->add($postData);
        return $this->json($result);
    }

    /**
     * 只支持根据主键以及 ip_str 字段 update 数据
     * @RequestMapping("/ip/update")
     * @throws Throwable
     */
    public function edit(Request $request)
    {
        $postData = $request->post();
        $result = $this->ipDao->update([
            'id' => $postData['id'] ?? null,
        ], $postData);

        return $this->json($result);
    }

    /**
     * @RequestMapping("/ip/delete")
     * @throws Throwable
     */
    public function delete(Request $request)
    {
        $postData = $request->post();
        $isSoft = $postData['is_soft'] ?? 1;
        $result = $this->ipDao->delete([
            'id'=>$postData['id'] ?? 0,
        ], $isSoft);
        return $this->json($result);
    }
}
