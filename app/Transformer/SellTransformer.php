<?php

namespace App\Transformer;

/**
 * Class SellTransformer
 *
 * @package \App\Transformer
 */
class SellTransformer extends Transformer
{
    public function transform ($item)
    {
        return [
            'id' => "".$item['id']."",
            'sell_title' => "".$item['sell_title']."",
            'sell_pic' => $item['sell_pic'],
            'cate_id' => "".$item['cate_id']."",
            'sell_describe' => "".$item['sell_describe']."",
            'sell_price' => "".$item['money'],
            'sell_area' => "".$item['sell_area']."",
            'is_sell' => "".$item['is_sell']."",
            'sell_time' => "".$item['sell_time']."",
            'sell_uid' => "".$item['sell_uid']."",
            'user_face' => "".config('web.QINIU_URL').'/'.$item['user_face']."",
            'nickname' => "".$item['nickname']."",
            'phone' => "".$item['phone']."",
            'cate_name' => "".$item['cate_name']."",
            'sell_thumbsUp' => "".$item['sell_thumbsUp']."",
            'sell_comment' => "".$item['sell_comment']."",
            'is_thumbsUp' => "".$item['is_thumbsUp'].""
        ];
    }
}
