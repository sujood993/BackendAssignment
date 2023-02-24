<?php

namespace App\Http\Controllers;

use App\Services\StatisticsService;

class StatisticsController extends Controller
{

    public function __invoke(StatisticsService $stats)
    {
        return response()->json(['statistics' => $stats->getStatistics()]);
    }
}

