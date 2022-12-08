<?php

namespace App\Jobs;

use App\User;
use App\Stock;
use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class PredictStockReadingsCalibration extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $user;
    protected $stock;
    //protected $start_from_date;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user, Stock $stock/*, $start_from_date*/)
    {
        $this->user = $user;
        $this->stock = $stock;
        //$this->start_from_date = $start_from_date;
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
            //\Log::info('u'.$this->user->serial_no.'_s'.$this->stock->serial_no.' predict_stock_readings_calibration STARTED');
            \Cache::put($this->user->serial_no.'_'.$this->stock->serial_no.'_calibration_job_p', 'STARTED', 1440);
            \DB::statement('call predict_stock_readings_calibration('.$this->stock->serial_no.')');
            \Cache::put($this->user->serial_no.'_'.$this->stock->serial_no.'_calibration_job_p', 'FINISHED', 1440);
            //\Log::info('u'.$this->user->serial_no.'_s'.$this->stock->serial_no.' predict_stock_readings_calibration FINISHED');
        }catch(\Exception $ex)
        {
            \Cache::put($this->user->serial_no.'_'.$this->stock->serial_no.'_calibration_job_p', 'ERROR', 1440);
            //\Log::info('u'.$this->user->serial_no.'_s'.$this->stock->serial_no.' predict_stock_readings_calibration ERROR');
            \Log::error($ex);
        }

    }
}
