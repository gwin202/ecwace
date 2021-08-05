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
		 <script src="js/jquery.min.js"></script>
		 <script src="js/jquery_ui.min.js"></script>
	 <script type="text/javascript">
	$(document).ready(function(){
		$('#from_date').datepicker({
			dateFormat: 'yy-mm-dd'
		});
		$('#to_date').datepicker({
			dateFormat: 'yy-mm-dd'
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
					 <div class="row" id="report">
					 <div class="col-lg-5 mt-2">
					 <div class="form-row">
					 <div class="col-lg-5">
					 <div class="form-group">
					 <label>Start Date:</label>
					 <input type="text" id="from_date" name="from_date" class="form-control" placeholder="From Date">
					 </div>
					 </div>
					 <div class="col-lg-5">
					 <div class="form-group">
					 <label>End Date:</label>
					 <input type="text" id="to_date" name="to_date" class="form-control" placeholder="To Date">
					 
					 </div>
					 
					 </div>
					 <div class="form-group">
					 <div class="col-lg-2 mt-2">
						 <br><br>
					 <input type="submit" value="Search" name="filter" class="btn btn-info" id="filter">
					 </div>
					 </div>
					 

					 </div>
					 
					 </div>
					<div class="col-lg-6 mt-2">
					<div class="input-group form-row m-3">
						<!-- <input type="text" class="form-control" placeholder="Search..." id="search" name="search" autocomplete="on"> -->
						<select name="Select" id="search" class="form-control">
						<option value="">Select</option>
						
						</select>
						<select name="type" id="type" id="type" class="form-control">
						<option value="">Select</option>
						<option value="product_category">Product Category</option>
						<option value="payment_status">Payment Method</option>
						<option value="dom_product">Dom Product</option>
						</select>
						<div class="form-group-append">
							<button type="submit" name="typenow" id="typenow" class="btn btn-info">
							<i class="fa fa-search"></i>
							</button>
						</div>
					</div>

					</div>
						 
						
							
							</div>
				 		<div id="inven">
	     					<?php
					function count_total_order_value($db)
					{
						$query = "
						SELECT sum(inventory_order_total) as total_order_value FROM inventory_order 
						WHERE inventory_order_status='active'
						";
						
						$statement = $db->query($query);
						// 
						
						foreach($statement as $row)
						{
							return number_format($row['total_order_value'], 2);
						}
					}
						 $statement=$db->query("
						 SELECT * FROM inventory_order_product
						 INNER JOIN inventory_order ON inventory_order_product.inventory_order_id = inventory_order.inventory_order_id
						 INNER JOIN product on inventory_order_product.product_id = product.product_id
                         INNER JOIN user_details on inventory_order.user_id = user_details.user_id
                         INNER JOIN category on product.category_id = category.category_id
						 where inventory_order.inventory_order_status='active'
					 ") or die($db->error);
					 function count__order_profit($db)
{

	$query = "SELECT sum(tax) as total_order_profit FROM inventory_order 
	WHERE inventory_order_status='active'";
	$statement = $db->query($query);

	foreach($statement as $row){
		return number_format($row['total_order_profit'], 2);
	}
}
						 # code...
						 echo "<table class='table table-striped table-condensed tbl-pin'>
															<tr>
																<th>S/N</th>
																<th>Product Category</th>
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
															$product_category = $row["category_name"];
															$custom_phone = $row["inventory_order_address"];
															
															$payment_status = '';

																if($row['payment_status'] == 'cash')
																{
																	$payment_status = '<span class="label label-primary">Cash</span>';
																}
																else if($row['payment_status'] == 'donation'){
																	$payment_status = '<span class="label label-info">Donation</span>';
																}
																else if($row['payment_status'] == 'transfer'){
																	$payment_status = '<span class="label label-info"> Transfer</span>';
																}
																else
																{
																	$payment_status = '<span class="label label-warning">Credit</span>';
																}
																$payment_method = $payment_status;
															$inventory_oder_date = $row["inventory_order_created_date"];
															$product_name = $row["product_name"];
															$product_unit= $row["product_unit"];
															$product_base_price = $row["product_base_price"];
															$product_cost_price = $row["product_tax"];
															$product_quantity = $row["quantity"];
														$user_name=$row["user_name"];
															$inventory_oder_total = '<strike>N</strike>'.number_format($row['inventory_order_total']);
															$total=count_total_order_value($db);
								   
															@$i++;
															echo"
																<tr>
																<td>$i</td>
																<td>$product_category</td>
																<td>$custom_name</td>
																<td>$product_name</td>
																<td>$product_quantity  $product_unit</td>
																<td>$product_cost_price</td>
																<td>$product_base_price</td>
																<td>$inventory_oder_total</td>
																<td>$payment_method</td>
																<td>$inventory_oder_date</td>
																<td>$user_name</td>
																</tr>
								   
								   
															";
														}
														echo " </table>
															
																<div class='clearfix'>
																	<div class='row gpa'>
																		<div class='col-md-4'> 
																	<h4>	Total = <strike>N</strike> $total
																				<br>
																		</h4>
																		</div>
														
																		
																		</div>
																	
																	</div>
																
															
														";
					 

						 
														# code...
 
									?>
				            		
	 				</div>
					 <button class='btn btn-info btn-xs no-print' onclick='window.print()' type='button' align='right' id='love'>
																			<i class='fa fa-print'></i> Print
																		</button>
																		<button class='btn btn-info btn-xs no-print' type='button' align='right' id='hide'>
																			<i class='fa fa-print'></i> Hide
																		</button>
	 			</div>	
	 		</div>
	 	</section>
 	</div>
 	
 	<footer> </footer>
		
</div>
<script>
	$(document).ready(function(){
		$('#filter').click(function () {
			var from_date = $('#from_date').val();
			var to_date = $('#to_date').val();
			if (from_date != '' && to_date != '') {
				$.ajax({
					url: 'order_fetch.php',
					method: 'POST',
					data: {from_date:from_date, to_date:to_date},
					success:function(data){
						$('#inven').html(data);
					}
				});
			}
			else{
				alert('Please select a date');
			}

		});
		$('#type').change(function(){
			var type = $('#type').val();
			$.post('product_category.php', { type: type }, function(data) //send id to backends
        {
			$('#search').html(data);
        });
		});
		$('#typenow').click(function(){
			var from_date = $('#from_date').val();
			var to_date = $('#to_date').val();
			var  search = $('#search').val();
			var type = $('#type').val();
			if (from_date != '' && to_date != '' && search != '' && type != '') {
				$.ajax({
					url: 'product_fetch.php',
					method : 'POST',
					data:{from_date: from_date, to_date: to_date, search: search, type: type},
					success: function(data){
						$('#inven').html(data);
					}
				})
				// alert('Success');
			}
			else{
				alert('not select')
			}

		});
		$("#love").click(function(){
			$('#report').hide();
		});
		$('#hide').click(function () {
			$('#report').hide();
			$('#hide').hide();
		});
	

	});
</script>
 </body>
 </html>