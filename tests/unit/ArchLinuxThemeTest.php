<?php

namespace ArchLinux\Theme\Tests\Unit;

use ArchLinux\Theme\ArchLinuxTheme;
use Flarum\Foundation\Config;
use Flarum\Frontend\Compiler\VersionerInterface;
use Flarum\Frontend\Driver\TitleDriverInterface;
use Flarum\Frontend\Document;
use Illuminate\Contracts\Filesystem\Factory as FilesystemFactory;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Contracts\View\Factory as ViewFactory;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;

final class ArchLinuxThemeTest extends TestCase
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

        $this->assertIsArray($document->getForumApiDocument()['data']);
        $this->assertIsArray($document->getForumApiDocument()['data']['attributes']);
        $this->assertEquals('foo', $document->getForumApiDocument()['data']['attributes']['headerHtml']);
        $this->assertEquals('foo', $document->getForumApiDocument()['data']['attributes']['footerHtml']);
        $this->assertIsString($document->head['favicon']);
        $this->assertStringContainsString('rel="icon"', $document->head['favicon']);
    }

    public function testPreservesExistingDocumentContent(): void
    {
        $config = new Config(['url' => 'https://forum.archlinux.de']);
        $renderer = $this->createStub(Renderable::class);
        $renderer->method('render')->willReturn('theme');
        $viewFactory = $this->createStub(ViewFactory::class);
        $viewFactory->method('make')->willReturn($renderer);

        $document = new Document(
            $viewFactory,
            ['data' => ['attributes' => [
                'headerHtml' => 'header',
                'footerHtml' => 'footer',
                'faviconUrl' => '/custom.ico',
            ]]],
            $this->createStub(ServerRequestInterface::class),
            $this->createStub(TitleDriverInterface::class),
            $config,
            $this->createStub(VersionerInterface::class),
            $this->createStub(FilesystemFactory::class)
        );

        (new ArchLinuxTheme($config, $viewFactory))($document);

        $data = $document->getForumApiDocument()['data'];
        $this->assertIsArray($data);
        $attributes = $data['attributes'];
        $this->assertIsArray($attributes);
        $this->assertSame('themeheader', $attributes['headerHtml']);
        $this->assertSame('footertheme', $attributes['footerHtml']);
        $this->assertSame('/custom.ico', $attributes['faviconUrl']);
        $this->assertArrayNotHasKey('favicon', $document->head);
        $this->assertSame('#333', $document->meta['theme-color']);
        $this->assertIsString($document->head['manifest']);
        $this->assertStringContainsString('manifest.webmanifest', $document->head['manifest']);
    }
}
