<?php
/**
 * Created by PhpStorm.
 * User: Samuel
 * Date: 2019/7/13
 * Time: 12:21
 */

namespace App\Model\Logic\Tg;


class MsgTemplate
{
    /**
     * 补单动态码模板
     *
     * @param array $params
     * @return string
     */
    public static function getMessageSimpleStyle($params=[]):string
    {
        $options = [
            'app'=>'',
            'userName' => '',
            'code'     => '',
        ];
        $options = array_merge($options, $params);
        $username = $options['userName'];
        $patttern = '@(\w{3})\w{4}(\w{0,})@';
        $username = preg_replace($patttern, '$1****$2', $username, 1);

        return "【app:{$options['app']}】用户 {$username}，正在申请补单动态码，动态码： {$options['code']}";
    }
}
