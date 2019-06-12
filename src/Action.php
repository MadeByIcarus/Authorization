<?php


namespace Icarus\Authorization;


use Icarus\Authorization\Interfaces\IAction;
use Icarus\Authorization\Interfaces\IDynamicResource;
use Icarus\Authorization\Interfaces\IPrivilege;


class Action implements IAction
{

    /**
     * @var Resource
     */
    private $resource;

    /**
     * @var IPrivilege
     */
    private $privilege;



    public function __construct(IDynamicResource $resource, IPrivilege $privilege)
    {
        $this->resource = $resource;
        $this->privilege = $privilege;
    }



    public function getResource(): IDynamicResource
    {
        return $this->resource;
    }



    public function getPrivilege(): IPrivilege
    {
        return $this->privilege;
    }



    public static function fromStrings(string $resource, string $privilege): Action
    {
        if (!class_exists($resource)) {
            throw new \InvalidArgumentException("Class $resource does not exist.");
        }
        if (!class_exists($privilege)) {
            throw new \InvalidArgumentException("Class $privilege does not exist.");
        }

        return new Action(new $resource(), new $privilege());
    }
}