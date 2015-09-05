<?php
/**
 * Created by PhpStorm.
 * User: Hash
 * Date: 20-08-2015
 * Time: 22:58
 */

namespace App\CN\Transformers;


class PermissionTransformer extends Transformer{

    /**
     * @param $user
     * @return array
     */
    public function transform($permission){

        return [
           $permission->permissionName =>boolval($permission->pivot->isEnabled)
        ];

    }
}