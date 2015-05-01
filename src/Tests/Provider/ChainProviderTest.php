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

use RingCaptcha\AppLookup\AppInfo;
use RingCaptcha\AppLookup\Exception\NotFoundException;
use RingCaptcha\AppLookup\Provider\ChainProvider;

class ChainProviderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \RingCaptcha\AppLookup\Exception\NotFoundException
     * @expectedExceptionMessage Unable to found the "com.mycompany.myapp" app.
     */
    public function testLookupWithoutProviders()
    {
        $provider = new ChainProvider();
        $provider->lookup('com.mycompany.myapp');
    }

    /**
     * @expectedException \RingCaptcha\AppLookup\Exception\NotFoundException
     * @expectedExceptionMessage Unable to found the "com.mycompany.myapp" app.
     */
    public function testLookupWithoutSupportedProvider()
    {
        $providerMock = $this->getMockBuilder('RingCaptcha\AppLookup\Provider\ProviderInterface')->getMock();

        $providerMock
            ->expects($this->once())
            ->method('lookup')
            ->with($this->equalTo('com.mycompany.myapp'))
            ->will($this->throwException(new NotFoundException('com.mycompany.myapp')))
        ;

        $provider = new ChainProvider();
        $provider->addProvider($providerMock);
        $provider->lookup('com.mycompany.myapp');
    }

    public function testLookup()
    {
        $providerMockA = $this->getMockBuilder('RingCaptcha\AppLookup\Provider\ProviderInterface')->getMock();

        $providerMockA
            ->expects($this->once())
            ->method('lookup')
            ->with($this->equalTo('com.mycompany.myapp'))
            ->will($this->throwException(new NotFoundException('com.mycompany.myapp')))
        ;

        $providerMockB = $this->getMockBuilder('RingCaptcha\AppLookup\Provider\ProviderInterface')->getMock();

        $app = new AppInfo('com.mycompany.myapp', 'MyApp', 'MyCompany');

        $providerMockB
            ->expects($this->once())
            ->method('lookup')
            ->with($this->equalTo('com.mycompany.myapp'))
            ->willReturn($app)
        ;

        $provider = new ChainProvider();

        $provider->addProvider($providerMockA);
        $provider->addProvider($providerMockB);

        $this->assertEquals($app, $provider->lookup('com.mycompany.myapp'));
    }
}
