<?php

namespace Icarus\Authorization\Interfaces;


interface IAuthorizator
{

    public function addRole(IRole $role);



    public function addResource(IDynamicResource $resource);



    public function allow(IRole $role, IAction $action);



    public function deny(IRole $role, IAction $action);



    public function isAllowed(IAccessIdentity $accessIdentity, IAction $action);



    /**
     * @param IAccessIdentity $accessIdentity
     * @param IAction $action
     * @return iterable|IRole[]
     */
    public function getRoles(IAccessIdentity $accessIdentity, IAction $action): iterable;
}