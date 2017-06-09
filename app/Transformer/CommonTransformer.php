<?php

namespace App\Transformer;

/**
 * Class CommonTransformer
 *
 * @package \App\Transformer
 */
class CommonTransformer extends Transformer
{
    public function transform ($item)
    {
        return [
            'common_id' => "".$item['common_id']."",
            'form_uid' => "".$item['form_uid']."",
            'form_nickname' => "".$item['form_nickname']."",
            'form_user_face' => "".$item['form_user_face']."",
            'to_uid' => "".$item['to_uid']."",
            'to_user_face' => "".$item['to_user_face']."",
            'to_nickname' => "".$item['to_nickname']."",
            'common_content' => $item['common_content'],
            'common_time' => $item['common_time']
        ];
    }
}
