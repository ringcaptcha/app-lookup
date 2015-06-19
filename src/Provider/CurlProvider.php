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
