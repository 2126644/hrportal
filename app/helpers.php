<?php

use App\Models\Setting;

function setting($key, $default = [])
{
    return Setting::where('key', $key)->value('value') ?? $default;
}

