<?php
//function.php

function fill_category_list($db)
{
	$query = "
	SELECT * FROM category 
	WHERE category_status = 'active' 
	ORDER BY category_name ASC
	";
	$statement = $db->query($query);
	// $statement->execute();
	$result = $statement->fetch_all();
	$output = '';
	foreach($result as $row)
	{
		$output .= '<option value="'.$row["category_id"].'">'.$row["category_name"].'</option>';
	}
	return $output;
}

function fill_brand_list($db, $category_id)
{
	$query = "SELECT * FROM brand 
	WHERE brand_status = 'active' 
	AND category_id = '".$category_id."'
	ORDER BY brand_name ASC";
	$statement = $db->query($query);
	// $statement->execute();
	$result = $statement->fetch_all();
	$output = '<option value="">Select Brand</option>';
	foreach($result as $row)
	{
		$output .= '<option value="'.$row["brand_id"].'">'.$row["brand_name"].'</option>';
	}
	return $output;
}

function get_user_name($db, $user_id)
{
	$query = "
	SELECT user_name FROM user_details WHERE user_id = '".$user_id."'
	";
	$statement = $db->query($query);
	// $statement->execute();
	$result = $statement->fetch_all();
	foreach($result as $row)
	{
		return $row['user_name'];
	}
}

function fill_product_list($db)
{
	$query = "
	SELECT * FROM product 
	WHERE product_status = 'active' 
	ORDER BY product_name ASC
	";
	$statement = $db->query($query);
	// $statement->execute();
	$result = $statement->fetch_all();
	$output = '';
	foreach($result as $row)
	{
		$output .= '<option value="'.$row["product_id"].'">'.$row["product_name"].'</option>';
	}
	return $output;
}

function fetch_product_details($product_id, $db)
{
	$query = "
	SELECT * FROM product 
	WHERE product_id = '".$product_id."'";
	$statement = $db->query($query);
	// $statement->execute();
	$result = $statement->fetch_all();
	foreach($result as $row)
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
	// $statement->execute();
	$result = $statement->fetch_all();
	$total = 0;
	foreach($result as $row)
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
		// $statement->execute();
	}
	return $available_quantity;
}

function count_total_user($db)
{
	$query = "
	SELECT * FROM user_details WHERE user_status='active'";
	$statement = $db->query($query);
	// $statement->execute();
	return $statement->num_row;
}

function count_total_category($db)
{
	$query = "
	SELECT * FROM category WHERE category_status='active'
	";
	$statement = $db->query($query);
	// $statement->execute();
	return $statement->num_row;
}

function count_total_brand($db)
{
	$query = "
	SELECT * FROM brand WHERE brand_status='active'
	";
	$statement = $db->query($query);
	// $statement->execute();
	return $statement->num_row;
}

function count_total_product($db)
{
	$query = "
	SELECT * FROM product WHERE product_status='active'
	";
	$statement = $db->query($query);
	// $statement->execute();
	return $statement->num_row;
}

function count_total_order_value($db)
{
	$query = "
	SELECT sum(inventory_order_total) as total_order_value FROM inventory_order 
	WHERE inventory_order_status='active'
	";
	if($_SESSION['type'] == 'user')
	{
		$query .= ' AND user_id = "'.$_SESSION["user_id"].'"';
	}
	$statement = $db->query($query);
	// $statement->execute();
	$result = $statement->fetch_all();
	foreach($result as $row)
	{
		return number_format($row['total_order_value'], 2);
	}
}

function count_total_cash_order_value($db)
{
	$query = "
	SELECT sum(inventory_order_total) as total_order_value FROM inventory_order 
	WHERE payment_status = 'cash' 
	AND inventory_order_status='active'
	";
	if($_SESSION['type'] == 'user')
	{
		$query .= ' AND user_id = "'.$_SESSION["user_id"].'"';
	}
	$statement = $db->query($query);
	// $statement->execute();
	$result = $statement->fetch_all();
	foreach($result as $row)
	{
		return number_format($row['total_order_value'], 2);
	}
}

function count_total_credit_order_value($db)
{
	$query = "
	SELECT sum(inventory_order_total) as total_order_value FROM inventory_order WHERE payment_status = 'credit' AND inventory_order_status='active'
	";
	if($_SESSION['type'] == 'user')
	{
		$query .= ' AND user_id = "'.$_SESSION["user_id"].'"';
	}
	$statement = $db->query($query);
	// $statement->execute();
	$result = $statement->fetch_all();
	foreach($result as $row)
	{
		return number_format($row['total_order_value'], 2);
	}
}



?>