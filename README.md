# API development skeleton

帮助你快速搭建 API 项目骨架，并指导你如何使用该骨架的细节。

## Installation

```
composer create-project --prefer-dist mix/api-skeleton api
```

## Quick start

> 需要先安装 [Swoole](https://wiki.swoole.com/#/environment) 或者 [WorkerMan](http://doc.workerman.net/install/requirement.html)

启动 Swoole 多进程服务

```
composer run-script swoole:start
```

启动 Swoole 协程服务

```
composer run-script swooleco:start
```

启动 WorkerMan 多进程服务

```
composer run-script workerman:start
```

## 启动脚本

`composer.json` 定义了启动的脚本，对应上面的执行命令

```json
"scripts": {
    "swoole:start": "php bin/swoole.php start",
    "swooleco:start": "php bin/swooleco.php start",
    "workerman:start": "php bin/workerman.php start"
},
```

`.php` 文件就是程序的入口文件，可以根据自己的需求修改

## 编写一个 API 接口

首先修改 `.env` 文件的数据库信息

```
# DATABASE
DATABASE_DSN='mysql:host=127.0.0.1;port=3306;charset=utf8;dbname=test'
DATABASE_USERNAME=root
DATABASE_PASSWORD=123456

# REDIS
REDIS_HOST=127.0.0.1
REDIS_PORT=6379
REDIS_DATABASE=0
REDIS_PASSWORD=
```

在 `src/Router/Vega.php` 定义一个新的路由

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

文档：[hassankhan/config](https://seldaek.github.io/monolog/doc/01-usage.html)

- 配置

```
Config::instance()
```

文档：[hassankhan/config](https://github.com/hassankhan/config#getting-values)

## License

Apache License Version 2.0, http://www.apache.org/licenses/
