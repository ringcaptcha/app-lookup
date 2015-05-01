<?php

/*
 * This file is part of the app-lookup library.
 *
 * (c) RingCaptcha, LLC.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RingCaptcha\AppLookup\Exception;

/**
 * This exception is thrown when an app is not found.
 *
 * @author Diego Saint Esteben <diego@ringcaptcha.com>
 */
class NotFoundException extends \RuntimeException implements ExceptionInterface
{
    public function __construct($id)
    {
        parent::__construct(sprintf('Unable to found the "%s" app.', $id));
    }
}
