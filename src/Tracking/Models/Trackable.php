<?php

declare(strict_types=1);

namespace ReliqArts\Logistiq\Tracking\Models;

use ReliqArts\Logistiq\Tracking\Contracts\Trackable as TrackableContract;
use ReliqArts\Logistiq\Utility\Eloquent\Model;

abstract class Trackable extends Model implements TrackableContract
{
}
