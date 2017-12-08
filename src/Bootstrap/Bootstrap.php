<?php
/**
 * 启动
 * User: zhangfengjin
 * Date: 2017/11/29
 */

namespace XYLibrary\Bootstrap;

use XYLibrary\Exception\ExceptionHandler;
use XYLibrary\Facade\Facade;
use XYLibrary\IoC\Container;
use XYLibrary\Support\Redis\RedisManager;

class Bootstrap
{
    protected $app;

    public function __construct()
    {
        $this->app = new Container();
        Facade::setFacadeApplication($this->app);
        require_once __DIR__ . "/../Utils/helpers.php";
    }

    /**
     * 获取容器对象
     * @return IoC\Container
     */
    public function getContainer()
    {
        return $this->app;
    }

    /**
     * 启动
     */
    public function bootstrap()
    {
        $this->registerException();
        $this->registerConfig();
        $this->registerRedis();
    }

    /**
     * 注册错误
     */
    protected function registerException()
    {
        $exception = new ExceptionHandler();
        $exception->handler();
    }

    /**
     * 注册配置信息
     */
    protected function registerConfig()
    {
        $this->app->bind("config", function ($app) {
            $configs = [];
            $dir = __DIR__ . "/../Config/";
            if (is_dir($dir)) {
                if ($handler = opendir($dir)) {
                    while (($file = readdir($handler)) !== false) {
                        $paths = pathinfo($file);
                        if ($file != "." && $file != ".."
                            && strtolower($paths["extension"]) == "php"
                        ) {
                            $configs[$paths["filename"]] = require $dir . $file;
                        }
                    }
                }
            } else {
                throw new \RuntimeException("load config error,no exists $dir");
            }
            return $configs;
        });
    }

    /**
     * 注册redis
     */
    protected function registerRedis()
    {
        $this->app->bind("redis", function ($app) {
            $config = $app["config"]["database"]["redis"];
            return new RedisManager($config["client"], $config);
        });
    }
}