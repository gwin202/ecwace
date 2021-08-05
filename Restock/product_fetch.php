<?php
include('connections/connection.php');
function fetch_product_details($product_id, $db)
{
	$query = "
	SELECT * FROM product 
	WHERE product_id = '".$product_id."'";
	$statement = $db->query($query);
	foreach($statement as $row)
	{
		$output['product_name'] = $row["product_name"];
		$output['quantity'] = $row["product_quantity"];
		$output['price'] = $row['product_base_price'];
		$output['tax'] = $row['product_tax'];
	}
	return $output;
}

function available_product_quantity($db, $product_id)
{
	$product_data = fetch_product_details($product_id, $db);
	$query = "
	SELECT 	inventory_order_product.quantity FROM inventory_order_product 
	INNER JOIN inventory_order ON inventory_order.inventory_order_id = inventory_order_product.inventory_order_id
	WHERE inventory_order_product.product_id = '".$product_id."' AND
	inventory_order.inventory_order_status = 'active'
	";
	$statement = $db->query($query);
	$total = 0;
	foreach($statement as $row)
	{
		$total = $total + $row['quantity'];
	}
	$available_quantity = intval($product_data['quantity']) - intval($total);
	return $available_quantity;
}

function count_total_order_value($db)
{
	$type = $_POST['type'];
	$search= $_POST['search'];
	$from_date = $_POST['from_date'];
	$to_date = $_POST['to_date'];
	if ($type == 'payment_status') {
		# code...
		$query = "SELECT sum(inventory_order_total) as total_order_value FROM inventory_order 
	INNER JOIN inventory_order_product on inventory_order.inventory_order_id = inventory_order_product.inventory_order_id 
		WHERE inventory_order_date BETWEEN '$from_date' AND '$to_date' AND inventory_order.payment_status ='$search' AND inventory_order_status='active'";
	$statement = $db->query($query);

	foreach($statement as $row){
		return number_format($row['total_order_value'], 2);
	}
	}
	elseif ($type == 'product_category') {
		$query ="SELECT sum(inventory_order_total) as total_order_value FROM inventory_order 
		INNER JOIN inventory_order_product on inventory_order.inventory_order_id = inventory_order_product.inventory_order_id
		INNER join product on inventory_order_product.product_id = product.product_id
			WHERE inventory_order_date BETWEEN '$from_date' AND '$to_date' AND product.category_id = '$search'  AND inventory_order_status='active'";
			$statement = $db->query($query);
			foreach($statement as $row){
				return number_format($row['total_order_value']);
			}
		# code...
	}
	$query = "SELECT sum(inventory_order_total) as total_order_value FROM inventory_order 
	INNER JOIN inventory_order_product on inventory_order.inventory_order_id = inventory_order_product.inventory_order_id 
		WHERE inventory_order_date BETWEEN '$from_date' AND '$to_date' AND inventory_order.payment_status ='$search' AND inventory_order_status='active'";
	$statement = $db->query($query);

	foreach($statement as $row){
		return number_format($row['total_order_value'], 2);
	}
}
// function count__order_profit($db)
// {
// 	$from_date = $_POST['from_date'];
// 	$to_date = $_POST['to_date'];
// 	$search = $_POST['search'];
// 	$query = "SELECT sum(inventory_order_total) as total_order_value FROM inventory_order 
// 	INNER JOIN inventory_order_product on inventory_order.inventory_order_id = inventory_order_product.inventory_order_id 
// 		WHERE inventory_order_date BETWEEN '$from_date' AND '$to_date' AND inventory_order.payment_status ='$search' AND inventory_order_status='active'";
// 	$statement = $db->query($query);

// 	foreach($statement as $row){
// 		return number_format($row['total_order_profit'], 2);
// 	}
// }

