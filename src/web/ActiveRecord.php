<?php
/**
 * Created by PhpStorm.
 * User: cz
 * Date: 2018/11/20
 * Time: 17:36
 */

namespace clip\base;


class ActiveRecord extends \yii\db\ActiveRecord
{
    /**
     * @param null $sql
     * @param array $params
     * @return \yii\db\Command
     */
    public static function createCommand($sql = null, $params = [])
    {
        return static::getDb()->createCommand($sql, $params);
    }

    /**
     * @param $sql
     * @param array $values
     * @return int
     * @throws \yii\db\Exception
     */
    public static function execute($sql, array $values)
    {
        return self::getDb()->createCommand($sql)->bindValues($values)->execute();
    }

    /**
     *
     * @param null $isolationLevel
     * @return \yii\db\Transaction
     */
    public static function beginTransaction($isolationLevel = null)
    {
        return static::getDb()->beginTransaction($isolationLevel);
    }

    /**
     * @return false|null|string
     * @throws \yii\db\Exception
     */
    public static function getLastId()
    {
        return self::createCommand('SELECT LAST_INSERT_ID()')->queryScalar();
    }

    /**
     * SQL参数添加引号
     * @param $str
     * @return string
     */
    public static function quoteValue($str)
    {
        if (!is_string($str)) {
            return $str;
        }
        return "'" . addcslashes(str_replace("'", "''", $str), "\000\n\r\\\032") . "'";
    }
}