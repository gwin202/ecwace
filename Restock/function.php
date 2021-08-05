<?php
//function.php

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
if($available_quantity == 0)
{
$update_query = "
UPDATE product SET 
product_status = 'inactive' 
WHERE product_id = '".$product_id."'
";
$statement = $db->query($update_query);

}
return $available_quantity;
}


?>