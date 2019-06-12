<?php
declare(strict_types=1);

namespace Icarus\Authorization\Interfaces;


interface IDynamicResource
{

    public function __toString();



    /**
     * @param IAccessIdentity $identity
     * @return array|IRole[]
     */
    public function getIdentityRoles(IAccessIdentity $identity): array;
}