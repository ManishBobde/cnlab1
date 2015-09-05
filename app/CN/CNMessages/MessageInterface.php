<?php
/**
 * Created by PhpStorm.
 * User: Hash
 * Date: 28-07-2015
 * Time: 23:02
 */

namespace App\CN\CNMessages;


interface MessageInterface {

    public function retrieveInboxMessages();

    public function retrieveSentMessages();

    public function retrieveDraftMessages();

    public function retrieveTrashedMessages();

    public function submitMessages();

    public function trashMessages();

    public function deleteMessages();

}