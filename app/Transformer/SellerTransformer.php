<?php

namespace App\Transformer;
use Illuminate\Support\Facades\Config;

/**
 * Class SellerTransformer
 *
 * @package \App\Transformer
 */
class SellerTransformer extends Transformer
{
    public function transform ($item)
    {
        return [
            'seller_uid' => "".$item['sell_uid']."",
            'user_face' => Config::get('web.QINIU_URL').'/'.$item['user_face'],
            'nickname' => $item['nickname'],
            'autograph' => $item['autograph'],
        ];
    }
}
