 <?php 
 	include("connections/connection.php");
	//  include("function.php");


  ?>
 <!DOCTYPE html>
 <html>
 <head>
 	<title>Report</title>
 	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">

 	<link rel="stylesheet" type="text/css" href="css/style.css">
 	<link rel="stylesheet" type="text/css" href="css/font-awesome.min.css">
	 <link rel="stylesheet" href="css/datepicker.css">
 		<link rel="stylesheet" href="css/jquery_ui.css">
		 <script src="js/jquery.js"></script>
		 <script src="js/jquery_ui.min.js"></script>
		 <script src="js/bootstrap.min.js"></script>
		 <script src="js/bootstrap-datepicker1.js"></script>
		 <script>
	$(document).ready(function(){
		$(document).on('click','.restock',function(){
			var product_id =$(this).attr("id");
			//alert(product_id);
			$('#productModal').modal('show');
			var btn_action = 'fetch_single';
			// $.post('order_fetch.php',{product_id: product_id, btn_action: btn_action},function(data){
			// 	$('#productModal').modal('show');
            //     $('#category_id').val(data.category_id);
            //     $('#brand_id').html(data.brand_select_box);
            //     $('#brand_id').val(data.brand_id); 
            //     $('#product_name').val(data.product_name);
            //     $('#product_description').val(data.product_description);
            //     $('#product_quantity').val(data.product_quantity);
            //     $('#product_unit').val(data.product_unit);
            //     $('#product_base_price').val(data.product_base_price);
            //     $('#product_tax').val(data.product_tax);
            //     $('.modal-title').html("<i class='fa fa-pencil-square-o'></i> Edit Product");
            //     $('#product_id').val(product_id);
            //     $('#action').val("Edit");
            //     $('#btn_action').val("Edit");

			// });
		});
	
	

	});
</script>

 	<meta name="viewport" content="width=device-width, initial-scale=1.0">
 </head>
 <body class="login-body-background">
	 

 <div class="all">
 	<div class="navbar-header hidden-xs visible-sm visible-md visible-lg">
      <a class="navbar-brand" href="#"><img src="images/"></a>
    </div>
    <h1 class="text-center">Order Details Report</h1>
 </div>
 


