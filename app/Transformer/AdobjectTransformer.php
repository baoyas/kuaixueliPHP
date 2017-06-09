<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/10/18
 * Time: 15:33
 */

namespace App\Transformer;

use Illuminate\Support\Facades\Config;

class AdobjectTransformer extends Transformer
{
    public function transform ($item)
    {
        return [
            'ad_id' => $item['id'],
            'ad_object_name' => $item['ad_object_name'],
            'ad_skip' => $item['ad_skip_describe'].$item['ad_object_aim'],
            'ad_thumb' => Config::get('web.QINIU_URL') .'/'. $item['ad_object_thumb']
        ];
    }
}