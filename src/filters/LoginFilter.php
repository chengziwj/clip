<?php
/**
 * Created by PhpStorm.
 * User: cz
 * Date: 2018/11/20
 * Time: 17:57
 */

namespace clip\filters;


use clip\interfaces\AuthInterface;
use yii\base\ActionFilter;

class LoginFilter extends ActionFilter
{
    /**
     * @var AuthInterface
     */
    public $auth;

    /**
     * @var string 登录页面
     */
    public $loginUrl = '/login';

    /**
     * @param \yii\base\Action $action
     * @return bool
     */
    public function beforeAction($action)
    {
        if ($this->auth->isLogin()) {
            return true;
        } else {
            $this->loginRequired();
            return false;
        }
    }

    private function loginRequired()
    {
        return \Yii::$app->getResponse()->redirect($this->loginUrl);
    }
}