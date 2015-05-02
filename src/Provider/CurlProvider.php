<?php

/*
 * This file is part of the app-lookup library.
 *
 * (c) RingCaptcha, LLC.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RingCaptcha\AppLookup\Provider;

use RingCaptcha\AppLookup\Exception\RuntimeException;

/**
 * @author Diego Saint Esteben <diego@ringcaptcha.com>
 */
abstract class CurlProvider implements ProviderInterface
{
    private $resource;

    /**
     * Init a cURL handle.
     */
    protected function init()
    {
        if (!is_resource($this->resource)) {
            $this->resource = curl_init();
        }
    }

    /**
     * Perform a cURL session.
     *
     * @param string $url
     * @param array  $options
     *
     * @throws RuntimeException
     */
    protected function exec($url, array $options = array())
    {
        $this->init();

        curl_setopt($this->resource, CURLOPT_URL, $url);
        curl_setopt($this->resource, CURLOPT_RETURNTRANSFER, true);
        curl_setopt_array($this->resource, $options);

        $response = curl_exec($this->resource);

        if (false === $response) {
            throw new RuntimeException(sprintf('cURL error: %s', curl_error($this->resource)));
        }

        return $response;
    }

    /**
     * Get information regarding a specific cURL handle.
     *
     * @param int $option
     *
     * @return mixed
     */
    protected function getInfo($option = 0)
    {
        if (!is_resource($this->resource)) {
            return false;
        }

        return curl_getinfo($this->resource, $option);
    }

    /**
     * Closes a cURL handle.
     */
    protected function close()
    {
        if (is_resource($this->resource)) {
            curl_close($this->resource);
            $this->resource = null;
        }
    }
}