<div class="wrapper container">
 	<div class="content">
	 	<section>
	 		<div class="row">
	 			<div class="panel panel-default">
	 				<div class="panel-heading">
		 				<div class="site-map">
		 					<h3>Report <small>Statistics Overview</small></h3>
		 				</div>
	 					
	 				</div>
	 				<div class="panel-body">
				
				 		<div id="inven">
	     					<?php
							 function fetch_product_details($product_id, $db)
							 {
								 $query = "
								 SELECT * FROM product 
								 WHERE product_id = '".$product_id."'";
								 $statement = $db->query($query);

								 foreach($statement as $row)
								 {
									 $outputs['product_name'] = $row["product_name"];
									 $outputs['quantity'] = $row["product_quantity"];
									 $outputs['price'] = $row['product_base_price'];
									 $outputs['tax'] = $row['product_tax'];
								 }
								 return $outputs;
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
						 $statement=$db->query("
						 SELECT * FROM product 
							INNER JOIN brand ON brand.brand_id = product.brand_id
							INNER JOIN category ON category.category_id = product.category_id 
							INNER JOIN user_details ON user_details.user_id = product.product_enter_by
												") or die($db->error);

						 # code...
						 echo "<table class='table table-striped table-condensed tbl-pin'>
															<tr>
																<th>S/N</th>
																<th>Product Category</th>
																<th> Brand Name</th>
																<th>Product Name</th>
																<th>Available Quantity</th>
																<th>Cost price</th>
																<th>Base price</th>
																<th>Created By</th>
																<th></th>
																<th></th>
																
															</tr>

														";
														
														foreach ($statement as $row) {
															# code...
															$status = '';
															if($row['product_status'] == 'active')
															{
																$status = '<span id="product" class="label label-success">Active</span>';
															}
															else
															{
																$status = '<span class="label label-danger">Inactive</span>';
															} 
															$product_id = $row['product_id'];
															$category_name = $row['category_name'];
															$brand_name = $row['brand_name'];
															$product_name = $row['product_name'];
															$available_quantity =available_product_quantity($db,$row["product_id"].' '.$row["product_unit"]);
															$user_name= $row['user_name'];
															$product_base_price = $row['product_base_price'];
															$product_tax = $row['product_tax'];
															$product_quantity =$row['product_quantity'];
															$status_now = $status;
															$restock='<button type="submit" name="restock" id="'.$row['product_id'].'" class="btn btn-info btn-xs restock">Restock</button>';


								   
															@$i++;
															echo"
																<tr>
																<td>$i</td>
																<td>$category_name</td>
																<td>$brand_name</td>
																<td>$product_name</td>
																<td>$available_quantity</td>
																<td>$product_tax</td>
																<td>$product_base_price</td>
																<td>$user_name</td>
																<td>$status_now</td>
																<td>$restock</td>
															
																</tr>
								   
								   
															";
														}
														echo " </table>
														";
					 

						 
														# code...
 
									?>
				            		
	 				</div>
	 			</div>	
	 		</div>
			 <input type="submit" value="Submit" class="btn btn-primary submit">
	 	</section>
 	</div>
	 <div id="productModal" class="modal fade">
            <div class="modal-dialog">
                <form method="post" id="product_form">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title"><i class="fa fa-plus"></i> Add Product</h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Select Category</label>
                                <select name="category_id" id="category_id" class="form-control" required>
                                    <option value="">Select Category</option>
                                    <?php //echo fill_category_list($connect);?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Select Brand</label>
                                <select name="brand_id" id="brand_id" class="form-control" required>
                                    <option value="">Select Brand</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Enter Product Name</label>
                                <input type="text" name="product_name" id="product_name" class="form-control" required />
                            </div>
                            <div class="form-group">
                                <label>Enter Product Description</label>
                                <textarea name="product_description" id="product_description" class="form-control" rows="5" required></textarea>
                            </div>
                            <div class="form-group">
                                <label>Enter Product Quantity</label>
                                <div class="input-group">
                                    <input type="hidden" name="product_quantity" id="product_quantity" class="form-control" required pattern="[+-]?([0-9]*[.])?[0-9]+" /> 
									<input type="text" name="product_quantity_now" id="product_quantity_now" class="form-control" required pattern="[+-]?([0-9]*[.])?[0-9]+" /> 
                                    <span class="input-group-addon">
                                        <select name="product_unit" id="product_unit" required>
                                            <option value="">Select Unit</option>
                                            <option value="Bags">Bags</option>
                                            <option value="Bottles">Bottles</option>
                                            <option value="Box">Box</option>
                                            <option value="Dozens">Dozens</option>
                                            <option value="Feet">Feet</option>
                                            <option value="Gallon">Gallon</option>
                                            <option value="Grams">Grams</option>
                                            <option value="Inch">Inch</option>
                                            <option value="Kg">Kg</option>
                                            <option value="Liters">Liters</option>
                                            <option value="Meter">Meter</option>
                                            <option value="Nos">Nos</option>
                                            <option value="Packet">Packet</option>
                                            <option value="Rolls">Rolls</option>
                                        </select>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Enter Product Base Price</label>
                                <input type="text" name="product_base_price" id="product_base_price" class="form-control" required pattern="[+-]?([0-9]*[.])?[0-9]+" />
                            </div>
                            <div class="form-group">
                                <label>Enter Product Cost Price</label>
                                <input type="text" name="product_tax" id="product_tax" class="form-control" required pattern="[+-]?([0-9]*[.])?[0-9]+" />
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="hidden" name="product_id" id="product_id" />
                            <input type="hidden" name="btn_action" id="btn_action" />
                            <input type="submit" name="action" id="action" class="btn btn-info" value="Add" />
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
 	
 	<footer> </footer>
		
</div>

 </body>
 </html>