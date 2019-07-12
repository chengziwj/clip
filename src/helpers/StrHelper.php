<?php
/**
 * Created by PhpStorm.
 * User: cz
 * Date: 2018/11/20
 * Time: 16:48
 */

namespace clip\helpers;


use yii\helpers\StringHelper;

class StrHelper extends StringHelper
{
    const ENCODING = 'UTF-8';

    /**
     * 截取字符串
     * @param string $str
     * @param int $length 截取后字符串长度
     * @param string $tail
     * @param string $encoding
     * @return mixed
     */
    public static function cut($str, $length, $tail = '...', $encoding = 'UTF-8')
    {
        $strLen = mb_strlen($str, $encoding);
        $tailLen = mb_strlen($tail, $encoding);
        if ($length < 1 || $strLen < $length || $strLen - $length <= $tailLen) {
            return $str;
        } else {
            return mb_substr($str, 0, $length, $encoding) . $tail;
        }
    }

    /**
     * 替换字符串尾部 例如：中英文混合 替换尾部两个字符  中英文**
     * @param $str
     * @param $length
     * @param string $padString
     * @param string $encoding
     * @return mixed
     */
    public static function replaceTail($str, $length, $padString = '*', $encoding = 'UTF-8')
    {
        $strLen = mb_strlen($str, $encoding);
        if ($strLen > $length && $length > 0) {
            $head = mb_substr($str, 0, $strLen - $length, $encoding);
            return $head . self::initPadString($length, $padString);
        } else {
            $length = floor($strLen / 2);
            if ($length > 0) {
                return self::replaceTail($str, $length, $padString, $encoding);
            } else {
                return $str;
            }
        }
    }

    /**
     * 替换字符串头部 例如：中英文混合 替换开头两个字符  **文混合
     * @param $str
     * @param $length
     * @param string $padString
     * @param string $encoding
     * @return mixed|string
     */
    public static function replaceHead($str, $length, $padString = '*', $encoding = 'UTF-8')
    {
        $strLen = mb_strlen($str, $encoding);
        if ($strLen > $length && $length > 0) {
            $tail = mb_substr($str, $length, $strLen - $length + 1, $encoding);
            return self::initPadString($length, $padString) . $tail;
        } else {
            $length = floor($strLen / 2);
            if ($length > 0) {
                return self::replaceHead($str, $length, $padString, $encoding);
            } else {
                return $str;
            }
        }
    }

    /**
     * 替换字符串中间部分 例如：中英文混合 替换开头两个字符  中英**合
     * @param $str
     * @param int $headLen 头部保留长度
     * @param int $tailLen 尾部保留长度
     * @param string $padString
     * @param string $encoding
     * @return mixed
     */
    public static function replaceMiddle($str, $headLen, $tailLen, $padString = '*', $encoding = 'UTF-8')
    {
        $strLen = mb_strlen($str, $encoding);
        if ($strLen == 1) {
            return $str;
        }
        $retain = $headLen + $tailLen;//总的保留字符串长度
        if ($headLen >= $strLen || $tailLen >= $strLen || $retain == $strLen) {
            $headLen = $tailLen = floor($strLen / 2) - 1;
            return self::replaceMiddle($str, $headLen, $tailLen, $padString, $encoding);
        } elseif ($retain > $strLen) {
            if ($headLen > $tailLen) {
                $tailLen = 0;
            } elseif ($headLen < $tailLen) {
                $headLen = 0;
            } else {
                $headLen = $tailLen = floor($strLen / 2) - 1;
            }
            return self::replaceMiddle($str, $headLen, $tailLen, $padString, $encoding);
        } else {
            $head = mb_substr($str, 0, $headLen, $encoding);
            $tail = mb_substr($str, $strLen - $tailLen, $tailLen, $encoding);
            return $head . self::initPadString($strLen - $retain, $padString) . $tail;
        }
    }

    /**
     * 隐藏邮箱地址
     * @param $email
     * @param $headLen
     * @param $tailLen
     * @param string $padString
     * @param string $encoding
     * @return string
     */
    public static function replaceEmail($email, $headLen, $tailLen, $padString = '*', $encoding = 'UTF-8')
    {
        $index = mb_strpos($email, '@', 0, $encoding);
        $head = mb_substr($email, 0, $index);
        $tail = mb_substr($email, $index);
        return self::replaceMiddle($head, $headLen, $tailLen, $padString, $encoding) . $tail;
    }

    /**
     * @param $length
     * @param string $padString
     * @return string
     */
    private static function initPadString($length, $padString = '')
    {
        return str_repeat($padString, $length);
    }

    /**
     * 随机生成手机号
     * @return string
     */
    public static function randomPhone()
    {
        $arr = array(
            130, 131, 132, 133, 134, 135, 136, 137, 138, 139,
            144, 147,
            150, 151, 152, 153, 155, 156, 157, 158, 159,
            176, 177, 178,
            180, 181, 182, 183, 184, 185, 186, 187, 188, 189,
        );
        return $arr[array_rand($arr)] . mt_rand(1000, 9999) . mt_rand(1000, 9999);
    }

    /**
     * base64编码，并且转换为url可传递数据
     * @param $input
     * @return string
     */
    public static function base64UrlEncode($input)
    {
        return strtr(base64_encode($input), '+/', '-_');
    }

    /**
     * base64解码
     * @param $input
     * @return bool|string
     */
    public static function base64UrlDecode($input)
    {
        return base64_decode(strtr($input, '-_', '+/'));
    }
}