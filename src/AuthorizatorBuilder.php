<?php
declare(strict_types=1);

namespace Icarus\Authorization;


use Icarus\Authorization\Interfaces\IAuthorizatorStringBuilder;


/**
 * Class AuthorizatorBuilder
 * @package Icarus\Authorization
 *
 * Simply set up permissions by passing string references to resource, role and privilege classes.
 * Example:
 * class MyResource extends Resource {}
 *
 * $this->addResource(MyResource::class) instead of Authorizator::addResource(new MyResource())
 */
class AuthorizatorBuilder implements IAuthorizatorStringBuilder
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
}
