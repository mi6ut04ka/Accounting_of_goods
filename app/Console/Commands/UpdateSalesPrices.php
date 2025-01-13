<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateSalesPrices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-sales-prices';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting to update sales prices...');

        try {
            DB::transaction(function () {
                DB::table('sales')
                    ->whereNotNull('product_id')
                    ->update([
                        'price' => DB::raw('(SELECT price FROM products WHERE products.id = sales.product_id)')
                    ]);
            });

            $this->info('Sales prices updated successfully!');
        } catch (\Exception $e) {
            $this->error('An error occurred while updating sales prices: ' . $e->getMessage());
        }
    }
}
