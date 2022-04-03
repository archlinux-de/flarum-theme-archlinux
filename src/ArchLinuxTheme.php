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

        $config = $this->getConfig();

        Arr::set(
            $forumApiDocument,
            'data.attributes.headerHtml',
            $this->createHeader($config) . Arr::get($forumApiDocument, 'data.attributes.headerHtml', '')
        );
        Arr::set(
            $forumApiDocument,
            'data.attributes.footerHtml',
            Arr::get($forumApiDocument, 'data.attributes.footerHtml', '') . $this->createFooter($config)
        );

        $document->setForumApiDocument($forumApiDocument);
    }

    /**
     * @return string[]
     */
    private function getConfig(): array
    {
        $config = $this->config->offsetGet('arch');
        return is_array($config) ? $config : [];
    }

    /**
     * @param string[] $config
     */
    private function createHeader(array $config): string
    {
        return $this->factory->make(
            'theme-archlinux::header',
            $config
        )->render();
    }

    /**
     * @param string[] $config
     */
    private function createFooter(array $config): string
    {
        return $this->factory->make(
            'theme-archlinux::footer',
            $config
        )->render();
    }
}
