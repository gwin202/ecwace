<?php
include('connections/connection.php');
if (isset($_POST['type'])) {
	# code...
    $type = $_POST['type'];
    if ($type == 'product_category') {
        # code...
        $query = "
        SELECT * FROM category 
        WHERE category_status = 'active' 
        ORDER BY category_name ASC
        ";
        $statement = $db->query($query);
        $output = '';
        foreach($statement as $row)
        {
            $output .= '<option value="'.$row["category_id"].'">'.$row["category_name"].'</option>';
        }
        echo $output;
    }elseif ($type == 'payment_status') {
        # code...
        $query = "SELECT * FROM payment";
        $statement = $db->query($query);
        $output ='';
        foreach ($statement as $row) {
            $output .= '<option value="' . $row['payment'].'">'.$row["payment"].'</option>';
        }
        echo $output;

    }
    elseif($type == 'dom_product'){
        
        echo '<option value="dom_product">Dom Product</option>';
        echo '<option value="love"> Love </option>';

    }
	


	
	
}



?>