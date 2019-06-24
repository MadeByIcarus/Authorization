<?php
declare(strict_types=1);

namespace Icarus\Authorization\Interfaces;


interface IAuthorizationActionProvider
{

    public static function authorizationAction(): ?IAction;



    public static function redirectDestinationOnPermissionDenied(): ?string;



    public function getAuthorizator(): IAuthorizator;



    public function getAccessIdentity(): ?IAccessIdentity;



    public function getPermissionDeniedCallback(): ?callable;
}