<?php
/**
 * Created by PhpStorm.
 * User: cz
 * Date: 2018/11/20
 * Time: 16:36
 */

namespace clip\web;

use Yii;
use yii\helpers\Html;
use yii\web\Cookie;
use yii\web\NotFoundHttpException;
use clip\models\Result;
use clip\models\Error;
use clip\models\Pagination;

class Controller extends \yii\web\Controller
{
    /**
     * @var \yii\web\Request
     */
    private $_request;

    /**
     * @return \yii\console\Request|\yii\web\Request
     */
    public function getRequest()
    {
        if (isset($this->_request)) {
            return $this->_request;
        } else {
            $this->_request = Yii::$app->request;
            return $this->_request;
        }
    }

    /**
     * 判断是否ajax请求
     * @return bool
     */
    public function isAjax()
    {
        return $this->getRequest()->getIsAjax();
    }

    /**
     * 判断是否get请求
     * @return bool
     */
    public function isGet()
    {
        return $this->getRequest()->getIsGet();
    }

    /**
     * 判断是否post请求
     * @return bool
     */
    public function isPost()
    {
        return $this->getRequest()->getIsPost();
    }

    /**
     * 判断是否delete
     * @return bool
     */
    public function isDelete(){
        return $this->getRequest()->getIsDelete();
    }

    /**
     * 判断是否put
     * @return bool
     */
    public function isPut(){
        return $this->getRequest()->getIsPut();
    }

    /**
     * 获取get参数
     * @param $key
     * @param null $default
     * @return null
     */
    public function get($key, $default = null)
    {
        return $this->getRequest()->get($key, $default);
    }

    /**
     * 获取get string参数
     * @param $key
     * @param string $default
     * @return string
     */
    public function getString($key, $default = '')
    {
        return trim($this->get($key, $default));
    }

    /**
     * 获取get类型 int 参数
     * @param $key
     * @param int $default 默认参数
     * @return int
     */
    public function getInt($key, $default = 0)
    {
        $val = $this->get($key);
        if (is_numeric($val)) {
            return intval($val);
        }
        return $default;
    }

    /**
     * 或get类型 float 参数
     * @param $key
     * @param float $default
     * @return float
     */
    public function getFloat($key, $default = 0.0)
    {
        $val = $this->get($key);
        if (is_numeric($val)) {
            return floatval($val);
        }
        return $default;
    }

    /**
     * 获取时间参数，并且转换为时间戳
     * @param $key
     * @param int $default
     * @return false|int
     */
    public function getTime($key, $default = 0)
    {
        $time = strtotime($this->get($key));
        return $time !== false ? $time : $default;
    }

    /**
     * 获取对应日期开始时间戳
     * @param $key
     * @return false|int|null
     */
    public function getStartTime($key)
    {
        $time = $this->getTime($key);
        if ($time > 0) {
            return strtotime("midnight", $time);
        }
        return null;
    }

    /**
     * 获取对应日期结束时间戳
     * @param $key
     * @return false|int|null
     */
    public function getEndTime($key)
    {
        $time = $this->getTime($key);
        if ($time > 0) {
            return strtotime("tomorrow", $time) - 1;
        }
        return null;
    }


    /**
     * @param $key
     * @param null $default
     * @return null
     */
    public function post($key = null, $default = null)
    {
        return $this->getRequest()->post($key, $default);
    }

    /**
     * @param $key
     * @param string $default
     * @return string
     */
    public function postString($key, $default = '')
    {
        return $this->encode(trim($this->post($key, $default)));
    }

    /**
     * @param $key
     * @param int $default
     * @return int
     */
    public function postInt($key, $default = 0)
    {
        $val = $this->post($key);
        if (is_numeric($val)) {
            return intval($val);
        }
        return $default;
    }

    /**
     * @param $key
     * @param bool $filter_zero 是否过滤0
     * @param string $delimiter
     * @return array|null
     */
    public function postIntArray($key, $filter_zero = true, $delimiter = ',')
    {
        $val = $this->post($key);
        if ($val) {
            $vals = explode($delimiter, $val);
            return array_filter($vals, function ($v) use ($filter_zero) {
                return $filter_zero ? (is_numeric($v) && $v > 0) : is_numeric($v);
            });
        }
        return null;
    }

    /**
     * @param $key
     * @param float $default
     * @return float
     */
    public function postFloat($key, $default = 0.0)
    {
        $val = $this->post($key);
        if (is_numeric($val)) {
            return floatval($val);
        }
        return $default;
    }

    /**
     *
     * @param $key
     * @param array $default
     * @return array|mixed
     */
    public function postJson($key, $default = [])
    {
        $json = json_decode($this->post($key), true);
        return $json ? $json : $default;
    }

    public function encode($str)
    {
        return Html::encode($str);
    }

    /**
     * @param $name
     * @param $value
     * @param int $expire
     */
    public function setCookie($name, $value, $expire = 0)
    {
        $cookies = Yii::$app->response->cookies;
        $cookies->add(new Cookie([
            'name' => $name,
            'value' => $value,
            'expire' => $expire
        ]));
    }

    /**
     * @param $name
     */
    public function removeCookie($name)
    {
        $cookies = Yii::$app->response->cookies;
        return $cookies->remove($name);
    }

    /**
     * 获取cookie
     * @param $name
     * @param string $default
     * @return mixed
     */
    public function getCookie($name, $default = '')
    {
        $cookies = Yii::$app->request->cookies;
        return $cookies->getValue($name, $default);
    }

    /**
     * 返回成功
     * @param string $msg
     * @param array $data
     * @return \yii\web\Response
     */
    public function success($msg = '操作成功', $data = [])
    {
        $rs = new Result(true, Error::SUCCESS, $msg, $data);
        return $this->asJson($rs);
    }

    /**
     * 返回失败
     * @param string $msg 错误信息
     * @param int $code 错误码
     * @param array $data
     * @return \yii\web\Response
     */
    public function fail($msg = '操作失败', $code = -1, $data = [])
    {
        if (is_array($msg)) {
            $rs = '';
            foreach ($msg as $key => $val) {
                $rs .= implode('<br />', $val);
            }
            $msg = $rs;
        }
        $rs = new Result(false, $code, $msg, $data);
        return $this->asJson($rs);
    }

    /**
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function notFound()
    {
        if (Yii::$app->request->isAjax) {
            return $this->fail('未找到资源', Error::NOT_FOUND);
        } else {
            throw new NotFoundHttpException();
        }
    }

    /**
     * 获取分页对象
     * @return Pagination
     */
    public function page()
    {
        $start = $this->getInt('start');
        $length = $this->getInt('length');
        $pages = new Pagination($start, $length);

        $pages->order = $this->get('order');
        $pages->draw = $this->get('draw');
        return $pages;
    }

}