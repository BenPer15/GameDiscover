<?php

use Carbon\Carbon;

if (!function_exists('formatDate')) {
    function formatDate($date, $format = 'd M Y')
    {
        return $date ? Carbon::parse($date)->format($format) : '';
    }
}
