<?php
declare(strict_types=1);

namespace Icarus\Authorization;


use Icarus\Authorization\Interfaces\IRole;


abstract class Role implements IRole
{

    public function __toString()
    {
        return static::class;
    }



    public function getParentRoles(): array
    {
        return [];
    }
}