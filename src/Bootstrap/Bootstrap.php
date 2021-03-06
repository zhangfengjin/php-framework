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
use XYLibrary\Utils\Filesystem;

class Bootstrap
{
    protected $app;
    protected $initConfig;

    protected $dirs = [
        'form' => __DIR__ . "/../Config/",
        'to' => __DIR__ . "/../../../../../Config/"
    ];

    public function __construct($initConfig = true)
    {
        require_once __DIR__ . "/../Utils/helpers.php";
        $this->initConfig = $initConfig;
        $this->app = Container::getInstance();
        Facade::setFacadeApplication($this->app);
    }

    /**
     * 初始化Config
     */
    protected function autoInitConfig()
    {
        $form = $this->dirs['form'];
        if ($this->initConfig && file_exists($form)) {
            //@rename($this->dirs['form'], $this->dirs['to']);
            copyDir($form, $this->dirs['to']);
        }
    }

    /**
     * 设置config加载路径
     * @param array $dirs
     */
    public function setConfigDirs(array $dirs)
    {
        $this->dirs = array_merge($this->dirs, $dirs);
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
    public function bootstrap($bootStraps = [])
    {
        $this->autoInitConfig();
        $this->app["app"] = $this->app;
        $this->app[get_class($this->app)] = $this->app;
        $this->registerException();
        $this->registerFilesystem();
        $this->registerConfig();
        $this->registerRedis();
        foreach ($bootStraps as $abstract) {
            $instance = $this->app[$abstract];
            if (method_exists($instance, "register")) {
                $instance->register();
            }
        }
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
            foreach ($this->dirs as $dir) {
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
                }
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

    /**
     *注册文件操作类
     */
    protected function registerFilesystem()
    {
        $this->app->bind("files", function ($app) {
            return new Filesystem();
        });
    }
}