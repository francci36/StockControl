<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Product;

class UpdateStockThreshold extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stocks:update-threshold';


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
        Product::whereHas('transactions', function ($query) {
            $query->where('type', 'exit')
                ->whereDate('created_at', '>', now()->subDays(30));
        })->update([
            'stock_threshold' => DB::raw('(SELECT COALESCE(AVG(quantity), 5) FROM transactions WHERE type = "exit" AND product_id = products.id)')
        ]);

        $this->info('Seuil de stock mis à jour automatiquement !');
    }

}
