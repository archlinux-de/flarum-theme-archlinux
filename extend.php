<?php

use ArchLinux\Theme\ArchLinuxTheme;
use Flarum\Extend;

return [
    (new Extend\Frontend('forum'))
        ->js(__DIR__ . '/js/dist/forum.js')
        ->css(__DIR__ . '/less/forum.less')
        ->content(ArchLinuxTheme::class),
    (new Extend\View())
        ->namespace('theme-archlinux', __DIR__ . '/views'),
];
