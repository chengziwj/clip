<?php
/**
 * Created by PhpStorm.
 * User: cz
 * Date: 2018/11/20
 * Time: 16:46
 */

namespace clip\models;


abstract class Error
{
    /**
     * 请求成功
     */
    const SUCCESS = 200;

    /**
     * 请求参数有误
     */
    const PARAM = 400;

    /**
     * 没有操作权限
     */
    const FORBIDDEN = 403;

    /**
     * 访问资源不存在
     */
    const NOT_FOUND = 404;

    /**
     * 未知错误
     */
    const UNKNOWN = 500;

    /**
     * 身份验证失败
     */
    const MIS_TOKEN = 401;

}