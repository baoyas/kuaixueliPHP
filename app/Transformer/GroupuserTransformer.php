<?php

namespace App\Transformer;

/**
 * Class GroupuserTransformer
 *
 * @package \App\Transformer
 */
class GroupuserTransformer extends Transformer
{
    public function transform ($item)
    {
        return [
            'uid' => "".$item['id']."",
            'nickname' => $item['nickname'],
            'face' => config('web.QINIU_URL').'/'.$item['user_face'],
            'phone' => "".$item['phone'].""
        ];
    }
}
