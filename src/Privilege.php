<?php
declare(strict_types=1);

namespace Icarus\Authorization;


use Icarus\Authorization\Interfaces\IPrivilege;


abstract class Privilege implements IPrivilege
{

    public function __toString()
    {
        return static::class;
    }
}