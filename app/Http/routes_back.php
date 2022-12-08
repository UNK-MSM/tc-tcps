<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

/*Route::get('/membership/create', function () {
    return view('membership_create');
});
Route::get('/user/create', function () {
    return view('Admin::user_create');
});
Route::get('/plan/create', function () {
    return view('membership_plan_create');
});
Route::get('/market_vacation/create', function () {
    return view('market_vacation_create');
});*/

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

use Illuminate\Http\Request;

Route::post('ppcb', ['as' =>'ppcb', function(Request $request){
	\Log::debug('PP events', $request->all());
	return '';
}]);

Route::get('ppcb', ['as' =>'get_ppcb', function(Request $request){
	\Log::debug('PP events', $request->all());
	return '';
}]);
Route::group(['middleware' => 'web'], function () {

    Route::auth();
	Route::post('password/email', ['as'=>'password.reset.email', 'uses' => 'Auth\PasswordController@sendResetLinkEmail']);

	Route::group(['middleware' => ['auth', 'auth.dataentry', 'locale']], function() {
		Route::get('stock_closing_reading/import', ['as' => 'stock_closing_reading.import', 'uses' => 'StockClosingReadingController@getImport']);
		Route::get('stock_closing_reading/market_import', ['as' => 'stock_closing_reading.market_import', 'uses' => 'StockClosingReadingController@getMarketImport']);
		Route::post('stock_closing_reading/import', ['as' => 'stock_closing_reading.import', 'uses' => 'StockClosingReadingController@postImport']);
		Route::post('stock_closing_reading/market_import', ['as' => 'stock_closing_reading.market_import', 'uses' => 'StockClosingReadingController@postMarketImport']);
		Route::get('stock_closing_reading/lcd/{stock}', ['as' => 'stock_closing_reading.last_closing_date', 'uses' => 'StockClosingReadingController@getLastClosingDate']);
		Route::get('stock_closing_reading/mld/{stock}', ['as' => 'stock_closing_reading.market_last_date', 'uses' => 'StockClosingReadingController@getMarketLastDate']);
		Route::resource('stock_closing_reading', 'StockClosingReadingController');
	});

	Route::group(['middleware' => ['auth', 'auth.backend', 'locale']], function() {
		Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');

		Route::resource('discount_code', 'DiscountCodeController');
		Route::resource('discount_code_usage', 'GenericController');
		Route::resource('membership_type', 'GenericController');
		Route::post('membership/{membership}/activate', ['as' => 'membership.activate', 'uses' => 'MembershipController@activate']);
		Route::resource('membership', 'MembershipController');
		Route::resource('membership_plan_instance', 'MembershipPlanInstanceController');
		Route::resource('membership.membership_plan_instance', 'MembershipPlanInstanceController');
		Route::resource('membership_plan_instance.membership_plan_instance_detail', 'MembershipPlanInstanceDetailController');
		Route::resource('membership_custom_plan', 'GenericController');
		Route::resource('stock_entry_source', 'GenericController');
		Route::resource('stock_market_vacation', 'GenericController');
		Route::resource('membership_plan', 'GenericController');
		Route::resource('urgent_cause', 'GenericController');

		Route::get('stock/{stock}/calibration', ['as' => 'stock.calibration', 'uses' => 'StockController@calibration']);
		Route::post('stock/{stock}/calibrate', ['as' => 'stock.calibrate', 'uses' => 'StockController@calibrate']);
		Route::get('stock/{stock}/results/{type?}/{date?}', ['as' => 'stock.results', 'uses' => 'StockController@results']);
		Route::get('stock/analytical_report', ['as' => 'stock.analytical_report', 'uses' => 'StockController@analytical_report_ui']);
		Route::post('stock/analytical_report', ['as' => 'stock.analytical_report', 'uses' => 'StockController@analytical_report']);
		Route::get('stock/levels_report', ['as' => 'stock.levels_report', 'uses' => 'StockController@levels_report_ui']);
		Route::post('stock/levels_report', ['as' => 'stock.levels_report', 'uses' => 'StockController@levels_report']);
		Route::get('stock/{stock}/readings/{type?}/{date?}', ['as' => 'stock.readings', 'uses' => 'StockController@readings']);
		Route::get('stock/{stock}/export_results/{type?}/{file_id?}', ['as' => 'stock.export_results', 'uses' => 'StockController@export_results']);
		Route::post('stock/{stock}/apply_calibration/{date?}', ['as' => 'stock.apply_calibration', 'uses' => 'StockController@applyCalibrationSettings']);

		Route::get('stock/{stock}/urgent_causes', ['as' => 'stock.urgent_causes', 'uses' => 'StockController@getStockUrgentCalculation']);
		Route::get('stock/levels', ['as' => 'stock.levels', 'uses' => 'StockController@getStockLevels']);
		Route::post('stock/recalculate/{stock}/{date?}', ['as' => 'stock.recalculate', 'uses' => 'StockController@recalculateStockReadings']);
		Route::post('stock/refresh_last_stock_prediction/{stock}', ['as' => 'stock.refresh_last_stock_prediction', 'uses' => 'StockController@refreshLastStockPrediction']);
		Route::resource('stock', 'StockController');
		Route::resource('stock.stock_urgent_calculation', 'GenericController');
		Route::resource('currency', 'GenericController');
		Route::resource('stock_urgent_calculation', 'GenericController');
		Route::get('stock_market/decimal-places/{stock_market}', ['as' => 'stock_market.decimal_places', 'uses' => 'StockMarketController@getMarketDecimalPlaces']);
		Route::resource('stock_market', 'StockMarketController');
		Route::resource('stock_type', 'GenericController');
		Route::resource('user_type', 'GenericController');
		Route::resource('user_status', 'GenericController');
		Route::resource('news', 'GenericController');
		Route::resource('marquee_content', 'GenericController');
		Route::resource('final_analysis_message', 'FinalAnalysisMessageController');
		Route::resource('prediction_status', 'GenericController');
		Route::resource('country', 'GenericController');
		Route::resource('point', 'PointController');
		Route::resource('user', 'UserController');
		Route::controller('calculation', 'CalculationController');


		Route::post('setting/clear', ['as' => 'setting.clear', 'uses' => 'SettingController@clearStockLevelSettings']);
		Route::resource('setting', 'SettingController');


		Route::post('backup', ['as' => 'backup', function(){
	    	Artisan::call('backup:run');
		}]);
		Route::post('clear_cache', ['as' => 'clear_cache', function(){
			\Log::info('Clearing cache...');
	    	Artisan::call('cache:clear');
        \Cache::flush();
			\Log::info('Cache Cleared!');
		}]);


		Route::post('run', ['as' => 'run', function(){
	    	\Artisan::call('queue:work', ['--tries' => 1]);
		}]);
		Route::get('stock/{stock}/calibration_progress', ['as' => 'stock.calibration_progress', function($stock){
	    	$data['calibration_job_c'] = \Cache::get(\Auth::user()->serial_no.'_'.$stock.'_calibration_job_c');
	    	$data['calibration_job_p'] = \Cache::get(\Auth::user()->serial_no.'_'.$stock.'_calibration_job_p');
	    	//$data['calibration_job_p'] = 'FINISHED';
	    	return $data;
		}]);
		Route::get('stock/{stock}/calculation_progress', ['as' => 'stock.calculation_progress', function($stock){
	    	$data['recalculations_job_c'] = \Cache::get(\Auth::user()->serial_no.'_'.$stock.'_recalculations_job_c');
	    	return $data;
		}]);
		Route::get('phpinfo', function(){
			echo phpinfo();
		});

		Route::get('lang/{lang}', ['as' => 'lang', 'uses' => function($lang){
			$user = \Auth::user();
			$user->locale = $lang;

			if($user->save())
			{
				\Session::put('lang', $lang);
			}
			return back();
		}]);
	});
});
