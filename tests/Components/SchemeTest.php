<?php

/**
 * League.Uri (http://uri.thephpleague.com/components)
 *
 * @package    League\Uri
 * @subpackage League\Uri\Components
 * @author     Ignace Nyamagana Butera <nyamsprod@gmail.com>
 * @license    https://github.com/thephpleague/uri-components/blob/master/LICENSE (MIT License)
 * @version    2.0.2
 * @link       https://github.com/thephpleague/uri-components
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LeagueTest\Uri\Components;

use League\Uri\Components\Scheme;
use League\Uri\Exceptions\SyntaxError;
use League\Uri\Http;
use League\Uri\Uri;
use PHPUnit\Framework\TestCase;
use TypeError;
use function date_create;
use function var_export;

/**
 * @group scheme
 * @coversDefaultClass \League\Uri\Components\Scheme
 */
class SchemeTest extends TestCase
{
    /**
     * @covers ::__set_state
     * @covers ::__construct
     */
    public function testSetState(): void
    {
        $component = new Scheme('ignace');
        $generateComponent = eval('return '.var_export($component, true).';');
        self::assertEquals($component, $generateComponent);
    }

    /**
     * @covers ::withContent
     * @covers ::getContent
     * @covers ::__toString
     * @covers ::validate
     */
    public function testWithValue(): void
    {
        $scheme = new Scheme('ftp');
        $http_scheme = $scheme->withContent('HTTP');
        self::assertSame('http', $http_scheme->getContent());
        self::assertSame('http', (string) $http_scheme);
    }

    /**
     * @covers ::withContent
     * @covers ::validate
     */
    public function testWithContent(): void
    {
        $scheme = new Scheme('ftp');
        self::assertSame($scheme, $scheme->withContent('FtP'));
        self::assertNotSame($scheme, $scheme->withContent('Http'));
    }


    /**
     * @dataProvider validSchemeProvider
     *
     * @covers ::validate
     * @covers ::__toString
     * @covers ::getUriComponent
     *
     * @param null|mixed $scheme
     *
     */
    public function testValidScheme($scheme, string $toString, string $uriComponent): void
    {
        $scheme = new Scheme($scheme);
        self::assertSame($toString, (string) $scheme);
        self::assertSame($uriComponent, $scheme->getUriComponent());
    }

    public function validSchemeProvider(): array
    {
        return [
            [null, '', ''],
            [new Scheme('foo'), 'foo', 'foo:'],
            [new class() {
                public function __toString()
                {
                    return 'foo';
                }
            }, 'foo', 'foo:'],
            ['a', 'a', 'a:'],
            ['ftp', 'ftp', 'ftp:'],
            ['HtTps', 'https', 'https:'],
            ['wSs', 'wss', 'wss:'],
            ['telnEt', 'telnet', 'telnet:'],
        ];
    }

    /**
     * @dataProvider invalidSchemeProvider
     *
     * @covers ::validate
     */
    public function testInvalidScheme(string $scheme): void
    {
        self::expectException(SyntaxError::class);
        new Scheme($scheme);
    }

    public function invalidSchemeProvider(): array
    {
        return [
            'empty string' => [''],
            'invalid char' => ['in,valid'],
            'integer like string' => ['123'],
        ];
    }

    public function testInvalidSchemeType(): void
    {
        self::expectException(TypeError::class);
        new Scheme(date_create());
    }


    /**
     * @dataProvider getURIProvider
     * @covers ::createFromUri
     *
     * @param mixed   $uri      an URI object
     * @param ?string $expected
     */
    public function testCreateFromUri($uri, ?string $expected): void
    {
        $scheme = Scheme::createFromUri($uri);

        self::assertSame($expected, $scheme->getContent());
    }

    public function getURIProvider(): iterable
    {
        return [
            'PSR-7 URI object' => [
                'uri' => Http::createFromString('http://example.com?foo=bar'),
                'expected' => 'http',
            ],
            'PSR-7 URI object with no scheme' => [
                'uri' => Http::createFromString('//example.com/path'),
                'expected' => null,
            ],
            'League URI object' => [
                'uri' => Uri::createFromString('http://example.com?foo=bar'),
                'expected' => 'http',
            ],
            'League URI object with no scheme' => [
                'uri' => Uri::createFromString('//example.com/path'),
                'expected' => null,
            ],
        ];
    }

    public function testCreateFromUriThrowsTypeError(): void
    {
        self::expectException(TypeError::class);

        Scheme::createFromUri('http://example.com#foobar');
    }
}
