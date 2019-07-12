<?php
/**
 * Created by PhpStorm.
 * User: cz
 * Date: 2018/11/20
 * Time: 17:56
 */

namespace clip\interfaces;


interface AuthInterface
{
    /**
     * 获取实例
     * @return mixed
     */
    public static function getInstance();

    /**
     * 判断是否登录
     * @return mixed
     */
    public function isLogin();
}