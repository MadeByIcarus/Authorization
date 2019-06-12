<?php
declare(strict_types=1);

namespace Icarus\Authorization\Interfaces;


interface IAction
{

    public function getResource(): IDynamicResource;



    public function getPrivilege(): IPrivilege;
}