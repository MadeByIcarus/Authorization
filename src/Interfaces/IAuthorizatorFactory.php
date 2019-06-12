<?php
declare(strict_types=1);

namespace Icarus\Authorization\Interfaces;


use Icarus\Authorization\Authorizator;
use Nette\Security\Permission;


interface IAuthorizatorFactory
{

    public static function create(Permission $permission): Authorizator;

}