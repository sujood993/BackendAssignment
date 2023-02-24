<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class StatisticsService
{

    protected int $items_count;
    protected float $average_item_price;
    protected string $website_highest_total_price;
    protected float $last_month_total_price;

    /**
     * Returns a list of statistics about items
     *
     * @return object
     */
    public function getStatistics()
    {
        $stats = (object)[];
        $stats->items_count = $this->totalItemsCount();
        $stats->average_item_price = $this->averageItemPrice();
        $stats->last_month_total_price = $this->lastMonthTotalPrice();
        $stats->website_highest_total_price = $this->websiteHighestTotalPrice();
        return collect($stats)->toArray();
    }

    /**
     * Returns a total item count
     * @return int
     */
    private function totalItemsCount()
    {
        return DB::table('items')->count();
    }

    /**
     * Returns average price of an item
     *
     * @return float
     */
    private function averageItemPrice()
    {
        $averagePrice = DB::table('items')->avg('price');
        return number_format($averagePrice, 2, '.', ' ');
    }

    /**
     * Returns the highest total price of its items
     *
     * @return string
     */
    private function websiteHighestTotalPrice()
    {
        $websites = DB::table('items')->select(DB::raw('SUM(price) as total'), 'provider')->groupBy('provider')->orderBy('total', 'desc')->first();;
        return [
            'website' => $websites->provider,
            'total' => number_format($websites->total, 2, '.', ' ')
        ];
    }

    /**
     * Returns the total price of last month
     * @return string
     */
    private function lastMonthTotalPrice()
    {
        $currentMonth = now()->month;
        $totalPrice = DB::table('items')->whereMonth('created_at', $currentMonth)->sum('price');
        return number_format($totalPrice, 2, '.', ' ');
    }


    /**
     * @param String $label
     * @return float|string
     */

    public function getByStatistic(string $label)
    {


        return match ($label) {
            'Items Count' => $this->totalItemsCount() . ' Items',
            'Average Item Price' => $this->averageItemPrice(),
            'Total Items Price This Month' => $this->lastMonthTotalPrice(),
            'Website with Highest Price' => $this->websiteHighestTotalPrice(),
        };
    }
}