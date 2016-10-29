@extends('layouts.app')

@section('content')
<div class="col-md-9">
	<div class="content-box-large">
		@if (count($errors) > 0)
		    <div class="alert alert-danger">
		        <ul>
		            @foreach ($errors->all() as $error)
		                <li>{{ $error }}</li>
		            @endforeach
		        </ul>
		    </div>
		@endif
		@if (Session::has('purchase_message'))
		   <div class="alert alert-info">{{ Session::get('purchase_message') }}</div>
		@endif
		<div class="panel-body">
			<form role="form" id="sale_form" method="POST" action="">
			<input type="hidden" name="_token" value="{{ csrf_token() }}" />
			<fieldset>
				<div class="panel-heading">
					<legend>Add Sale Stock</legend>
				</div>	
				<div class="form-group col-sm-3">
					<div>
  						<label>Select Party</label>
						<div class="bfh-selectbox hide" id="party_id" data-name="party_id" data-value="" data-filter="true">
						  <div data-value="">Select Party</div>
						  @foreach($arrayParties as $party)
						  	<div data-value="{{ $party->id }}">{{ $party->name }}</div>
						  @endforeach
						</div>
  					</div>
				</div>
				<div class="form-group col-sm-3">
					<label>Date</label>
					<div class="bfh-datepicker" data-name="sale_date" data-format="d-m-y" data-date="today">
						<div class="input-group bfh-datepicker-toggle" data-toggle="bfh-datepicker">
							<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
							<input type="text" name="sale_date" class="form-control" placeholder="" readonly="">
						</div>
					</div>
				</div>
				<div class="form-group col-sm-2">
					<label>Bilty #</label>
					<input class="form-control" placeholder="Bilty #" maxlength="15" name="bilty_no" type="text" value="">
				</div>
				<div class="form-group col-sm-4">
					<label>Cargo Address</label>
					<input class="form-control" placeholder="Cargo Address" maxlength="15" name="adda_address" type="text" value="">
				</div>
				<div class="panel-heading">
					<legend>Add All Sale Items</legend>
				</div>
				<div class="form-group col-sm-5">
					<div>
  						<label>Select Item</label>
						<div class="bfh-selectbox hide" id="product_id" data-name="product_id" data-value="" data-filter="true">
						  <div data-value="">Select Item</div>
						  @foreach($arrayProducts as $Product)
						  	<div data-value="{{ $Product->id }}">{{ $Product->name }}</div>
						  @endforeach
						</div>
  					</div>
				</div>
				<div class="form-group col-sm-1" style="padding-right: 0px;">
					<label>Quantity</label>
					<input class="form-control number_only" placeholder="Qty" maxlength="15" name="quantity" id="quantity" type="text" value="">
				</div>
				<div class="form-group col-sm-2">
					<label>List Price</label>
					<input class="form-control number_only" placeholder="List Price" maxlength="15" name="purchase_price" id="purchase_price" type="text" value="">
				</div>
				<div class="form-group col-sm-2">
					<div>
  						<label>Discount</label>
						<div class="bfh-selectbox hide" id="discount" data-name="discount" data-value="" data-filter="true">
						  <div data-value="">Select Discount</div>
						  @for($i = 51; $i <= 75; $i++ )	
						  	<div data-value="{{ $i }}">{{ $i }}%</div>
						  @endfor	
						</div>
  					</div>
				</div>
				<div class="form-group col-sm-1 hide" style="padding:0px">
					<label>Total Amount</label>
					<p class="amount_text">1,000</p>
				</div>
				<div class="form-group col-sm-1">
					<img class="cursor" id="addItems" src="/images/add-icon.png" alt="Add" style="width:60px; margin-top:7px;">
				</div>
			</fieldset>
			<div class="table-responsive">
				<table class="table">
              <thead>
                <tr>
                  <!-- <th>#</th> -->
                  <th>Item Name</th>
                  <th>Quantity</th>
                  <th>List Price</th>
                  <th>Discount</th>
                  <th>Net Price</th>
                  <th>Total Amount</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody id="addNewItems">
              </tbody>
            </table>
			</div>
			<div class="table-responsive hide" id="div_total_detail">
				<table class="table">
              <thead>
                <tr>
                  <th style="width:193px;">Total Items:&nbsp;<span id="total_items"></span></th>
                  <th>Total Quantity:&nbsp;<span id="total_quantity"></span></th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th>Grand Total Amount:&nbsp;<span id="grand_total_amount"></span></th>
                  <th></th>
                  <th></th>
                </tr>
              </thead>
            </table>
			</div>
			<div class="form-group col-sm-3">
		      <button type="buuton" id="submit_form" class="btn btn-primary">Save</button>
		    </div>
		    
		</form>
		</div>
	</div>
