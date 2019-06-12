<?php
declare(strict_types=1);

namespace Icarus\Authorization;


use Icarus\Authorization\Interfaces\IAccessIdentity;
use Icarus\Authorization\Interfaces\IDynamicResource;


abstract class Resource implements IDynamicResource
{

    public function __toString()
    {
        return static::class;
    }



    public function getIdentityRoles(IAccessIdentity $identity): array
    {
        return [];
    }
}