<?php

namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;

class GetAvatarUrl extends AbstractHelper
{
    public function __invoke($user, $size = 40)
    {
        return "https://www.gravatar.com/avatar/" . md5($user->getEmail()) . "?d=mm&s=" . $size;
    }
}
