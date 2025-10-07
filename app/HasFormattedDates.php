<?php

namespace App;

use Carbon\Carbon;

trait HasFormattedDates
{
    public function getCreatedAtFormattedAttribute()
    {
        return Carbon::parse($this->created_at)->format('d/m/Y H:i:s');
    }

    public function getUpdatedAtFormattedAttribute()
    {
        return Carbon::parse($this->updated_at)->format('d/m/Y H:i:s');
    }
}
