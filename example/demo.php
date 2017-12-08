<?php
require __DIR__ . "/../vendor/autoload.php";
//项目加载
$bootStrap = new \XYLibrary\Bootstrap\Bootstrap();
$bootStrap->bootstrap();
//获取项目容器
$app = $bootStrap->getContainer();
//注入到容器
$app->bind("test", function () {
    return new Test();
});
//解析类
$app["test"]->fly();


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
