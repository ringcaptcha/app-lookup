<?php

/*
 * This file is part of the app-lookup library.
 *
 * (c) RingCaptcha, LLC.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RingCaptcha\AppLookup\Tests\Provider;

use GuzzleHttp\Client;
use GuzzleHttp\Message\Response;
use GuzzleHttp\Stream\Stream;
use GuzzleHttp\Subscriber\Mock;
use RingCaptcha\AppLookup\Provider\AppleProvider;

class AppleProviderTest extends \PHPUnit_Framework_TestCase
{
    protected static $fixturesPath;

    public static function setUpBeforeClass()
    {
        self::$fixturesPath = realpath(__DIR__.'/../Fixtures/');
    }

    /**
     * @expectedException \RingCaptcha\AppLookup\Exception\NotFoundException
     * @expectedExceptionMessage Unable to found the "com.mycompany.myapp" app.
     */
    public function testLookupWithInexistentApp()
    {
        $client = new Client();

        $responseMock = new Mock([
            new Response(200, array(), Stream::factory(file_get_contents(self::$fixturesPath.'/json/response0.json'))),
        ]);

        $client->getEmitter()->attach($responseMock);

        $provider = new AppleProvider($client);
        $provider->lookup('com.mycompany.myapp');
    }

    public function testLookupWithExistentApp()
    {
        $client = new Client();

        $responseMock = new Mock([
            new Response(200, array(), Stream::factory(file_get_contents(self::$fixturesPath.'/json/response1.json'))),
        ]);

        $client->getEmitter()->attach($responseMock);

        $provider = new AppleProvider($client);
        $app = $provider->lookup('net.whatsapp.WhatsApp');

        $this->assertEquals('net.whatsapp.WhatsApp', $app->getId());
        $this->assertEquals('WhatsApp Messenger', $app->getName());
        $this->assertEquals('WhatsApp Inc.', $app->getOwner());
        $this->assertEquals('http://is2.mzstatic.com/image/pf/us/r30/Purple5/v4/70/fe/f6/70fef6d6-2a32-ea81-18d9-b58f2e50a0ce/mzl.gskvkfhf.png', $app->getBrand());
        $this->assertEquals(array('social networking', 'utilities'), $app->getTags());
        $this->assertNotNull($app->getDescription());
        $this->assertCount(5, $app->getScreenshots());
    }
}
