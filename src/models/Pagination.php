<?php
/**
 * Created by PhpStorm.
 * User: cz
 * Date: 2018/11/20
 * Time: 17:21
 */

namespace clip\models;

use yii\db\QueryInterface;


class Pagination
{
    public $start;
    public $total;
    public $offset;
    public $limit;

    public $draw;
    public $order;
    public $data;
    public $query;

    /**
     * Pagination constructor.
     * @param $offset
     * @param $limit
     */
    public function __construct($offset, $limit)
    {
        $this->limit = $limit;
        $this->offset = $offset;
    }


    /**
     * @return array
     */
    public function params()
    {
        return [
            "draw" => $this->draw,
            "recordsTotal" => $this->total,
            "recordsFiltered" => $this->total,
            'offset' => $this->offset,
            'data' => $this->data
        ];
    }

    /**
     * @param QueryInterface $query
     * @return array
     */
    public function fetchAll(QueryInterface $query)
    {
        return $query->offset($this->offset)->limit($this->limit)->all();
    }
}