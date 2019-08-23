## 一些服务

## requirements
* [swoole 4.3.5](https://github.com/swoole/swoole-src/releases/tag/v4.3.5)
* redis 扩展 https://pecl.php.net/package/redis

## install
* composer install

### 启动
* `php bin/swoft -h` 查看相关命令

## 常见问题
### 提示
* 错误提示为 Fatal error: Uncaught ErrorException: Uncaught Swoole\Error: API must be called in the coroutine in 
* 此时 `./bin/swoft` 文件中注释掉 `\Swoole\Runtime::enableCoroutine();`

## ip access
* 基于 [swoft 2](https://www.swoft.org/docs/2.x/zh-CN) 的 ip 访问控制服务

## 接口说明
### 返回值说明
* 返回一般包含 3 个字段

|字段 | 描述 | 是否必填 |备注|
|:-----|:-----|:-----|:-----|
| `status` | 状态码| 1  | 无|
| `data` | 返回的数据 | 0  | 无|
| `msg` | 异常时的提示信息 | 0  | 无|

## Rest API
### 检查 ip
* `/ip/check`
* 通过 http 请求，查看参数 ip 是否位于 ip 白名单中

#### 参数列表

|参数字段名 | 描述 | 是否必填 |备注|
|:-----|:-----|:-----|:-----|
| `ip` | 检查的 ip 值 | 1  | 无 |

### 获取 ip 列表
* `/ip/index`
* 通过 http 请求，查看 ip 白名单中的 ip 列表

### 新增 ip
* `/ip/add`
* 通过 http 请求，向 ip 白名单中新增 ip 样本

### 更新 ip
* `/ip/update`
* 通过 http 请求，更新 ip 白名单中 ip 信息

#### 参数列表

|参数字段名 | 描述 | 是否必填 |备注|
|:-----|:-----|:-----|:-----|
| `id` | ip 信息主键| 1  | 无 |
| `ip_str` | 要更新的目标 ip 值| 1  | 无 |

### 删除 ip
* `/ip/delete`
* 通过 http 请求，删除 ip 白名单中的 ip 信息。软删除，应删除都支持

#### 参数列表

|参数字段名 | 描述 | 是否必填 |备注|
|:-----|:-----|:-----|:-----|
| `id` | ip 信息主键| 1  | 无|
| `is_soft` | 是否软删除 1表示软删除；0表示硬删除；默认软删除| 0  | 无|

## 其他

### telegram 获取 channel 或者 group 的 id
* https://api.telegram.org/bot{botId}/getUpdates
* 参考 https://stackoverflow.com/questions/32423837/telegram-bot-how-to-get-a-group-chat-id
