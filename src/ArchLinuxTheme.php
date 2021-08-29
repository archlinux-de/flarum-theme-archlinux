<?php

namespace ArchLinux\Theme;

use Flarum\Foundation\Config;
use Flarum\Frontend\Document;
use Illuminate\Contracts\View\Factory as ViewFactory;
use Illuminate\Support\Arr;

class ArchLinuxTheme
{
    private const ASSETS_PATH = '/assets/extensions/archlinux-de-theme-archlinux';

    public function __construct(private Config $config, private ViewFactory $factory)
    {
    }

    public function __invoke(Document $document): void
    {
        $forumApiDocument = $document->getForumApiDocument();

        Arr::set($document->meta, 'theme-color', '#333');
        Arr::set(
            $document->head,
            'manifest',
            '<link rel="manifest" href="' . self::ASSETS_PATH . '/manifest.webmanifest">'
        );

        if (!Arr::get($forumApiDocument, 'data.attributes.faviconUrl')) {
            Arr::set(
                $document->head,
                'favicon',
                '<link rel="icon" href="' . self::ASSETS_PATH . '/archicon.svg" sizes="any" type="image/svg+xml">'
            );
        }

        Arr::set(
            $forumApiDocument,
            'data.attributes.headerHtml',
            $this->createHeader() . Arr::get($forumApiDocument, 'data.attributes.headerHtml', '')
        );
        Arr::set(
            $forumApiDocument,
            'data.attributes.footerHtml',
            Arr::get($forumApiDocument, 'data.attributes.footerHtml', '') . $this->createFooter()
        );

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
