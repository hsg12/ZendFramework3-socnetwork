<?php

namespace Application\Service;

use Zend\Navigation\Service\DefaultNavigationFactory;

class TopNavigation extends DefaultNavigationFactory
{
    protected function getName()
    {
        return 'top_navigation';
    }
}
