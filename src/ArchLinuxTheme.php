<?php

namespace ArchLinux\Theme;

use Flarum\Foundation\Config;
use Flarum\Frontend\Document;
use Illuminate\Contracts\View\Factory as ViewFactory;

class ArchLinuxTheme
{
    public function __construct(private Config $config, private ViewFactory $factory)
    {
    }

    public function __invoke(Document $document): void
    {
        $forumApiDocument = $document->getForumApiDocument();
        $document->head[] =
            '<link rel="shortcut icon" href="/assets/extensions/archlinux-de-theme-archlinux/favicon.ico" />';

        $forumApiDocument['data']['attributes']['headerHtml'] = $this->createHeader();
        $forumApiDocument['data']['attributes']['footerHtml'] = $this->createFooter();

        $document->setForumApiDocument($forumApiDocument);
    }

    private function createHeader(): string
    {
        return $this->factory->make(
            'theme-archlinux::header',
            $this->config->offsetGet('arch') ?? []
        )->render();
    }

    private function createFooter(): string
    {
        return $this->factory->make(
            'theme-archlinux::footer',
            $this->config->offsetGet('arch') ?? []
        )->render();
    }
}
