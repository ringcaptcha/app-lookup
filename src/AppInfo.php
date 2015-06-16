<?php

/*
 * This file is part of the app-lookup library.
 *
 * (c) RingCaptcha, LLC.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
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
