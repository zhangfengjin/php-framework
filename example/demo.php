<?php

require __DIR__ . "/../vendor/autoload.php";
//项目加载 真实项目中参数改为true--用于自动生成项目配置文件(数据库、redis等)
$bootStrap = new \XYLibrary\Bootstrap\Bootstrap(false);
$bootStrap->bootstrap();
//获取项目容器
$app = $bootStrap->getContainer();
//注入
$app->bind("test", function () {
    return new Test();
});
//解析类
$app["test"]->fly();

echo tap(123, function ($value) {
    echo "asd";
});

class Test
{
    public function __construct()
    {
        echo "class Test init!\r\n";
    }

    public function fly()
    {
        echo "this test is success!\r\n";
    }
}
