<?php

declare(strict_types=1);

namespace ReliqArts\Logistiq\Utility\Eloquent;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model as EloquentModel;

/**
 * @mixin Builder
 */
abstract class Model extends EloquentModel
{
}
