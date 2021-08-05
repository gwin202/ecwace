<?php

//order_fetch.php

include('connections/connection.php');
function count_total_order_value($db)
{
	$from_date = $_POST['from_date'];
	$to_date = $_POST['to_date'];
	$query = "SELECT sum(inventory_order_total) as total_order_value FROM inventory_order 
	WHERE inventory_order_date BETWEEN '$from_date' AND '$to_date' AND inventory_order_status='active'";
	$statement = $db->query($query);

	foreach($statement as $row){
		return number_format($row['total_order_value'], 2);
	}
}
function count__order_profit($db)
{
	$from_date = $_POST['from_date'];
	$to_date = $_POST['to_date'];
	$query = "SELECT sum(tax) as total_order_profit FROM inventory_order 
	WHERE inventory_order_date BETWEEN '$from_date' AND '$to_date' AND inventory_order_status='active'";
	$statement = $db->query($query);

	foreach($statement as $row){
		return number_format($row['total_order_profit'], 2);
	}
}

if (isset($_POST['from_date'],$_POST['to_date'])) {
	# code...
	$from_date = $_POST['from_date'];
	$to_date = $_POST['to_date'];
	$statement = $db->query("SELECT * FROM inventory_order_product
	INNER JOIN inventory_order ON inventory_order_product.inventory_order_id = inventory_order.inventory_order_id
	INNER JOIN product on inventory_order_product.product_id = product.product_id
	INNER JOIN user_details on inventory_order.user_id = user_details.user_id
	 WHERE inventory_order_date BETWEEN '$from_date' AND '$to_date' AND inventory_order.inventory_order_status='active'")or die($db->error);
	$output ="<table class='table table-striped table-condensed tbl-pin'>
	<tr>
	<th>S/N</th>
	<th>Customer Name</th>
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
	foreach ($statement as $row) {
		# code...
		$custom_name = $row["inventory_order_name"];
		$custom_phone = $row["inventory_order_address"];
		$payment_method = $row["payment_status"];
		$inventory_oder_date = $row["inventory_order_created_date"];
		$product_name = $row["product_name"];
		$product_unit= $row["product_unit"];
		$product_base_price = $row["product_base_price"];
		$product_cost_price = $row["product_tax"];
		$product_quantity = $row["quantity"];
	$user_name=$row["user_name"];
		$inventory_oder_total = '<strike>N</strike>'.number_format($row['inventory_order_total']);
		$total= count__order_profit($db);

		@$i++;
		$output .="
		<tr>
																<td>$i</td>
																<td>$custom_name</td>
																<td>$product_name</td>
																<td>$product_quantity  $product_unit</td>
																<td>$product_cost_price<td>
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
			<h4>	Total = <strike>N</strike> $total
			<br>
			</h4>
	</div>

	</div>
			</div>";
	echo $output;
	
}
function fill_category_list($db)
{
	$query = "
	SELECT * FROM category 
	WHERE category_status = 'active' 
	ORDER BY category_name ASC
	";
	$statement = $db->query($query);
	$result = $statement->fetch_all();
	$output = '';
	foreach($result as $row)
	{
		$output .= '<option value="'.$row["category_id"].'">'.$row["category_name"].'</option>';
	}
	return $output;
}


?>