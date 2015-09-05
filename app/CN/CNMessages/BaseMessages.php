<?php
/**
 * Created by PhpStorm.
 * User: Hash
 * Date: 30-07-2015
 * Time: 22:33
 */

namespace app\CN\CNMessages;


abstract class BaseMessages {

    abstract function retrieveMessages($bucketType);

}