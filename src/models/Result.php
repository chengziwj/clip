<?php
/**
 * Created by PhpStorm.
 * User: cz
 * Date: 2018/11/20
 * Time: 16:45
 */

namespace clip\models;


class Result
{
    public $succ;
    public $code;
    public $msg;
    public $data;

    /**
     * Result constructor.
     * @param bool $succ
     * @param int $code 对应\app\common\Error.php
     * @param string $msg
     * @param array $data
     */
    public function __construct(bool $succ, int $code, string $msg, array $data = [])
    {
        $this->succ = $succ;
        $this->code = $code;
        $this->msg = $msg;
        $this->data = $data;

    }

    public function __set($name, $value)
    {
        // TODO: Implement __set() method.
        $this->data[$name] = $value;
    }

    public static function success($msg = '', $data = [])
    {
        return new self(true, 200, $msg, $data);
    }

    public static function fail($code, $msg, $data = [])
    {
        return new self(false, $code, $msg, $data);
    }
}