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

namespace RingCaptcha\AppLookup\Tests;

use RingCaptcha\AppLookup\AppInfo;

class AppInfoTest extends \PHPUnit_Framework_TestCase
{
    public function testSerialization()
    {
        $app = new AppInfo('com.mycompany.myapp', 'MyApp', 'MyCompany', 'Lorem ipsum dolor sit amet.', 'http//myapp.com/logo.png');

        $this->assertEquals($app, unserialize(serialize($app)));
    }

    public function testGetters()
    {
        $app = new AppInfo('com.mycompany.myapp', 'MyApp', 'MyCompany', 'Lorem ipsum dolor sit amet.', 'http//myapp.com/logo.png', array(), array(), AppInfo::PLATFORM_IOS);

        $this->assertEquals('com.mycompany.myapp', $app->getId());
        $this->assertEquals('MyApp', $app->getName());
        $this->assertEquals('MyCompany', $app->getOwner());
        $this->assertEquals('Lorem ipsum dolor sit amet.', $app->getDescription());
        $this->assertEquals('http//myapp.com/logo.png', $app->getBrand());
        $this->assertEquals(array(), $app->getTags());
        $this->assertEquals(array(), $app->getScreenshots());
        $this->assertEquals(AppInfo::PLATFORM_IOS, $app->getPlatform());
    }
}
