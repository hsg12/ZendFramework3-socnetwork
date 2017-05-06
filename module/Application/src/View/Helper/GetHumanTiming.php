<?php

namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;

class GetHumanTiming extends AbstractHelper
{
    public function __invoke(\DateTime $date)
    {
        $timeAgo = '';
        $diff = $date->diff(new \Datetime('now'));

        if ( ($t = $diff->format("%Y")) > 0 ) {
            $timeAgo = ($t == 1) ? $t . ' year' : $t . ' years';
        } elseif ( ($t = $diff->format("%m")) > 0 ) {
            $timeAgo = ($t == 1) ? $t . ' month' : $t . ' months';
        } elseif ( ($t = $diff->format("%d")) > 0 ) {
            $timeAgo = ($t == 1) ? $t . ' day' : $t . ' days';
        } elseif ( ($t = $diff->format("%h")) > 0 ) {
            $timeAgo = ($t == 1) ? $t . ' hour' : $t . ' hours';
        } elseif ( ($t = $diff->format("%i")) > 0 ) {
            $timeAgo = ($t == 1) ? $t . ' minute' : $t . ' minutes';
        } elseif ( ($t = $diff->format("%s")) >= 0 ) {
            if ( $t == 0 || $t == 1 ) {
                $timeAgo = 'just now';
                return $timeAgo;
            } else {
                $timeAgo = $t . ' seconds';
            }
        }

        return $timeAgo . ' ago';
    }
}