</div>
<input type="hidden" id="item_counter" name="item_counter" value="0" />
<style type="text/css">
.amount_text {
	text-align: center;
    font-size: 14px;
    font-weight: bold;
    padding-top: 7px;
    margin-bottom: 0px;
}
.cursor{
	cursor: pointer;
}
</style>
@endsection

@section('custom_js')
	<script type="text/javascript">
		$(document).ready(function () {
			// shows drop down list when page loaded
			$("#product_id").removeClass('hide');
			$("#discount").removeClass('hide');
			$("#party_id").removeClass('hide');
			// submit form
			$("#submit_form").click(function () {
				$("#sale_form").submit();
			});
			$("#item_counter").val(0);
			// Add more items
			var counter = 0;
			$("#addItems").click(function () {
				var item_counter = $("#item_counter").val();
				counter = parseInt(item_counter) + parseInt(1);
				var inner_counter = $("#inner_counter").html();
				$("#item_counter").val(counter);
				$("#total_items").html(counter);
				var product_value = $("#product_id").find('.bfh-selectbox-option').html();
				var product_id = $("#product_id").find("input[name='product_id']").val();
				var discount_percent = $("#discount").find("input[name='discount']").val();
				var quantity = $("#quantity").val();
				var purchase_price = $("#purchase_price").val();
				var discount_value = $("#discount").find('.bfh-selectbox-option').html();
				var discount_id = $("#discount").find("input[name='discount']").val();
				var AlreadyId = $("#Product_"+product_id+"").closest('tr').attr('id');
				if (quantity.indexOf(",") >= 0)
					quantity = quantity.replace(',', '');
				if (purchase_price.indexOf(",") >= 0)
					purchase_price = purchase_price.replace(',', '');
				if(discount_value == "Select Discount")
					discount_value = 0;
				if(discount_id == "")
					discount_id = 0;
				var net_price = (purchase_price - (purchase_price * 1/100 * discount_percent)).toFixed(2);
				var total_amount = quantity * net_price;
				if(!AlreadyId && product_value != "Select Item" && quantity != "" && discount_value != "Select Discount" && purchase_price != "" && total_amount != 0)
				{
					$("#div_total_detail").removeClass('hide');
					var str = "";
			          str = "<tr id='Product_"+product_id+"'>";
	                  //str += "<td id='inner_counter'>"+counter+"</td>";
	                  str += "<td>"+product_value+"<input type='hidden' name='product_id[]' value='"+product_id+"'></td>";
	                  str += "<td>"+addCommas(quantity)+"<input id='Quantity_"+product_id+"' type='hidden' name='quantity[]' value='"+quantity+"'></td>";
	                  str += "<td>"+addCommas(purchase_price)+"<input type='hidden' name='sale_price[]' value='"+purchase_price+"'></td>";
	                  str += "<td>"+discount_value+"<input type='hidden' name='discount_id[]' value='"+discount_id+"'></td>";
	                  str += "<td>"+net_price+"<input id='NetPrice_"+product_id+"' type='hidden' name='net_price[]' value='"+net_price+"'></td>";
	                  str += "<td>"+addCommas(total_amount)+"<input id='TotalAmount_"+product_id+"' type='hidden' name='total_amount[]' value='"+total_amount+"'></td>";
	                  str += "<td><img class='cursor deleteItems' src='/images/delete.png' alt='Delete' id='"+product_id+"'></td>";
	                  str += "</tr>";
			          $('#addNewItems').append(str);
			          // Grand total quantity
			          var total_quantity = $("#total_quantity").html();
			          if(total_quantity == "")
			          	total_quantity = 0;
			          else
			          	total_quantity = $("#total_quantity").html();
			          //console.log(quantity + "***" + total_quantity);
			          if(total_quantity != 0)
			          {
			          	if (total_quantity.indexOf(",") >= 0)
					  	total_quantity = total_quantity.replace(',', '');	
			          }
			          var grand_total_quatity = parseInt(quantity) + parseInt(total_quantity);
			          $("#total_quantity").html(addCommas(grand_total_quatity));
			          
			          // grand total amount
			          var grand_total_amount = $("#grand_total_amount").html();
			          if(grand_total_amount == "")
			          	grand_total_amount = 0;
			          else
			          	grand_total_amount = $("#grand_total_amount").html();
			          //console.log(quantity + "***" + total_quantity);
			          if(grand_total_amount != 0)
			          {
			          	if (grand_total_amount.indexOf(",") >= 0)
					  	grand_total_amount = grand_total_amount.replace(',', '');	
			          }
			          var grand_total_amount1 = parseInt(total_amount) + parseInt(grand_total_amount);
			          $("#grand_total_amount").html(addCommas(grand_total_amount1));
			          		 
				}
				else if(product_value == "Select Item" || product_value == "Select Item" || quantity == "" || discount_value == "Select Discount" || brand_value == "Select Brand" || brand_value == "Select Discount" || purchase_price == "" || total_amount == 0)
				{
					alert('Please add empty item values!');
					$("#item_counter").val(counter - 1);
					$("#total_items").html(counter - 1);
					return false;		
				}
				else
				{
					alert('Product already added!');
					$("#item_counter").val(counter - 1);
					$("#total_items").html(counter - 1);	
					return false;	
				}
				$("#product_id").find('.bfh-selectbox-option').html('');
				$("#product_id").find('.bfh-selectbox-option').html('Select Item');
				$("#product_id").find("input[name='product_id']").val('');
				$("#discount").find('.bfh-selectbox-option').html('');
				$("#discount").find('.bfh-selectbox-option').html('Select Discount');
				$("#discount").find("input[name='discount']").val('');
				$("#quantity").val('');
				$("#purchase_price").val('');
			});
			// Delete Items
			$(document).on('click', '.deleteItems', function (){
				var id = $(this).attr('id');
				var counter = $("#item_counter").val();
				var remove_quantity = $("#Quantity_"+id).val();
				var total_quantity = $("#total_quantity").html();
				$("#total_quantity").html(addCommas(total_quantity - remove_quantity));
				var remove_total_amount = $("#TotalAmount_"+id).val();
				var grand_total_amount = $("#grand_total_amount").html();
				if(grand_total_amount != 0)
		          {
		          	if (grand_total_amount.indexOf(",") >= 0)
				  	grand_total_amount = grand_total_amount.replace(',', '');	
		          }
		        if(remove_total_amount != 0)
		          {
		          	if (remove_total_amount.indexOf(",") >= 0)
				  	remove_total_amount = remove_total_amount.replace(',', '');	
		          }  
				$("#grand_total_amount").html(addCommas(grand_total_amount - remove_total_amount));
				$("#item_counter").val(counter - 1);
				$("#total_items").html(counter - 1);
				console.log(counter);
				if((counter - 1) == 0)
					$("#div_total_detail").addClass('hide');
				$("#Product_"+id).remove();
			});
		});
		function addCommas(nStr)
		{
		    nStr += '';
		    x = nStr.split('.');
		    x1 = x[0];
		    x2 = x.length > 1 ? '.' + x[1] : '';
		    var rgx = /(\d+)(\d{3})/;
		    while (rgx.test(x1)) {
		        x1 = x1.replace(rgx, '$1' + ',' + '$2');
		    }
		    return x1 + x2;
		}
	</script>
@endsection