if (isset($_POST['from_date'],$_POST['to_date']) && isset($_POST['search']) && $_POST['type']) {
	# code...
	$type = $_POST['type'];
	$from_date = $_POST['from_date'];
	$to_date = $_POST['to_date'];
	$search = $_POST['search'];
	if ($type == 'product_category') {
		# code...
		$statement = $db->query("SELECT * FROM inventory_order_product
		INNER JOIN inventory_order ON inventory_order_product.inventory_order_id = inventory_order.inventory_order_id
		INNER JOIN product on inventory_order_product.product_id = product.product_id
		INNER JOIN category on product.category_id = category.category_id
		INNER JOIN user_details on inventory_order.user_id = user_details.user_id
		 WHERE inventory_order_date BETWEEN '$from_date' AND '$to_date' AND inventory_order.inventory_order_status='active' AND category.category_id = '$search' GROUP BY inventory_order_product.inventory_order_product_id")or die($db->error);
		 	$output ="<table class='table table-striped table-condensed tbl-pin'>
			 <tr>
			 <th>S/N</th>
			 <th>Customer Name</th>
			 <th>Product Category</th>
			 <th>Product Name</th>
			 <th>Product Quantity</th>
			 <th>Cost price</th>
			 <th>Base price</th>
			 <th>Total Amount</th>
			 <th>Payment Method</th>
			 <th>Oder Date</th>
			 <th>Created By</th>
			 </tr>
			 ";
			 if($statement->num_rows){
			 foreach ($statement as $row) {
				 # code...
				 $custom_name = $row["inventory_order_name"];
				 $custom_phone = $row["inventory_order_address"];
				 $product_category = $row["category_name"];
				 $payment_method = $row["payment_status"];
				 $inventory_oder_date = $row["inventory_order_created_date"];
				 $product_name = $row["product_name"];
				 $product_unit= $row["product_unit"];
				 $product_base_price = $row["product_base_price"];
				 $product_cost_price = $row["product_tax"];
				 $product_quantity = $row["quantity"];
			 $user_name=$row["user_name"];
				 $inventory_oder_total = '<strike>N</strike>'.number_format($row['inventory_order_total']);
				 $total= count_total_order_value($db);
		 
				 @$i++;
				 $output .="
				 <tr>
																		 <td>$i</td>
																		 <td>$custom_name</td>
																		 <td>$product_category</td>
																		 <td>$product_name</td>
																		 <td>$product_quantity  $product_unit</td>
																		 <td>$product_cost_price</td>
																		 <td>$product_base_price</td>
																		 <td>$inventory_oder_total</td>
																		 <td>$payment_method</td>
																		 <td>$inventory_oder_date</td>
																		 <td>$user_name</td>
			 
		 
			 
		 
		 ";
		 
			 }
			 $output .="
			 </table>
			 <div class='clearfix'>
			 <div class='row gpa'>
					 <div class='col-md-4'> 
						 <h4>	Total = <strike>N</strike>  $total
						 <br>
						</h4>
				 </div>
			 
				 </div>
		
					 </div>";
			 echo $output;
			}
			else{
				$output .= 'Product not Available';
				echo $output;
			}


	}
	else if($type == 'payment_status'){
		$statement = $db->query("SELECT * FROM inventory_order_product
		INNER JOIN inventory_order ON inventory_order_product.inventory_order_id = inventory_order.inventory_order_id
		INNER JOIN product on inventory_order_product.product_id = product.product_id
		INNER JOIN category on product.category_id = category.category_id
		INNER JOIN user_details on inventory_order.user_id = user_details.user_id
		 WHERE inventory_order_date BETWEEN '$from_date' AND '$to_date' AND inventory_order.inventory_order_status='active' AND inventory_order.payment_status ='$search' GROUP BY inventory_order_product.inventory_order_product_id")or die($db->error);
		 	$output ="<table class='table table-striped table-condensed tbl-pin'>
			 <tr>
			 <th>S/N</th>
			 <th>Customer Name</th>
			 <th>Product Category</th>
			 <th>Product Name</th>
			 <th>Product Quantity</th>
			 <th>Cost price</th>
			 <th>Base price</th>
			 <th>Total Amount</th>
			 <th>Payment Method</th>
			 <th>Oder Date</th>
			 <th>Created By</th>
			 </tr>
			 ";
			 if ($statement->num_rows) {
				 # code...
			 
			 foreach ($statement as $row) {
				 # code...
				 $custom_name = $row["inventory_order_name"];
				 $custom_phone = $row["inventory_order_address"];
				 $product_category = $row["category_name"];
				 $payment_method = $row["payment_status"];
				 $inventory_oder_date = $row["inventory_order_created_date"];
				 $product_name = $row["product_name"];
				 $product_unit= $row["product_unit"];
				 $product_base_price = $row["product_base_price"];
				 $product_cost_price = $row["product_tax"];
				 $product_quantity = $row["quantity"];
			 $user_name=$row["user_name"];
				 $inventory_oder_total = '<strike>N</strike>'.number_format($row['inventory_order_total']);
				 $total= count_total_order_value($db);
		 
				 @$i++;
				 $output .="
				 <tr>
																		 <td>$i</td>
																		 <td>$custom_name</td>
																		 <td>$product_category</td>
																		 <td>$product_name</td>
																		 <td>$product_quantity  $product_unit</td>
																		 <td>$product_cost_price</td>
																		 <td>$product_base_price</td>
																		 <td>$inventory_oder_total</td>
																		 <td>$payment_method</td>
																		 <td>$inventory_oder_date</td>
																		 <td>$user_name</td>
			 
		 
			 
		 
		 ";
		 
			 }
			 $output .="
			 </table>
			 <div class='clearfix'>
		 <div class='row gpa'>
				 <div class='col-md-4'> 
					 <h4>	Total = <strike>N</strike>  $total
					 <br>
					</h4>
			 </div>
		 
			 </div>
					 </div>";
			 echo $output;
			}
			else {
				# code...
				$output .="Not Available ";
				echo $output;
			}


	}
	elseif ($type == 'dom_product') {
		# code...
		$query=$db->query("SELECT * FROM `product` WHERE `product_status` = 'active'");
		$output ="<table class='table table-striped table-condensed tbl-pin'>
		<tr>
		<th>S/N</th>
		<th>Product Name</th>
		<th>Product Quantity</th>
		<th>Cost price</th>
		<th>Base price</th>
		<th>Created Date Date</th>
		</tr>
		";

		foreach ($query as $row) {
			$product_id = $row["product_id"];
			$product_name = $row['product_name'];
			$product_quantity = $row['product_quantity'];
			// $product_unit = $row['product_unit'];
			$product_base_price = $row['product_base_price'];
			$product_tax = $row['product_tax'];
			$product_enter_by = $row['product_enter_by'];
			$product_date = $row['product_date'];
			$product_avalible =available_product_quantity($db, $row["product_id"]) . ' ' . $row["product_unit"];
			
			$statement = $db->query("SELECT * FROM inventory_order_product
		INNER JOIN inventory_order ON inventory_order_product.inventory_order_id = inventory_order.inventory_order_id WHERE inventory_order.inventory_order_date BETWEEN '$from_date' AND '$to_date' AND inventory_order_product.product_id='$product_id' AND inventory_order.inventory_order_status = 'active'") or die($db->error);
			if ($statement->num_rows) {
				// foreach ($statement as $sales) {
				// 	# code...
				// 	// echo $sales['product_id'] . " <br>". $sales['inventory_order_total'];
				// }
			}else{
				@$i++;
				$output .="
					<tr>
					<td>$i</td>
					<td>$product_name</td>
					<td>$product_avalible</td>
					<td>$product_tax</td>
					<td>$product_base_price</td>
					<td>$product_date</td>


					</tr>
				
				";
			}

		}
		$output .="
			 </table>
			 <div class='clearfix'>
		 <div class='row gpa'>
				 <div class='col-md-4'> 
					 </h4>
			 </div>
		 
			 </div>
					 </div>";
			 echo $output;
	}

}




?>