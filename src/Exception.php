<?php

declare(strict_types=1);

namespace ReliqArts\Logistiq;

use Exception as BaseException;

abstract class Exception extends BaseException
{
    protected const CODE = self::DEFAULT_CODE;
    protected const DEFAULT_CODE = 4000;
}
