<?php
/**
 * Created by PhpStorm.
 * User: Samuel
 * Date: 2019/7/13
 * Time: 11:52
 */

namespace App\Model\Logic\Tg;

use App\Model\Logic\Common\HttpRequest;
use App\Model\Logic\Tg\MsgTemplate;

class MessageLogic
{
    public static function sendSecurityCodeToTgChannel($params=[]):array
    {
        $options = [
            'message' => '',
        ];
        $options = array_merge($options, $params);
        if (empty($options['message'])) {
            return ['status'=>500031, 'msg'=>'缺少参数！'];
        }
        $message = $options['message'];
        $botCode = env('TG_DYN_CODE_BOT_CODE', '');
        $chatId = env('TG_DYN_CODE_CHANNEL_ID', '');
        $url = "https://api.telegram.org/bot{$botCode}/sendMessage?chat_id={$chatId}";
        $res = HttpRequest::curlHttp([
            'url'      => $url,
            'method'   => 'post',
            'postData' => [
                'text' => $message,
            ],
            'bodyType' => 2,
            'timeout'  => 5,
        ]);
        if ($res['status'] != 1) {
            return $res;
        }
        $response = $res['data'];
        $responseArr = json_decode($response, true);
        if ($responseArr['ok'] != true) {
            return [
                'status' => 500039,
                'msg'    => json_encode($responseArr['result'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            ];
        }

        return ['status'=>1, 'msg'=>'发送成功！'];
    }

    // 发送安全码信息到 telegram 中
    public static function sendSecurityCodeToTgGroup($params=[])
    {
        $options = [
            'codeInfo' => [],// 安全码信息
        ];
        $options = array_merge($options, $params);
        if (empty($options['codeInfo'])) {
            return ['status'=>500031, 'msg'=>'缺少参数！'];
        }
        global $envConfig;
        if (empty($options['codeInfo']['message'])) {
            $message = MsgTemplate::getMessageSimpleStyle($options['codeInfo']);
        } else {
            $message = $options['codeInfo']['message'];
        }
        $url = "https://api.telegram.org/bot{$envConfig['telegramBot']['botCode']}/sendMessage?chat_id={$envConfig['telegramBot']['chatId']}";
        $res = HttpRequest::curlHttp([
            'url'      => $url,
            'method'   => 'post',
            'postData' => [
                'text' => $message,
            ],
            'bodyType' => 2,
            'timeout'  => 5,
        ]);
        if ($res['status'] != 1) {
            return $res;
        }
        $response = $res['data'];
        $responseArr = json_decode($response, true);
        if ($responseArr['ok'] != true) {
            return [
                'status' => 500039,
                'msg'    => json_encode($responseArr['result'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            ];
        }

        return ['status'=>1, 'msg'=>'发送成功！'];
    }
}
