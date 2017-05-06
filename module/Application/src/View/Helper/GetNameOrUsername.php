<?php

namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;

class GetNameOrUsername extends AbstractHelper
{
    public function __invoke($obj, $user)
    {
        if ($user) {
            if ($user->getFirstName() && $user->getLastName()) {
                return $obj->escapeHtml($user->getFirstName() . ' ' . $user->getLastName());
            } else {
                return $obj->escapeHtml($user->getUsername());
            }
        }
        return false;
    }
}
