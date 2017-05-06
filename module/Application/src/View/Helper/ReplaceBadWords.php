<?php

namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;

class ReplaceBadWords extends AbstractHelper
{
    public function __invoke($str)
    {
        $arrayBadWords = ['fuck', 'ass', 'bitch'];
        $arrayReplace  = ['f**k', 'a*s', 'b***h'];

        return str_ireplace($arrayBadWords, $arrayReplace, $str);
    }
}
