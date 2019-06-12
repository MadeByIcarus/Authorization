<?php
declare(strict_types=1);

namespace Icarus\Authorization;


use Icarus\Authorization\Interfaces\IAccessIdentity;
use Icarus\Authorization\Interfaces\IAction;
use Icarus\Authorization\Interfaces\IAuthorizator;
use Icarus\Authorization\Interfaces\IAuthorizatorStringBuilder;
use Icarus\Authorization\Interfaces\IDynamicResource;
use Icarus\Authorization\Interfaces\IRole;
use Nette\Security\Permission;


class Authorizator implements IAuthorizator
{

    /**
     * @var Permission
     */
    private $permission;

    /** @var IAuthorizatorStringBuilder */
    private $stringBuilder;



    public function __construct(Permission $permission)
    {
        $this->permission = $permission;
    }



    public function addRole(IRole $role)
    {
        $parents = [];
        foreach ($role->getParentRoles() as $parent) {
            $parents[] = (string)$parent;
        }
        $this->permission->addRole((string)$role, $parents);
    }



    public function addResource(IDynamicResource $resource)
    {
        $this->permission->addResource((string)$resource);
    }



    public function allow(IRole $role, IAction $action)
    {
        $this->permission->allow((string)$role, (string)$action->getResource(), (string)$action->getPrivilege());
    }



    public function deny(IRole $role, IAction $action)
    {
        $this->permission->deny((string)$role, (string)$action->getResource(), (string)$action->getPrivilege());
    }



    public function denyActionToAll(IAction $action)
    {
        $this->permission->deny(Permission::ALL, (string)$action->getResource(), (string)$action->getPrivilege());
    }



    public function isAllowed(IAccessIdentity $accessIdentity, IAction $action)
    {
        foreach ($this->getRoles($accessIdentity, $action) as $role) {
            $isAllowed = $this->permission->isAllowed((string)$role, (string)$action->getResource(), (string)$action->getPrivilege());
            if ($isAllowed) {
                return true;
            }
        }
        return false;
    }



    /**
     * @param IAccessIdentity $accessIdentity
     * @param IAction $action
     * @return iterable|IRole[]
     */
    public function getRoles(IAccessIdentity $accessIdentity, IAction $action): iterable
    {
        foreach ($accessIdentity->getRoles() as $role) {
            yield $role;
        }
        foreach ($action->getResource()->getIdentityRoles($accessIdentity) as $role) {
            yield $role;
        }
    }



    // string builder

    public function getStringBuilder(): IAuthorizatorStringBuilder
    {
        if (!$this->stringBuilder) {
            $this->stringBuilder = new class($this) implements IAuthorizatorStringBuilder
            {

                /**
                 * @var Authorizator
                 */
                private $authorizator;



                public function __construct(Authorizator $authorizator)
                {
                    $this->authorizator = $authorizator;
                }



                public function addRole(string $role)
                {
                    if (!class_exists($role)) {
                        throw new \InvalidArgumentException("Class $role does not exist.");
                    }
                    $this->authorizator->addRole(new $role());
                }



                public function addResource(string $resource)
                {
                    if (!class_exists($resource)) {
                        throw new \InvalidArgumentException("Class $resource does not exist.");
                    }
                    $this->authorizator->addResource(new $resource());
                }



                public function allow(string $role, string $resource, string $privilege)
                {
                    if (!class_exists($role)) {
                        throw new \InvalidArgumentException("Class $role does not exist.");
                    }
                    $this->authorizator->allow(new $role(), Action::fromStrings($resource, $privilege));
                }



                public function deny(string $role, string $resource, string $privilege)
                {
                    if (!class_exists($role)) {
                        throw new \InvalidArgumentException("Class $role does not exist.");
                    }
                    $this->authorizator->deny(new $role(), Action::fromStrings($resource, $privilege));
                }



                public function denyActionToAll(string $resource, string $privilege)
                {
                    $this->authorizator->denyActionToAll(Action::fromStrings($resource, $privilege));
                }
            };
        }

        return $this->stringBuilder;
    }

}