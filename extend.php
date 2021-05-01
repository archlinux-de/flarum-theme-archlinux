<?php

namespace ArchLinux\Theme;

use Flarum\Extend;

return [
    (new Extend\Frontend('forum'))
        ->css(__DIR__ . '/less/forum.less')
        ->content(ArchLinuxTheme::class),
    (new Extend\View())
        ->namespace('theme-archlinux', __DIR__ . '/views'),
];
