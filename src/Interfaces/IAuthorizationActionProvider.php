<?php
declare(strict_types=1);

namespace Icarus\Authorization\Interfaces;


interface IAuthorizationActionProvider
{

    public static function authorizationAction(): ?IAction;



    public function getPermissionDeniedFallback(): ?callable;
}