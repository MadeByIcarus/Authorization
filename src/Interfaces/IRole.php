<?php
declare(strict_types=1);

namespace Icarus\Authorization\Interfaces;


interface IRole
{

    public function __toString();



    public function getParentRoles(): array;
}