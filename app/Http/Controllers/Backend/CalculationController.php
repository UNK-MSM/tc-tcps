<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\GenericRequest;

class CalculationController extends Controller
{
    //
    public function getMethodOne(Request $request){
    	$data['title'] = trans('titles.method_1_title');
        $data['description'] = "";
    	$data['markets'] = \App\StockMarket::with('stocks')->get();
    	$data['decimal_places'] = 3;

    	$data['predicted_stable_price_out'] = 0;
        $data['prediction_text_result_out'] = '';
        $data['has_results'] = false;
    	$data['stock_serial_no'] = $request->get('stock_serial_no');
    	$data['date'] = $request->get('date');
    	$data['closing_price'] = $request->get('closing_price');

        return view('Admin::calculation_method_1', $data);
    }

    public function postMethodOne(Request $request){
        $data['title'] = trans('titles.method_1_title');
        $data['description'] = "";
    	$data['markets'] = \App\StockMarket::with('stocks')->get();

    	$stock_serial_no = $request->get('stock_serial_no');
    	$date = $request->get('date');
    	$closing_price = $request->get('closing-price');

    	$data['predicted_stable_price_out'] = 0;
        $data['prediction_text_result_out'] = '';
        $data['has_results'] = false;
    	$data['stock_serial_no'] = $stock_serial_no;
    	$data['stock'] = \App\Stock::findOrFail($stock_serial_no);
    	$data['decimal_places'] = isset($data['stock']->stock_price_decimal_places)? $data['stock']->stock_price_decimal_places:2;
    	$data['date'] = $date;
    	$data['closing_price'] = $closing_price;
	    if (isset($_POST['requesting'])) {
            $engine = \Config::get('database.default');
            $host = \Config::get('database.connections.'.$engine.'.host');
            $username = \Config::get('database.connections.'.$engine.'.username');
            $password = \Config::get('database.connections.'.$engine.'.password');
            $schema = \Config::get('database.connections.'.$engine.'.database');
	        $mysqli = new \mysqli($host, $username, $password, $schema);

	        if ($mysqli->connect_error) {
	            die('Connect Error (' . $mysqli->connect_errno . ') '
	                    . $mysqli->connect_error);
	        }
	                
        	$two_pass = isset($_POST['two-pass']) ? 'Y': 'N';

	        $stmt = $mysqli->prepare("CALL n_predict_stock_next_price(?, ?, ?, ?,
	                                        @predicted_up_price_out, 
	                                        @predicted_up_price_rate_out, 
	                                        @predicted_down_price_out, 
	                                        @predicted_down_price_rate_out, 
	                                        @predicted_stable_price_out,
	                                        @predicted_stable_price_rate_out,
	                                        @prediction_text_result_out);");
	        $stmt->bind_param('isds', $stock_serial_no, $date, $closing_price, $two_pass);

	        $stmt->execute();

	        $select = $mysqli->query('SELECT @predicted_up_price_out, 
	                                         @predicted_up_price_rate_out, 
	                                         @predicted_down_price_out, 
	                                         @predicted_down_price_rate_out, 
	                                         @predicted_stable_price_out, 
	                                         @predicted_stable_price_rate_out, 
	                                         @prediction_text_result_out');

	        $result = $select->fetch_assoc();

	        $has_results = true;

	        $predicted_up_price_out = $result['@predicted_up_price_out'];
	        $predicted_up_price_rate_out = $result['@predicted_up_price_rate_out'];

	        $predicted_down_price_out = $result['@predicted_down_price_out'];
	        $predicted_down_price_rate_out = $result['@predicted_down_price_rate_out'];

	        $predicted_stable_price_out = $result['@predicted_stable_price_out'];
	        $predicted_stable_price_rate_out = $result['@predicted_stable_price_rate_out'];

	        $prediction_text_result_out = $result['@prediction_text_result_out'];

        	$data['has_results'] = $has_results;

        	$data['predicted_up_price_out'] = $predicted_up_price_out;
        	$data['predicted_up_price_rate_out'] = $predicted_up_price_rate_out;
        	$data['predicted_down_price_out'] = $predicted_down_price_out;
        	$data['predicted_down_price_rate_out'] = $predicted_down_price_rate_out;
        	$data['predicted_stable_price_out'] = $predicted_stable_price_out;
        	$data['predicted_stable_price_rate_out'] = $predicted_stable_price_rate_out;
        	$data['prediction_text_result_out'] = $prediction_text_result_out;


	        $mysqli->close();
	    }


        return view('Admin::calculation_method_1', $data);
    }


    public function getMethodTwo(Request $request){
        $data['title'] = trans('titles.method_2_title');
        $data['description'] = "";
    	$data['markets'] = \App\StockMarket::with('stocks')->get();
    	$data['decimal_places'] = 3;

    	$data['pass_2_predicted_stable_price_out'] = 0;
        $data['pass_1_prediction_text_result_out'] = '';
        $data['pass_2_prediction_text_result_out'] = '';
        $data['has_results'] = false;
    	$data['stock_serial_no'] = $request->get('stock_serial_no');
    	$data['date'] = $request->get('date');
    	$data['closing_price'] = $request->get('closing_price');

        return view('Admin::calculation_method_2', $data);
    }

    public function postMethodTwo(Request $request){
        $data['title'] = trans('titles.method_2_title');
        $data['description'] = "";
    	$data['markets'] = \App\StockMarket::with('stocks')->get();

    	$data['pass_2_predicted_stable_price_out'] = 0;
        $data['pass_1_prediction_text_result_out'] = '';
        $data['pass_2_prediction_text_result_out'] = '';
        $data['has_results'] = false;
    	$data['stock_serial_no'] = $request->get('stock_serial_no');
    	$data['stock'] = \App\Stock::findOrFail($request->get('stock_serial_no'));
    	$data['decimal_places'] = isset($data['stock']->stock_price_decimal_places)? $data['stock']->stock_price_decimal_places:2;
    	$data['date'] = $request->get('date');
    	$data['closing_price'] = $request->get('closing-price');
	    if (isset($_POST['requesting'])) {

            $engine = \Config::get('database.default');
            $host = \Config::get('database.connections.'.$engine.'.host');
            $username = \Config::get('database.connections.'.$engine.'.username');
            $password = \Config::get('database.connections.'.$engine.'.password');
            $schema = \Config::get('database.connections.'.$engine.'.database');
            $mysqli = new \mysqli($host, $username, $password, $schema);

			if ($mysqli->connect_error) {
			    die('Connect Error (' . $mysqli->connect_errno . ') '
			            . $mysqli->connect_error);
			}

        	$stock_serial_no = $request->get('stock_serial_no');
        	$date = $request->get('date');
        	$closing_price = $request->get('closing-price');
			$stmt = $mysqli->prepare("CALL predict_stock_instant_price(?, ?, ?, 
											@pass_1_predicted_up_price_out, 
											@pass_1_predicted_up_price_rate_out, 
											@pass_1_predicted_down_price_out, 
											@pass_1_predicted_down_price_rate_out, 
											@pass_1_predicted_stable_price_out,
											@pass_1_predicted_stable_price_rate_out,
	                                                                                
	                                        @pass_2_predicted_up_price_out, 
											@pass_2_predicted_up_price_rate_out, 
											@pass_2_predicted_down_price_out, 
											@pass_2_predicted_down_price_rate_out, 
											@pass_2_predicted_stable_price_out,
											@pass_2_predicted_stable_price_rate_out,
	                                                                                
											@pass_1_prediction_text_result_out,
											@pass_2_prediction_text_result_out);");
			$stmt->bind_param('isd', $stock_serial_no, $date, $closing_price);

			$stmt->execute();

			$select = $mysqli->query('SELECT @pass_1_predicted_up_price_out, 
	                                                 @pass_1_predicted_up_price_rate_out, 
	                                                 @pass_1_predicted_down_price_out, 
	                                                 @pass_1_predicted_down_price_rate_out, 
	                                                 @pass_1_predicted_stable_price_out, 
	                                                 @pass_1_predicted_stable_price_rate_out, 
	                                                 
	                                                 @pass_2_predicted_up_price_out, 
	                                                 @pass_2_predicted_up_price_rate_out, 
	                                                 @pass_2_predicted_down_price_out, 
	                                                 @pass_2_predicted_down_price_rate_out, 
	                                                 @pass_2_predicted_stable_price_out, 
	                                                 @pass_2_predicted_stable_price_rate_out, 

	                                                 @pass_1_prediction_text_result_out,
	                                                 @pass_2_prediction_text_result_out');

			$result = $select->fetch_assoc();

			$has_results = true;

			$pass_1_predicted_up_price_out = $result['@pass_1_predicted_up_price_out'];
			$pass_1_predicted_up_price_rate_out = $result['@pass_1_predicted_up_price_rate_out'];
			$pass_1_predicted_down_price_out = $result['@pass_1_predicted_down_price_out'];
			$pass_1_predicted_down_price_rate_out = $result['@pass_1_predicted_down_price_rate_out'];
			$pass_1_predicted_stable_price_out = $result['@pass_1_predicted_stable_price_out'];
			$pass_1_predicted_stable_price_rate_out = $result['@pass_1_predicted_stable_price_rate_out'];
			$pass_2_predicted_up_price_out = $result['@pass_2_predicted_up_price_out'];
			$pass_2_predicted_up_price_rate_out = $result['@pass_2_predicted_up_price_rate_out'];
			$pass_2_predicted_down_price_out = $result['@pass_2_predicted_down_price_out'];
			$pass_2_predicted_down_price_rate_out = $result['@pass_2_predicted_down_price_rate_out'];
			$pass_2_predicted_stable_price_out = $result['@pass_2_predicted_stable_price_out'];
			$pass_2_predicted_stable_price_rate_out = $result['@pass_2_predicted_stable_price_rate_out'];
			$pass_1_prediction_text_result_out = $result['@pass_1_prediction_text_result_out'];
			$pass_2_prediction_text_result_out = $result['@pass_2_prediction_text_result_out'];


        	$data['has_results'] = $has_results;

        	$data['pass_1_predicted_up_price_out'] = $pass_1_predicted_up_price_out;
        	$data['pass_1_predicted_up_price_rate_out'] = $pass_1_predicted_up_price_rate_out;
        	$data['pass_1_predicted_down_price_out'] = $pass_1_predicted_down_price_out;
        	$data['pass_1_predicted_down_price_rate_out'] = $pass_1_predicted_down_price_rate_out;
        	$data['pass_1_predicted_stable_price_out'] = $pass_1_predicted_stable_price_out;
        	$data['pass_1_predicted_stable_price_rate_out'] = $pass_1_predicted_stable_price_rate_out;
        	$data['pass_2_predicted_up_price_out'] = $pass_2_predicted_up_price_out;
        	$data['pass_2_predicted_up_price_rate_out'] = $pass_2_predicted_up_price_rate_out;
        	$data['pass_2_predicted_down_price_out'] = $pass_2_predicted_down_price_out;
        	$data['pass_2_predicted_down_price_rate_out'] = $pass_2_predicted_down_price_rate_out;
        	$data['pass_2_predicted_stable_price_out'] = $pass_2_predicted_stable_price_out;
        	$data['pass_2_predicted_stable_price_rate_out'] = $pass_2_predicted_stable_price_rate_out;
        	$data['pass_1_prediction_text_result_out'] = $pass_1_prediction_text_result_out;
        	$data['pass_2_prediction_text_result_out'] = $pass_2_prediction_text_result_out;



	        $mysqli->close();
	    }


        return view('Admin::calculation_method_2', $data);
    }


    public function getMethodThree(Request $request){
        $data['title'] = trans('titles.method_3_title');
        $data['description'] = "";
        $data['markets'] = \App\StockMarket::with('stocks')->get();
        $data['generators'] = \DB::table('random_generators')->get();
    	$data['decimal_places'] = 3;

    	$data['predicted_closing_price_out'] = 0;
        $data['pass_1_prediction_text_result_out'] = '';
        $data['pass_2_prediction_text_result_out'] = '';
        $data['has_results'] = false;
        $data['stock_serial_no'] = $request->get('stock_serial_no');
    	$data['closing_price'] = $request->get('closing_price');
        $data['generator_type'] = $request->get('generator_type');

        return view('Admin::calculation_method_3', $data);
    }

    public function postMethodThree(Request $request){
        $data['title'] = trans('titles.method_3_title');
        $data['description'] = "";
        $data['markets'] = \App\StockMarket::with('stocks')->get();
        $data['generators'] = \DB::table('random_generators')->get();

        $data['generator_type'] = $request->get('generator_type');

        $data['predicted_closing_price_out'] = 0;
        $data['stock_serial_no'] = $request->get('stock_serial_no');
        $data['stock'] = \App\Stock::findOrFail($request->get('stock_serial_no'));
        $data['decimal_places'] = isset($data['stock']->stock_price_decimal_places)? $data['stock']->stock_price_decimal_places:2;
        $data['stock_urgent_causes'] = $data['stock']->stock_urgent_causes;
        $data['urgent_cause_serial_no'] = $request->get('urgent_cause_serial_no');
        $data['urgent_cause'] = \App\UrgentCause::find($request->get('urgent_cause_serial_no'));
        $data['closing_price'] = $request->get('closing-price');
        $data['generator'] = \DB::table('random_generators')->where('serial_no', $request->get('generator_type'))->first();
        if (isset($_POST['requesting'])) {

            $engine = \Config::get('database.default');
            $host = \Config::get('database.connections.'.$engine.'.host');
            $username = \Config::get('database.connections.'.$engine.'.username');
            $password = \Config::get('database.connections.'.$engine.'.password');
            $schema = \Config::get('database.connections.'.$engine.'.database');
            $mysqli = new \mysqli($host, $username, $password, $schema);

            if ($mysqli->connect_error) {
                die('Connect Error (' . $mysqli->connect_errno . ') '
                        . $mysqli->connect_error);
            }

            $stock_serial_no = $request->get('stock_serial_no');
            $urgent_cause_serial_no = $request->get('urgent_cause_serial_no');
            $closing_price = $request->get('closing-price');
            $generator_type = $request->get('generator_type');
            if($closing_price == 0)
            {
                $stock_last_reading = $data['stock']->stock_closing_readings()->whereNotNull('close_selling_price')->max('date_closed');;
                $stock_last_price = $data['stock']->stock_closing_readings()->where('date_closed', $stock_last_reading)->first()->close_selling_price;
                $closing_price = $stock_last_price;
                $data['closing_price'] = $closing_price;
            }

            $stmt = $mysqli->prepare("CALL predict_stock_urgent_price(?, ?, ?, ?,
                                            @predicted_closing_price_out);");
            $stmt->bind_param('iidi', $stock_serial_no, $urgent_cause_serial_no, $closing_price, $generator_type);
            $stmt->execute();
            $select = $mysqli->query('SELECT @predicted_closing_price_out');
            $result = $select->fetch_assoc();

            $data['has_results'] = true;
            $data['predicted_closing_price_out'] = $result['@predicted_closing_price_out'];

            $mysqli->close();
        }


        return view('Admin::calculation_method_3', $data);
    }
}
