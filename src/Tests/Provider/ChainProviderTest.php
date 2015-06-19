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
