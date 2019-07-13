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
    public static function getMessageSimpleStyle($params=[]):string
    {
        $options = [
            'app'=>'',
            'userName' => '',
            'code'     => '',
        ];
        $options = array_merge($options, $params);

        return "【app:{$options['app']}】用户 {$options['userName']}，正在申请动态码，动态码： {$options['code']}";
    }
}
