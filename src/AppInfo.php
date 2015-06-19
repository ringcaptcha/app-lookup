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

namespace RingCaptcha\AppLookup;

/**
 * Represents an app.
 *
 * @author Diego Saint Esteben <diego@ringcaptcha.com>
 */
class AppInfo implements \Serializable
{
    private $id;
    private $name;
    private $owner;
    private $description;
    private $brand;
    private $tags;
    private $screenshots;
    private $platform;

    const PLATFORM_IOS = 'iOS';
    const PLATFORM_ANDROID = 'Android';

    public function __construct($id, $name, $owner, $description = null, $brand = null, array $tags = array(), array $screenshots = array(), $platform = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->owner = $owner;
        $this->description = $description;
        $this->brand = $brand;
        $this->tags = $tags;
        $this->screenshots = $screenshots;
        $this->platform = $platform;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getOwner()
    {
        return $this->owner;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getBrand()
    {
        return $this->brand;
    }

    public function getTags()
    {
        return $this->tags;
    }

    public function getScreenshots()
    {
        return $this->screenshots;
    }

    public function getPlatform()
    {
        return $this->platform;
    }

    public function serialize()
    {
        return serialize(array($this->id, $this->name, $this->owner, $this->description, $this->brand, $this->tags, $this->screenshots, $this->platform));
    }

    public function unserialize($data)
    {
        list($this->id, $this->name, $this->owner, $this->description, $this->brand, $this->tags, $this->screenshots, $this->platform) = unserialize($data);
    }

    public function __toString()
    {
        return $this->serialize();
    }
}
