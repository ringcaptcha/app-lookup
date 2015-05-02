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
 * This exception is thrown when an error that can only be found on runtime occurs.
 *
 * @author Diego Saint Esteben <diego@ringcaptcha.com>
 */
class RuntimeException extends \RuntimeException implements ExceptionInterface
{
}
