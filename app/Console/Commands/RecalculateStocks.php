<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class RecalculateStocks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stock:recalculate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     * @return mixed
     */
    public function handle()
    {
        //
        $stocks = \App\Stock::select('serial_no', 'stock_name_en')->orderBy('serial_no', 'asc')->get();
        foreach($stocks as $stock)
        {
                $startTime = \Carbon\Carbon::now();
            try
            {
                \DB::statement('call recalculate_stock_readings('.$stock->serial_no.', null)');
                $endTime = \Carbon\Carbon::now();
                $this->info($stock->serial_no.' - '.$stock->stock_name_en.' completed in '.$endTime->diffInSeconds($startTime).' seconds');
            }catch(\Exception $ex)
            {
                $endTime = \Carbon\Carbon::now();
                $this->error('[ERROR] '.$stock->serial_no.' - '.$stock->stock_name_en);
                $this->error($ex->getMessage());
            }
        }
    }
}
