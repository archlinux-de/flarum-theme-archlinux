<?php

namespace ArchLinux\Theme\Test;

use ArchLinux\Theme\ArchLinuxTheme;
use Flarum\Foundation\Config;
use Flarum\Frontend\Document;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Contracts\View\Factory as ViewFactory;
use PHPUnit\Framework\TestCase;

class ArchLinuxThemeTest extends TestCase
{
    public function testRender(): void
    {
        $config = $this->createMock(Config::class);
        $config
            ->expects($this->atLeastOnce())
            ->method('offsetGet')
            ->with('arch')
            ->willReturn([]);

        $renderer = $this->createMock(Renderable::class);
        $renderer
            ->expects($this->atLeastOnce())
            ->method('render')
            ->willReturn('foo');

        $viewFactory = $this->createMock(ViewFactory::class);
        $viewFactory
            ->expects($this->atLeastOnce())
            ->method('make')
            ->willReturn($renderer);

        $forumApiDocument = [];
        $document = $this->createMock(Document::class);
        $document
            ->expects($this->atLeastOnce())
            ->method('getForumApiDocument')
            ->willReturnCallback(
                function () use (&$forumApiDocument) {
                    return $forumApiDocument;
                }
            );
        $document
            ->expects($this->atLeastOnce())
            ->method('setForumApiDocument')
            ->willReturnCallback(
                function (array $input) use (&$forumApiDocument) {
                    $forumApiDocument = $input;
                }
            );

        $archLinuxTheme = new ArchLinuxTheme($config, $viewFactory);
        $archLinuxTheme($document);

        $this->assertEquals('foo', $document->getForumApiDocument()['data']['attributes']['headerHtml']);
        $this->assertEquals('foo', $document->getForumApiDocument()['data']['attributes']['footerHtml']);
        $this->assertStringContainsString('rel="icon"', $document->head['favicon']);
    }
}
