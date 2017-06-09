<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/9/30
 * Time: 16:12
 */

namespace App\Transformer;


abstract class Transformer
{
    public function transformController ($items)
    {
        if (count($items) == count($items, 1))
        {
            return array_map([$this, 'transform'], [$items]);
        }
        else
        {
            return array_map([$this, 'transform'], $items);
        }
    }

    public abstract function transform ($item);
}