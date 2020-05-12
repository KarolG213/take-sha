<?php
/**
 * Created by PhpStorm.
 * User: Karol
 * Date: 10.05.2020
 * Time: 18:25
 */

namespace app\services\interfaces;


interface RepositoryServiceInterface
{
    public function fetchLastSHA():string;
    public function setOwner(string $param):void;
    public function setRepository(string $param):void;
    public function setBranch(string $param):void;
}