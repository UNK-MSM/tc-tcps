<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CalibrateStockReadings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'calibration:calculate {user} {stock}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
        $user = $this->argument('user');
        $stock = $this->argument('stock');
            \Log::info('CalibrateStockReadings STARTED');
        \Cache::put($user.'_'.$stock.'_calibration_job_c', 'STARTED', 1440);
        \DB::statement('call calibrate_stock_readings('.$stock.')');
        \Cache::put($user.'_'.$stock.'_calibration_job_c', 'FINISHED', 1440);
            \Log::info('CalibrateStockReadings FINISHED');
            \Log::info('predict_stock_readings_calibration STARTED');
        \Cache::put($user.'_'.$stock.'_calibration_job_p', 'STARTED', 1440);
        \DB::statement('call predict_stock_readings_calibration('.$stock.')');
        \Cache::put($user.'_'.$stock.'_calibration_job_p', 'FINISHED', 1440);
            \Log::info('predict_stock_readings_calibration FINISHED');
    }
}
