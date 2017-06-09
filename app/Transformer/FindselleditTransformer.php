<?php

namespace App\Transformer;

/**
 * Class FindselleditTransformer
 *
 * @package \App\Transformer
 */
class FindselleditTransformer extends Transformer
{
    public function transform ($item)
    {
        return [
            'id' => "".$item['id']."",
            'sell_title' => $item['sell_title'],
            'sell_pic' => $item['sell_pic'],
            'cate_id' => "".$item['cate_id']."",
            'sell_describe' => "".$item['sell_describe']."",
            'sell_price' => "".$item['sell_price']."",
            'sell_price_max' => "".$item['sell_price_max']."",
            'sell_area' => "".$item['sell_area']."",
            'sell_auth' => "".$item['sell_auth']."",
            'is_sell' => "".$item['is_sell']."",
            'cate_name' => "".$item['cate_name']."",
        ];
    }
}
