<?php
declare(strict_types=1);

namespace Icarus\Authorization\Traits;


use Icarus\Authorization\Interfaces\IAction;
use Icarus\Authorization\Interfaces\IAuthorizationActionProvider;
use Nette\Application\ForbiddenRequestException;
use Nette\Application\PresenterFactory;
use Nette\Application\UI\Presenter;


trait TAuthorizationPresenter
{

    /** @var PresenterFactory @inject */
    public $presenterFactory;



    protected function authorizeAccess(IAction $action, callable $onPermissionDenied = null)
    {
        /** @var $this IAuthorizationActionProvider */
        $user = $this->getAccessIdentity();

        /** @var $this IAuthorizationActionProvider */
        if (!$this->getAuthorizator()->isAllowed($user, $action)) {
            if ($onPermissionDenied) {
                call_user_func($onPermissionDenied);
            } else {
                throw new ForbiddenRequestException();
            }
        }
    }



    protected function authorizeAllActions()
    {
        $classes = [];

        /** @var IAuthorizationActionProvider $class */
        $class = static::class;

        while ($class !== Presenter::class) {
            $classes[] = $class;
            $class = get_parent_class($class);
        }

        foreach (array_reverse($classes) as $class) {
            /** @var IAuthorizationActionProvider $class */

            if (!is_subclass_of($class, IAuthorizationActionProvider::class)) {
                continue;
            }

            try {
                $action = $class::authorizationAction();
                $redirectDestination = $class::redirectDestinationOnPermissionDenied();
            } catch (\Error $e) {
                continue;
            }

            if (!$action) {
                continue;
            }

            /** @var $this IAuthorizationActionProvider */
            $callback = is_callable($this->getPermissionDeniedCallback()) ?
                $this->getPermissionDeniedCallback() :
                function () use ($redirectDestination) {
                    /** @var $this Presenter */
                    $this->redirect($redirectDestination);
                };

            $this->authorizeAccess(
                $action,
                $callback
            );
        }
    }



    public function isAuthorizedForDestination($link)
    {
        $presenter = substr($link, 0, strrpos($link, ":"));
        $presenter = ltrim($presenter, ":");
        /** @var IAuthorizationActionProvider $class */
        $class = $this->presenterFactory->getPresenterClass($presenter);
        if (is_subclass_of($class, IAuthorizationActionProvider::class) &&
            $action = $class::authorizationAction()
        ) {
            try {
                $this->authorizeAccess($action);
                return true;
            } catch (ForbiddenRequestException $e) {
                return false;
            }
        }
        return true;
    }
}