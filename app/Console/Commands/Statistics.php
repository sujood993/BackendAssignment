<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\StatisticsService;

class Statistics extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'items:statistics {--type=all}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Items Statistics {--type : Type of statistics
                                                all, 
                                                count=Items Count,
                                                average=Average Item Price,
                                                highest=Website with Highest Price, 
                                                monthly=Total Items Price This Month}';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(StatisticsService $statistic)
    {
        $type = $this->option('type') ?? 'all';

        $labels = [
            'all' => 'All',
            'count' => 'Items Count',
            'average' => 'Average Item Price',
            'monthly' => 'Total Items Price This Month',
            'highest' => 'Website with Highest Price',
        ];

        if ($type == 'all') {
            $allStatistics = $this->allStatistics($statistic);
            $this->table(['Statistics', 'Value'], $allStatistics);
            return 0;
        } else {
            $statisticLabel = $labels[$type] ?? '';
            if (empty($statisticLabel)) {
                $this->line('its a wrong type');
                return 0;
            }
            $this->line($statistic->getByStatistic($statisticLabel));
            return 0;
        }
    }


    private function allStatistics(StatisticsService $stats)
    {
        $all = $stats->getStatistics();

        return [
            [
                'Items count',
                $all['items_count']
            ],
            [
                'Average item price',
                $all['average_item_price']],
            [
                'Last Month Total Price',
                $all['last_month_total_price']],
            [
                'Website With Highest Price',
                $all['website_highest_total_price']['website'] . '&total=' . $all['website_highest_total_price']['total']
            ],
        ];
    }


}
