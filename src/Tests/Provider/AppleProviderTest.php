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

use RingCaptcha\AppLookup\Exception\NotFoundException;

class AppleProviderTest extends \PHPUnit_Framework_TestCase
{
    protected static $fixturesPath;

    public static function setUpBeforeClass()
    {
        self::$fixturesPath = realpath(__DIR__.'/../Fixtures/');
    }

    /**
     * @expectedException \RingCaptcha\AppLookup\Exception\NotFoundException
     * @expectedExceptionMessage Unable to found the "000000000" app.
     */
    public function testLookupWithInexistentApp()
    {
        $providerMock = $this->getMockBuilder('RingCaptcha\AppLookup\Provider\AppleProvider')
            ->setMethods(array('exec'))
            ->getMock()
        ;

        $providerMock
            ->expects($this->once())
            ->method('exec')
            ->with($this->equalTo('https://itunes.apple.com/lookup?id=000000000'), $this->anything())
            ->willReturn(file_get_contents(self::$fixturesPath.'/json/response0.json'))
        ;

        $providerMock->lookup('000000000');
    }

    public function testLookupWithExistentApp()
    {
        $providerMock = $this->getMockBuilder('RingCaptcha\AppLookup\Provider\AppleProvider')
            ->setMethods(array('exec'))
            ->getMock()
        ;

        $providerMock
            ->expects($this->once())
            ->method('exec')
            ->with($this->equalTo('https://itunes.apple.com/lookup?id=310633997'), $this->anything())
            ->willReturn(file_get_contents(self::$fixturesPath.'/json/response1.json'))
        ;

        $app = $providerMock->lookup('310633997');

        $this->assertEquals('310633997', $app->getId());
        $this->assertEquals('WhatsApp Messenger', $app->getName());
        $this->assertEquals('http://www.whatsapp.com/', $app->getOwner());
        $this->assertEquals('http://is2.mzstatic.com/image/pf/us/r30/Purple5/v4/70/fe/f6/70fef6d6-2a32-ea81-18d9-b58f2e50a0ce/mzl.gskvkfhf.png', $app->getBrand());
        $this->assertEquals(array('social networking', 'utilities'), $app->getTags());
        $this->assertNotNull($app->getDescription());
        $this->assertCount(5, $app->getScreenshots());
    }
}
