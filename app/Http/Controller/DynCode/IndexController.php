<?php
/**
 * Created by PhpStorm.
 * User: Samuel
 * Date: 2019/7/12
 * Time: 17:11
 */

namespace App\Http\Controller\DynCode;

use App\Http\Controller\BaseController;
use App\Http\Middleware\ControllerMiddleware;
use App\Model\Dao\DynCode\CommonDynCodeDao;
use App\Model\Logic\DynCode\DynCodeIndexLogic;
use Swoft\Http\Message\Request;
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
    /**
     * @var CommonDynCodeDao
     */
    protected $dao;

    /**
     * @var DynCodeIndexLogic
     */
    protected $logic;

    public function __construct()
    {
        parent::__construct();
        $this->dao = new CommonDynCodeDao();
        $this->logic = new DynCodeIndexLogic();
    }

    /**
     * 检查用户是否需要新的动态码
     *
     * @RequestMapping(route="/dc/checkNeedNewDynCode",method=RequestMethod::GET)
     * @throws Throwable
     */
    public function checkNeedNewDynCode(Request $request)
    {
        $username = $request->get('username', '');
        $dynCodeData = $this->dao->getList([
            'username' => $username,
            'orderBy'=>['id'=>'desc'],
            'limit'    => 1,
        ]);
        $result = [
            'status'=>1,
            'data'=>$dynCodeData,
        ];
        if (empty($dynCodeData) || count($dynCodeData) < 1) {
            $result['status'] = -1;
            $result['msg'] = '该用户不存在动态码！';
        }
        return $this->json($result);
    }

    /**
     * 生成新的动态码
     *
     * @RequestMapping(route="/dc/generateNewDynCode",method=RequestMethod::POST)
     * @throws Throwable
     */
    public function generateNewDynCode(Request $request)
    {
        $username = $request->post('username','');

    }

    /**
     * 验证动态码是否正确，正确后，标识缓存为：已验证
     *
     * @RequestMapping(route="/dc/verifyDynCode",method=RequestMethod::POST)
     * @throws Throwable
     */
    public function verifyDynCode()
    {

    }

    /**
     * 验证动态码是否可用，是否在有效期内，一个动态码有效期 5min
     *
     * @RequestMapping(route="/dc/checkDynCodeIsOk",method=RequestMethod::POST)
     * @throws Throwable
     */
    public function checkDynCodeIsOk()
    {

    }
}
