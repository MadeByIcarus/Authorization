<?php


namespace Icarus\Authorization\Interfaces;


interface IAuthorizatorStringBuilder
{

    public function addRole(string $role);



    public function addResource(string $resource);



    public function allow(string $role, string $resource, string $privilege);



    public function deny(string $role, string $resource, string $privilege);



    public function denyActionToAll(string $resource, string $privilege);
}