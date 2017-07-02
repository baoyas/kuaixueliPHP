<?php

namespace App\Transformer;
use Illuminate\Support\Facades\Config;

/**
 * Class UserTransformer
 *
 * @package \App\Transformer
 */
class UsersTransformer extends Transformer
{
    public function transform ($item)
    {
        return [
            'uid' => "".$item['id']."",
            'nickname' => "".$item['nickname']."",
            'phone' => "".$item['phone']."",
            'accounts' => "".$item['accounts']."",
            'address' => "".$item['address']."",
            'sex' => "".$item['sex']."",
            'area' => "".$item['area']."",
            'autograph' => "".$item['autograph']."",
            'user_face' => Config::get('web.QINIU_URL').'/'.$item['user_face'],
            'backgroud_pic' => Config::get('web.QINIU_URL').'/'.$item['backgroud_pic'],
            'points' => $item['points'],
        ];
    }
}
