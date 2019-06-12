<?php
declare(strict_types=1);

namespace Icarus\Authorization\Interfaces;


interface IAccessIdentity
{

    const ROLE_ADMIN = 'admin';



    /**
     * @return array|IRole[]
     */
    public function getRoles(): array;
}