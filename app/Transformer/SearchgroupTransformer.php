<?php

namespace App\Transformer;

/**
 * Class SearchgroupTransformer
 *
 * @package \App\Transformer
 */
class SearchgroupTransformer extends Transformer
{
    public function transform ($item)
    {
        return [
            'id' => "".$item['id']."",
            'group_name' => "".$item['group_name']."",
            'group_desc' => "".$item['group_desc']."",
            'owner_uid' => "".$item['owner_uid']."",
            'group_id' => "".$item['group_id']."",
            'group_face' => "".$item['group_face']."",
            'joinGroup' => "".$item['joinGroup']."",
        ];
    }
}
