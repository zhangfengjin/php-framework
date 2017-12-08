<?php
/**
 * Created by PhpStorm.
 * User: fengjin1
 * Date: 2017/12/8
 * Time: 21:51
 */

namespace XYLibrary\Contracts\Redis;


interface Factory
{
    /**
     * @param string $driver 存储设备 如redis\mysql等
     * @return mixed
     */
    function connections($driver = "default");

}