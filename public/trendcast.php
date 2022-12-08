<?php
	if (isset($_POST['requesting'])) {
		$mysqli = new mysqli('localhost', 'trendcas_mkhod', 'p@sSw00rd', 'trendcas_prediction_sys_sch');

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
		$stmt->bind_param('isds', $_POST['stock-serial-no'], $_POST['date'], $_POST['closing-price'], $two_pass);

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

		$mysqli->close();
	}
?>
<html>
	<head>
		<title>TrendCast Prediction Console</title>
		<style type="text/css">
			body {
				font-size: 18px;
				background-color: #000000;
				text-align: center;
			}

			table th, table td {
				border: 1px dotted gray;
				padding: 5px;
				background-color: white;
                            font-weight: bold;
			}
                        
                        table tr:nth-child(2n + 1) td {
                            background-color: silver;
                        }
                        
                        table tr span {
                            display: inline-block;
                            font-weight: normal;
                            margin-left: 20px;
                        }

			table {
				margin-bottom: 20px;
				margin-left: auto;
				margin-right: auto;
				text-align: left;
			}

			input, select {
				padding: 10px;
				border: 1px solid gray;
				border-radius: 15px;
			}
		</style>
	</head>

	<body>
		<?php if ($has_results) { ?>
		<table>
			<tr>
				<th>Predicted Rising Price</th>
				<th>Predicted Falling Price</th>
				<th>Predicted Stable Price</th>
				<th>Actual Closing Price</th>
			</tr>
			<tr>
                            <td style="<?php echo ($predicted_up_price_rate_out > $predicted_stable_price_rate_out && $predicted_up_price_rate_out > $predicted_down_price_rate_out) ? "background-color: green; color: white;" : "" ?>"><?php echo round($predicted_up_price_out, 3); ?><span>(<?php echo round($predicted_up_price_rate_out * 100, 0); ?>%)</span></td>
                            <td style="<?php echo ($predicted_down_price_rate_out > $predicted_stable_price_rate_out && $predicted_down_price_rate_out > $predicted_up_price_rate_out) ? "background-color: green; color: white;" : "" ?>"><?php echo round($predicted_down_price_out, 3); ?><span>(<?php echo round($predicted_down_price_rate_out * 100, 0); ?>%)</span></td>
                            <td style="<?php echo ($predicted_stable_price_rate_out > $predicted_up_price_rate_out && $ppredicted_stable_price_rate_out > $predicted_down_price_rate_out) ? "background-color: green; color: white;" : "" ?>"><?php echo round($predicted_stable_price_out, 3); ?><span>(<?php echo round($predicted_stable_price_rate_out * 100, 0); ?>%)</span></td>
                            <td id="actual-price" style="background-color: yellow; font-weight: bold;">-</td>
			</tr>
		</table>
		<table id="calcualtion-details">
			<tr>
				<th colspan="6">% IRP PROBABILITIES</th>
			</tr>
			<tr>
				<th colspan="2">IRP Probabilities</th>
				<th colspan="2">Last IRP</th>
				<th colspan="2">LAST 2 IRPs</th>
			</tr>
		</table>
		<?php } ?>
		<form method="POST">
			<select name="stock-serial-no">
				<option <?php echo $_POST['stock-serial-no'] == 2 ? 'selected' : ''; ?> value="2">Dubai Islamic Bank</option>
				<option <?php echo $_POST['stock-serial-no'] == 3 ? 'selected' : ''; ?> value="3">EMAAR</option>
				<option <?php echo $_POST['stock-serial-no'] == 4 ? 'selected' : ''; ?> value="4">Dubai Financial Market</option>
				<option <?php echo $_POST['stock-serial-no'] == 5 ? 'selected' : ''; ?> value="5">DEYAAR</option>
				<option <?php echo $_POST['stock-serial-no'] == 9 ? 'selected' : ''; ?> value="9">Dubai Investments </option>
				<option <?php echo $_POST['stock-serial-no'] == 10 ? 'selected' : ''; ?> value="10">Gulf Finance House</option>
				<option <?php echo $_POST['stock-serial-no'] == 11 ? 'selected' : ''; ?> value="11">Arabtec Holding</option>
				<option <?php echo $_POST['stock-serial-no'] == 12 ? 'selected' : ''; ?> value="12">Ajman Bank</option>
				<option <?php echo $_POST['stock-serial-no'] == 13 ? 'selected' : ''; ?> value="13">Union Properties</option>
			</select>
			<input type="date" id="closing-date" name="date" placeholder="Date in Format yyyy-mm-dd" value="<?php echo $_POST['date']; ?>" />
			<input type="text" id="closing-price" name="closing-price" placeholder="Stock Prior Closing Price" value="<?php echo ($_POST['closing-price'] == "" || $_POST['closing-price'] == 0) ? round($predicted_stable_price_out, 3): $_POST['closing-price']; ?>"/>
			<input type="submit" value="Bring it on!" />
                        <input type="submit" name="two-pass" value="Two Pass" />
			<input type="hidden" name="requesting" value="true" />
		</form>
		<script src="https://code.jquery.com/jquery-2.2.2.min.js"   integrity="sha256-36cp2Co+/62rEAAYHLmRCPIych47CvdM+uTBJwSzWjI="   crossorigin="anonymous"></script>
		<script type="text/javascript">
			var json = $.parseJSON('<?php echo $prediction_text_result_out == ''? '{}': $prediction_text_result_out; ?>');

			$(document).ready(function() {
				var rowSpanBook = {
					"1": {
						"top_positive": {
							"i": 0,
							"prob_rowspan": 0,
							"price_rowspan": 0
						},
						"middle_positive": {
							"i": -1,
							"prob_rowspan": 0,
							"price_rowspan": 0
						},
						"bottom_positive": {
							"i": -1,
							"prob_rowspan": 0,
							"price_rowspan": 0
						},
						"middle": {
							"i": 1
						},
						"bottom_negative": {
							"i": -1,
							"prob_rowspan": 0,
							"price_rowspan": 0
						},
						"middle_negative": {
							"i": -1,
							"prob_rowspan": 0,
							"price_rowspan": 0
						},
						"top_negative": {
							"i": 2,
							"prob_rowspan": 0,
							"price_rowspan": 0
						}
					},
					"2": {
						"top_positive": {
							"i": 0,
							"prob_rowspan": 0,
							"price_rowspan": 0
						},
						"middle_positive": {
							"i": 1,
							"prob_rowspan": 0,
							"price_rowspan": 0
						},
						"bottom_positive": {
							"i": -1,
							"prob_rowspan": 0,
							"price_rowspan": 0
						},
						"middle": {
							"i": 2
						},
						"bottom_negative": {
							"i": -1,
							"prob_rowspan": 0,
							"price_rowspan": 0
						},
						"middle_negative": {
							"i": 3,
							"prob_rowspan": 0,
							"price_rowspan": 0
						},
						"top_negative": {
							"i": 4,
							"prob_rowspan": 0,
							"price_rowspan": 0
						}
					},
					"3": {
						"top_positive": {
							"i": 0,
							"prob_rowspan": 0,
							"price_rowspan": 0
						},
						"middle_positive": {
							"i": 1,
							"prob_rowspan": 0,
							"price_rowspan": 0
						},
						"bottom_positive": {
							"i": 2,
							"prob_rowspan": 0,
							"price_rowspan": 0
						},
						"middle": {
							"i": 3
						},
						"bottom_negative": {
							"i": 4,
							"prob_rowspan": 0,
							"price_rowspan": 0
						},
						"middle_negative": {
							"i": 5,
							"prob_rowspan": 0,
							"price_rowspan": 0
						},
						"top_negative": {
							"i": 6,
							"prob_rowspan": 0,
							"price_rowspan": 0
						}
					},
					"4": {
						"top_positive": {
							"i": 0,
							"prob_rowspan": 0,
							"price_rowspan": 0
						},
						"middle_positive": {
							"i": 1,
							"prob_rowspan": 0,
							"price_rowspan": 0
						},
						"bottom_positive": {
							"i": 2,
							"prob_rowspan": 2,
							"price_rowspan": 2
						},
						"middle": {
							"i": 4
						},
						"bottom_negative": {
							"i": 5,
							"prob_rowspan": 2,
							"price_rowspan": 2
						},
						"middle_negative": {
							"i": 7,
							"prob_rowspan": 0,
							"price_rowspan": 0
						},
						"top_negative": {
							"i": 8,
							"prob_rowspan": 0,
							"price_rowspan": 0
						}
					},
					"5": {
						"top_positive": {
							"i": 0,
							"prob_rowspan": 0,
							"price_rowspan": 0
						},
						"middle_positive": {
							"i": 1,
							"prob_rowspan": 2,
							"price_rowspan": 2
						},
						"bottom_positive": {
							"i": 3,
							"prob_rowspan": 2,
							"price_rowspan": 2
						},
						"middle": {
							"i": 5
						},
						"bottom_negative": {
							"i": 6,
							"prob_rowspan": 2,
							"price_rowspan": 2
						},
						"middle_negative": {
							"i": 8,
							"prob_rowspan": 2,
							"price_rowspan": 2
						},
						"top_negative": {
							"i": 10,
							"prob_rowspan": 0,
							"price_rowspan": 0
						}
					},
					"6": {
						"top_positive": {
							"i": 0,
							"prob_rowspan": 2,
							"price_rowspan": 2
						},
						"middle_positive": {
							"i": 2,
							"prob_rowspan": 2,
							"price_rowspan": 2
						},
						"bottom_positive": {
							"i": 4,
							"prob_rowspan": 2,
							"price_rowspan": 2
						},
						"middle": {
							"i": 6
						},
						"bottom_negative": {
							"i": 7,
							"prob_rowspan": 2,
							"price_rowspan": 2
						},
						"middle_negative": {
							"i": 9,
							"prob_rowspan": 2,
							"price_rowspan": 2
						},
						"top_negative": {
							"i": 11,
							"prob_rowspan": 2,
							"price_rowspan": 2
						}
					},
					"7": {
						"top_positive": {
							"i": 0,
							"prob_rowspan": 3,
							"price_rowspan": 3
						},
						"middle_positive": {
							"i": 3,
							"prob_rowspan": 3,
							"price_rowspan": 3
						},
						"bottom_positive": {
							"i": 6,
							"prob_rowspan": 0,
							"price_rowspan": 0
						},
						"middle": {
							"i": 7
						},
						"bottom_negative": {
							"i": 8,
							"prob_rowspan": 0,
							"price_rowspan": 0
						},
						"middle_negative": {
							"i": 9,
							"prob_rowspan": 3,
							"price_rowspan": 3
						},
						"top_negative": {
							"i": 12,
							"prob_rowspan": 3,
							"price_rowspan": 3
						}
					},
					"8": {
						"top_positive": {
							"i": 0,
							"prob_rowspan": 3,
							"price_rowspan": 3
						},
						"middle_positive": {
							"i": 3,
							"prob_rowspan": 3,
							"price_rowspan": 3
						},
						"bottom_positive": {
							"i": 6,
							"prob_rowspan": 2,
							"price_rowspan": 2
						},
						"middle": {
							"i": 8
						},
						"bottom_negative": {
							"i": 9,
							"prob_rowspan": 2,
							"price_rowspan": 2
						},
						"middle_negative": {
							"i": 11,
							"prob_rowspan": 3,
							"price_rowspan": 3
						},
						"top_negative": {
							"i": 14,
							"prob_rowspan": 3,
							"price_rowspan": 3
						}
					},
					"9": {
						"top_positive": {
							"i": 0,
							"prob_rowspan": 4,
							"price_rowspan": 4
						},
						"middle_positive": {
							"i": 4,
							"prob_rowspan": 4,
							"price_rowspan": 4
						},
						"bottom_positive": {
							"i": 8,
							"prob_rowspan": 0,
							"price_rowspan": 0
						},
						"middle": {
							"i": 9
						},
						"bottom_negative": {
							"i": 10,
							"prob_rowspan": 0,
							"price_rowspan": 0
						},
						"middle_negative": {
							"i": 11,
							"prob_rowspan": 4,
							"price_rowspan": 4
						},
						"top_negative": {
							"i": 15,
							"prob_rowspan": 4,
							"price_rowspan": 4
						}
					}
				}
				if (json.stocks !== undefined) {
                                        $("#actual-price").html(json['close_selling_price']);
					$("#calcualtion-details").append('<tr><th></th><th></th><th>' + json.presets.last_irp_1 + '</th><th></th><th>' + json.presets.last_irp_2 + '</th><th></th><th>Average</th><th>Average Values for 2 IRP</th><th>Average Value for 3 IRP</th><th>Price After Calculation</th></tr>');
                                        
                                        $("#closing-date").on("change", function() {
                                            $("#closing-price").val("");
                                        });
                                        
					var details = "";
					var i = 0;

					$.each(json.stocks, function(key, value) {
						details += "<tr>";
						details += "<td>" + value.addition + "</td>";
						details += "<td>" + Math.round(value.price_prob * 100) + "%</td>";
						details += "<td>" + json.presets.last_irp_1 + value.addition + "</td>";
						details += "<td>" + value.probability_rate_1.toFixed(3) + "</td>";
						details += "<td>" + json.presets.last_irp_2 + value.addition + "</td>";
						details += "<td>" + value.probability_rate_2.toFixed(3) + "</td>";
						details += "<td>" + (100 * (value.average_rate_1 + value.average_rate_2) / 2).toFixed(2) + "%</td>";
						details += "<td>" + value.average_rate_1.toFixed(3) + "</td>";
						details += "<td>" + value.average_rate_2.toFixed(3) + "</td>";
						details += "<td>" + value.price.toFixed(3) + "</td>";

						if (i === rowSpanBook[json.presets.up_levels_count].top_positive.i) {
                            details += '<td rowspan="' + rowSpanBook[json.presets.up_levels_count].top_positive.prob_rowspan + '" style="background-color: yellow; font-weight: bold;">' + Math.round(json.prediction_totals.positive_top_price_prob * 100) + "%</td>";
							details += '<td rowspan="' + rowSpanBook[json.presets.up_levels_count].top_positive.price_rowspan + '" style="background-color: green; color: white; font-weight: bold;">' + json.prediction_totals.positive_top_price.toFixed(4) + "</td>";	
							details += '<td rowspan="' + json.presets.up_levels_count + '">' + json.prediction_totals.predicted_up_price.toFixed(4) + "</td>";							
						} else if (i === rowSpanBook[json.presets.up_levels_count].middle_positive.i) {
							details += '<td rowspan="' + rowSpanBook[json.presets.up_levels_count].middle_positive.prob_rowspan + '" style="background-color: yellow; font-weight: bold;">' + Math.round(json.prediction_totals.positive_middle_price_prob * 100) + "%</td>";
							details += '<td rowspan="' + rowSpanBook[json.presets.up_levels_count].middle_positive.price_rowspan + '" style="background-color: green; color: white; font-weight: bold;">' + json.prediction_totals.positive_middle_price.toFixed(3) + "</td>";						
						} else if (i === rowSpanBook[json.presets.up_levels_count].bottom_positive.i) {
							details += '<td rowspan="' + rowSpanBook[json.presets.up_levels_count].bottom_positive.prob_rowspan + '" style="background-color: yellow; font-weight: bold;">' + Math.round(json.prediction_totals.positive_bottom_price_prob * 100) + "%</td>";
							details += '<td rowspan="' + rowSpanBook[json.presets.up_levels_count].bottom_positive.price_rowspan + '" style="background-color: green; color: white; font-weight: bold;">' + json.prediction_totals.positive_bottom_price.toFixed(3) + "</td>";				
						} else if (i === rowSpanBook[json.presets.up_levels_count].middle.i) {
							details += '<td style="background-color: yellow; font-weight: bold;">' + Math.round(value.price_prob * 100) + "%</td>";
							details += '<td style="background-color: green; color: white; font-weight: bold;">' + value.price.toFixed(4) + "</td>";
							details += '<td>' + value.price.toFixed(4) + "</td>";								
						} else if (i === rowSpanBook[json.presets.down_levels_count].bottom_negative.i) {
							details += '<td rowspan="' + rowSpanBook[json.presets.down_levels_count].bottom_negative.prob_rowspan + '" style="background-color: yellow; font-weight: bold;">' + Math.round(json.prediction_totals.negative_bottom_price_prob * 100) + "%</td>";
							details += '<td rowspan="' + rowSpanBook[json.presets.down_levels_count].bottom_negative.price_rowspan + '" style="background-color: green; color: white; font-weight: bold;">' + json.prediction_totals.negative_bottom_price.toFixed(3) + "</td>";
							details += '<td rowspan="' + json.presets.down_levels_count + '">' + json.prediction_totals.predicted_down_price.toFixed(4) + "</td>";		
						} else if (i === rowSpanBook[json.presets.down_levels_count].middle_negative.i) {
							details += '<td rowspan="' + rowSpanBook[json.presets.down_levels_count].middle_negative.prob_rowspan + '" style="background-color: yellow; font-weight: bold;">' + Math.round(json.prediction_totals.negative_middle_price_prob * 100) + "%</td>";
							details += '<td rowspan="' + rowSpanBook[json.presets.down_levels_count].middle_negative.price_rowspan + '" style="background-color: green; color: white; font-weight: bold;">' + json.prediction_totals.negative_middle_price.toFixed(3) + "</td>";				
						} else if (i === rowSpanBook[json.presets.down_levels_count].top_negative.i) {
							details += '<td rowspan="' + rowSpanBook[json.presets.down_levels_count].top_negative.prob_rowspan + '" style="background-color: yellow; font-weight: bold;">' + Math.round(json.prediction_totals.negative_top_price_prob * 100) + "%</td>";
							details += '<td rowspan="' + rowSpanBook[json.presets.down_levels_count].top_negative.price_rowspan + '" style="background-color: green; color: white; font-weight: bold;">' + json.prediction_totals.positive_top_price.toFixed(4) + "</td>";			
						}


						details += "</tr>";

						i++;
					});

					$("#calcualtion-details").append(details);
				}
			});
		</script>
	</body>
</html>