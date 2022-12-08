<?php

namespace App\Jobs;

use App\User;
use App\Stock;
use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CalibrateStockReadings extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $user;
    protected $stock;
    protected $start_from_date;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user, Stock $stock, $start_from_date)
    {
        $this->user = $user;
        $this->stock = $stock;
        $this->start_from_date = $start_from_date;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        try
        {
        \Log::info('CalibrateStockReadings STARTED.', ['user' => $this->user->serial_no, 'stock' => $this->stock->serial_no, 'date' => $this->start_from_date]);
            \Cache::put($this->user->serial_no.'_'.$this->stock->serial_no.'_calibration_job_p', 'STARTED', 1440);
            \Cache::put($this->user->serial_no.'_'.$this->stock->serial_no.'_calibration_job_c', 'STARTED', 1440);
            \DB::statement('call calibrate_stock_readings('.$this->stock->serial_no.', \''.$this->start_from_date.'\')');
            //\DB::statement('call calibrate_stock_readings('.$this->stock->serial_no.', null)');
            \Cache::put($this->user->serial_no.'_'.$this->stock->serial_no.'_calibration_job_c', 'FINISHED', 1440);
            \Cache::put($this->user->serial_no.'_'.$this->stock->serial_no.'_calibration_job_p', 'FINISHED', 1440);
        \Log::info('CalibrateStockReadings FINISHED.', ['user' => $this->user->serial_no, 'stock' => $this->stock->serial_no, 'date' => $this->start_from_date]);

            //dispatch(new \App\Jobs\PredictStockReadingsCalibration($this->user, $this->stock));
        }catch(\Exception $ex)
        {
            \Cache::put($this->user->serial_no.'_'.$this->stock->serial_no.'_calibration_job_c', 'ERROR', 1440);
        \Log::info('CalibrateStockReadings ERROR.', ['user' => $this->user->serial_no, 'stock' => $this->stock->serial_no, 'date' => $this->start_from_date]);
        \Log::error($ex);
        }
    }
}
