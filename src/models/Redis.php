<?php

namespace clip\models;

use Yii;


class Redis
{
    /**
     * @var \Redis
     */
    private static $_redis;

    /**
     * @return \Redis
     */
    public static function conn()
    {
        if (isset(self::$_redis)) {
            return self::$_redis;
        } else {
            self::connect();
            return self::$_redis;
        }
    }

    /**
     * 初始化Redis连接
     */
    private static function connect()
    {
        $host = Yii::$app->params['redis']['host'] ?? '127.0.0.1';
        $port = Yii::$app->params['redis']['port'] ?? 6379;
        self::$_redis = new \Redis();
        if (self::$_redis->connect($host, $port)) {
            if (isset(Yii::$app->params['redis']['auth'])) {
                if (!self::$_redis->auth(Yii::$app->params['redis']['auth'])) {
                    \Yii::error('auth redis fail');
                }
            }
        } else {
            \Yii::error('connect redis fail');
        }
    }
}