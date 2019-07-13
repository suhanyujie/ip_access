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
use App\Model\Logic\Tg\MessageLogic;
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

    protected $data = [];

    public function __construct()
    {
        parent::__construct();
        $this->dao = new CommonDynCodeDao();
        $this->logic = new DynCodeIndexLogic();
    }

    /**
     * 检查用户动态码是否可用
     * 检查用户是否需要新的动态码。过期的动态码、尚未生成动态码都需要生成动态码的条件
     *
     * @RequestMapping(route="/dc/checkNeedNewDynCode",method={RequestMethod::GET})
     * @throws Throwable
     */
    public function checkNeedNewDynCode(Request $request)
    {
        $username = $request->get('username', '');
        $result = $this->logic->checkDynCodeIsOk([
            'username'=>$username,
        ]);

        return $this->json($result);
    }

    /**
     * 生成新的动态码。不仅生成动态码，还有相关的信息
     *
     * @RequestMapping(route="/dc/generateNewDynCode",method=RequestMethod::POST)
     * @throws Throwable
     */
    public function generateNewDynCode(Request $request)
    {
        $result = $this->logic->generateDynCodeInfo([
            'app'      => $request->post('app', ''),
            'username' => $request->post('username', ''),
        ]);

        return $this->json($result);
    }

    /**
     * 验证用户输入的动态码是否正确，正确后，标识缓存为：已验证
     *
     * @RequestMapping(route="/dc/verifyDynCode",method=RequestMethod::POST)
     * @throws Throwable
     */
    public function verifyDynCode(Request $request)
    {
        $app = $request->post('app','');
        $username = $request->post('username','');
        $dynCode = $request->post('dynCode','');
        $result = $this->logic->verifyDynCode([
            'app'      => $app,
            'username' => $username,
            'dynCode'  => $dynCode,
        ]);
        return $this->json($result);
    }

    /**
     * 验证动态码是否在有效期内，一个动态码有效期 5min
     * 先确定该用户的动态码是否存在，在确定其动态码是在有效期内
     *
     * @RequestMapping(route="/dc/checkDynCodeExpTime",method=RequestMethod::POST)
     * @throws Throwable
     */
    public function checkDynCodeExpTime(Request $request)
    {
        $username = $request->post('username','');
        $result = $this->logic->checkDynCodeExpTime([
            'username'=>$username,
        ]);
        return $this->json($result);
    }

    /**
     * @RequestMapping(route="/dc/sendTgMsg",method=RequestMethod::POST)
     * @throws Throwable
     */
    public function sendTgMsg(Request $request)
    {
        // input 中包含 message 字段
        $input = $request->post();
        $result = MessageLogic::sendSecurityCodeToTgChannel([
            'message'=>$input['message'],
        ]);
        return $this->json($result);
    }
}
