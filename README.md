# API development skeleton

帮助你快速搭建 API 项目骨架，并指导你如何使用该骨架的细节。

> 骨架默认开启了 HTTP 请求日志、SQL/Redis 日志，压测前请先关闭 `.env` 的 `APP_DEBUG`

## Installation

```
composer create-project --prefer-dist mix/api-skeleton api
```

## Quick start

> 需要先安装 [Swoole](https://wiki.swoole.com/#/environment) 或者 [WorkerMan](http://doc.workerman.net/install/requirement.html)

启动 Swoole 多进程服务

```
composer run-script --timeout=0 swoole:start
```

启动 Swoole 协程服务

```
composer run-script --timeout=0 swooleco:start
```

启动 WorkerMan 多进程服务

```
composer run-script --timeout=0 workerman:start
```

## Run script

- 命令中的 `--timeout=0` 参数是防止 composer 执行超时 [查看详情](https://getcomposer.org/doc/06-config.md#process-timeout)
- `composer.json` 定义了命令执行脚本，对应上面的执行命令

```json
"scripts": {
    "swoole:start": "php bin/swoole.php start",
    "swooleco:start": "php bin/swooleco.php start",
    "workerman:start": "php bin/workerman.php start",
    "cli:clearcache": "php bin/cli.php clearcache"
},
```

当然也可以直接下面这样启动，效果是一样的，但是 `scripts` 能帮你记录到底有哪些可用的命令，同时在IDE中调试更加方便。

```
php bin/swoole.php start
```

## 编写一个 API 接口

首先修改根目录 `.env` 文件的数据库信息

然后在 `src/Router/Vega.php` 定义一个新的路由

```php
$vega->handleC('/users/{id}', [new Users(), 'index'])->methods('GET');
```

路由里使用了 `Users` 控制器，我们需要创建他

- 服务器文档：[mix-php/vega](https://github.com/mix-php/vega#readme)
- 数据库文档：[mix-php/database](https://github.com/mix-php/database#readme)

```php
<?php

namespace App\Controller;

use App\Container\DB;
use Mix\Vega\Context;

class Users
{

    /**
     * @param Context $ctx
     * @throws \Exception
     */
    public function index(Context $ctx)
    {
        $row = DB::instance()->table('users')->where('id = ?', $ctx->param('id'))->first();
        if (!$row) {
            throw new \Exception('User not found');
        }
        $ctx->JSON(200, [
            'code' => 0,
            'message' => 'ok',
            'data' => $row
        ]);
    }

}
```

重新启动服务器后方可测试新开发的接口

> 实际开发中使用 PhpStorm 的 Run 功能，只需要点击一下重启按钮即可

```
// 查找进程 PID
ps -ef | grep swoole

// 通过 PID 停止进程
kill PID

// 重新启动进程
composer run-script swoole:start

// curl 测试
curl http://127.0.0.1:9501/users/1
```

## 使用容器中的对象

容器采用了一个简单的单例模式，你可以修改为更加适合自己的方式。

- 数据库

```
DB::instance()
```

文档：[mix-php/database](https://github.com/mix-php/database#readme)

- Redis

```
RDS::instance()
```

文档：[mix-php/redis](https://github.com/mix-php/redis#readme)

- 日志

```
Logger::instance()
```

文档：[monolog/monolog](https://seldaek.github.io/monolog/doc/01-usage.html)

- 配置

```
Config::instance()
```

文档：[hassankhan/config](https://github.com/hassankhan/config#getting-values)

## License

Apache License Version 2.0, http://www.apache.org/licenses/
