<?php

/*
 * This file is part of the app-lookup library.
 *
 * (c) RingCaptcha, LLC.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
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
        $app = new AppInfo('com.mycompany.myapp', 'MyApp', 'MyCompany', 'Lorem ipsum dolor sit amet.', 'http//myapp.com/logo.png');

        $this->assertEquals('com.mycompany.myapp', $app->getId());
        $this->assertEquals('MyApp', $app->getName());
        $this->assertEquals('MyCompany', $app->getOwner());
        $this->assertEquals('Lorem ipsum dolor sit amet.', $app->getDescription());
        $this->assertEquals('http//myapp.com/logo.png', $app->getBrand());
        $this->assertEquals(array(), $app->getTags());
        $this->assertEquals(array(), $app->getScreenshots());
    }
}
