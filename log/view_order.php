<?php

//view_order.php

if(isset($_GET["pdf"]) && isset($_GET['order_id']))
{
	require_once 'pdf.php';
	include('database_connection.php');
	include('function.php');
	if(!isset($_SESSION['type']))
	{
		header('location:login.php');
	}
	$output = '
	<html>
	<head>

	</head>
		<body style="margin-left:-12;margin: top -10;">
		ECWA Christian Education
		<br>';
	$statement = $connect->prepare("
		SELECT * FROM inventory_order 
		WHERE inventory_order_id = :inventory_order_id
		LIMIT 1
	");
	$statement->execute(
		array(
			':inventory_order_id'       =>  $_GET["order_id"]
		)
	);
	$result = $statement->fetchAll();
	foreach($result as $row)
	{
		$output .= '
		Name: '.$row["inventory_order_name"].'<br>
		Phone Number: '.$row["inventory_order_address"].'<br>
		Invoice NO. : '.$row["inventory_order_id"].'<br>
		Invoice Date ; '.$row["inventory_order_date"].'<br>
		
		';
		$statement = $connect->prepare("
			SELECT * FROM inventory_order_product 
			WHERE inventory_order_id = :inventory_order_id
		");
		$statement->execute(
			array(
				':inventory_order_id'       =>  $_GET["order_id"]
			)
		);
		$product_result = $statement->fetchAll();
		$count = 0;
		$total = 0;
		$total_actual_amount = 0;
		$total_tax_amount = 0;
		foreach($product_result as $sub_row)
		{
			$count = $count + 1;
			$product_data = fetch_product_details($sub_row['product_id'], $connect);
			$actual_amount = $sub_row["quantity"] * $sub_row["price"];
			$tax_amount = ($actual_amount * $sub_row["tax"])/100;
			$total_product_amount = $actual_amount + $tax_amount;
			$total_actual_amount = $total_actual_amount + $actual_amount;
			$total_tax_amount = $total_tax_amount + $tax_amount;
			$total = $total + $total_product_amount;
			$output .= '
				
				
				<li style="float:left; text-align:left; list-style-type: none; margin: 16px;">'.$count.'</li>
				<li style="float:left; text-align:left; list-style-type: none;margin: 16px;">'.$product_data['product_name'].'</li>
				<li style="float:left; text-align:left; list-style-type: none; margin: 16px;">'.$sub_row['quantity'].'</li>
				<li style="float:left; text-align:left; list-style-type: none; margin: 16px;">N'.number_format($actual_amount, 2).'</li>
				<br>
				<br>
				<br>
				<pre>
					<b>Total = '.number_format($total_actual_amount,2
				).'</b>
				</pre>
				


				
			';
		}
		$output .= '
		
		';
		$output .= '
						
	';}
	$pdf = new Pdf();
	$file_name = 'Order-'.$row["inventory_order_id"].'.pdf';
	// $pdf->set_paper('A8');
	$pdf->loadHtml($output);
	$pdf->render();
	$pdf->stream($file_name, array("Attachment" => false));
}

?>