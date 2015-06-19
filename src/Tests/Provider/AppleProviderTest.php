<?php

/*
 * Copyright 2015 RingCaptcha
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace RingCaptcha\AppLookup\Tests\Provider;

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
