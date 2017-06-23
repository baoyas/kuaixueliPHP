<?php

namespace App\Transformer;

/**
 * Class FriendsTransformer
 *
 * @package \App\Transformer
 */
class FriendsTransformer extends Transformer
{
    public function transform ($item)
    {
        return [
            'id' => "".$item['id']."",
            'sell_title' => "".$item['sell_title']."",
            'sell_pic' => $item['sell_pic'],
            'sell_describe' => "".$item['sell_describe']."",
            'sell_time' => "".$item['sell_time']."",
            'sell_uid' => "".$item['sell_uid']."",
            'sell_price' => "".$item['money'],
            'is_sell' => "".$item['is_sell']."",
            'is_circle' => "".$item['is_circle']."",
            'sell_video' => "".$item['sell_video']."",
            'sell_video_pic' => "".$item['sell_video_pic']."",
            'user_face' => "".$item['user_face']."",
            'nickname' => "".$item['nickname']."",
            'phone' => "".$item['phone']."",
            'sell_thumbsUp' => "".$item['sell_thumbsUp']."",
            'sell_comment' => "".$item['sell_comment']."",
            'is_thumbsUp' => "".$item['is_thumbsUp']."",
        ];
    }
}
