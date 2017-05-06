<?php

namespace Application\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Zend\Filter\StringTrim;
use Zend\Filter\StripTags;

class ClearString extends AbstractPlugin
{
    public function __invoke($str)
    {
        $stripTagsFilter = new StripTags();
        $str = $stripTagsFilter->filter($str);

        $stringTrimFilter = new StringTrim();
        $str = $stringTrimFilter->filter($str);

        return $str;
    }
}
