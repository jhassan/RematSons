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
			<form role="form" id="purchase_form" method="POST" action="">
			<input type="hidden" name="_token" value="{{ csrf_token() }}" />
			<fieldset>
				<div class="panel-heading">
					<legend>Add Purchase Stock</legend>
				</div>	
				<div class="form-group col-sm-2">
					<div>
  						<label>Select Category</label>
						<div class="bfh-selectbox hide" data-name="category_id" id="category_id" data-value="" data-filter="true">
						  <div data-value="">Select Category</div>
						  @foreach($array_category as $category)
						  	<div data-value="{{ $category->id }}">{{ $category->category_name }}</div>
						  @endforeach
						</div>
  					</div>
				</div>
				<div class="form-group col-sm-2">
					<div>
  						<label>Select Party</label>
						<div class="bfh-selectbox hide" data-name="party_id" id="party_id" data-value="" data-filter="true">
						  <div data-value="">Select Party</div>
						  @foreach($arrayParties as $party)
						  	<div data-value="{{ $party->id }}">{{ $party->name }}</div>
						  @endforeach
						</div>
  					</div>
				</div>
				<div class="form-group col-sm-3">
					<label>Date</label>
					<div class="bfh-datepicker" data-name="purchase_date" data-format="d-m-y" data-date="today">
						<div class="input-group bfh-datepicker-toggle" data-toggle="bfh-datepicker">
							<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
							<input type="text" name="purchase_date" class="form-control" placeholder="" readonly="">
						</div>
					</div>
				</div>
				<div class="form-group col-sm-2">
					<label>Bilty #</label>
					<input class="form-control" placeholder="Bilty #" maxlength="15" name="bilty_no" type="text" value="">
				</div>
				<div class="form-group col-sm-3">
					<label>Cargo Address</label>
					<input class="form-control" placeholder="Cargo Address" maxlength="15" name="adda_address" type="text" value="">
				</div>
				<div class="panel-heading">
					<legend>Add All Purchased Items</legend>
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
					<input class="form-control number_only" placeholder="List Price" maxlength="15" name="list_price" id="list_price" type="text" value="">
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
				<div class="form-group col-sm-1">
					<img class="cursor" id="addItems" src="/images/add-icon.png" alt="Add" style="width:60px; margin-top:7px;">
				</div>
			</fieldset>
			<div class="table-responsive">
				<table class="table">
              <thead>
                <tr>
                  <th style="text-align:left;">Item Name</th>
                  <th>Quantity</th>
                  <th>List Price</th>
                  <th>Discount %</th>
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
                  <th style="width:193px;">Total Items:&nbsp;<span id="text_total_item"></span></th>
                  <th>Total Quantity:&nbsp;<span id="text_total_quantity"></span></th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th>Grand Total Amount:&nbsp;<span id="text_total_amount"></span></th>
                  <th></th>
                  <th></th>
                </tr>
              </thead>
            </table>
			<input type="hidden" name="total_quantity" id="grand_total_quantity" value="">
            <input type="hidden" name="grand_total" id="grand_total_amount" value="">
            <input type="hidden" name="total_item" id="grand_total_item" value="">
			</div>
			<div class="form-group col-sm-3">
		      <button type="button" id="submit_button" class="btn btn-primary">Save</button>
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
.table-responsive table th, td{text-align: right;}
#product_id .bfh-selectbox-options{width: 349px;}
</style>
@endsection

@section('custom_js')
	<script type="text/javascript">
		$(document).ready(function () {
			
			// shows drop down list when page loaded
			$("#product_id").removeClass('hide');
			$("#discount").removeClass('hide');
			$("#party_id").removeClass('hide');
			$("#category_id").removeClass('hide');
			
			// submit form
			$("#submit_button").click(function (){
				$("#purchase_form").submit();
			});

			$("#item_counter").val(0);
			// Add more items
			var counter = 0;
			$("#addItems").click(function () {
				var item_counter = $("#item_counter").val();
				counter = parseInt(item_counter) + parseInt(1);
				var inner_counter = $("#inner_counter").html();
				$("#item_counter").val(counter);
				$("#text_total_item").html(counter);
				$("#grand_total_item").val(counter);
				var product_value = $("#product_id").find('.bfh-selectbox-option').html();
				var product_id = $("#product_id").find("input[name='product_id']").val();
				var discount_percent = $("#discount").find("input[name='discount']").val();
				var quantity = $("#quantity").val();
				var list_price = $("#list_price").val();
				var discount_value = $("#discount").find('.bfh-selectbox-option').html();
				var discount_id = $("#discount").find("input[name='discount']").val();
				var AlreadyId = $("#Product_"+product_id+"").closest('tr').attr('id');
				if (quantity.indexOf(",") >= 0)
					quantity = quantity.replace(',', '');
				if (list_price.indexOf(",") >= 0)
					list_price = list_price.replace(',', '');
				if(discount_value == "Select Discount")
					discount_value = 0;
				if(discount_id == "")
					discount_id = 0;
				var net_price = (list_price - (list_price * 1/100 * discount_percent)).toFixed(2);
				var total_amount = quantity * net_price;
				var list_total_amount = (total_amount).toFixed(2);
				if(!AlreadyId && product_value != "Select Item" && quantity != "" && list_price != "" && total_amount != 0)
				{
					$("#div_total_detail").removeClass('hide');
					var str = "";
			          str = "<tr id='Product_"+product_id+"'>";
	                  str += "<td style=text-align:left;'>"+product_value+"<input type='hidden' name='product_id[]' value='"+product_id+"'></td>";
	                  str += "<td>"+addCommas(quantity)+"<input id='Quantity_"+product_id+"' type='hidden' name='quantity[]' value='"+quantity+"'></td>";
	                  str += "<td>"+addCommas(list_price)+"<input type='hidden' name='list_price[]' value='"+list_price+"'></td>";
	                  str += "<td>"+discount_value+"<input type='hidden' name='discount_id[]' value='"+discount_id+"'></td>";
	                  str += "<td>"+net_price+"<input id='NetPrice_"+product_id+"' type='hidden' name='net_price[]' value='"+net_price+"'></td>";
	                  str += "<td>"+addCommas(list_total_amount)+"<input id='TotalAmount_"+product_id+"' type='hidden' name='total_amount[]' value='"+addCommas(list_total_amount)+"'></td>";
	                  str += "<td class='text-center'><img class='cursor deleteItems' src='/images/delete.png' alt='Delete' id='"+product_id+"'></td>";
	                  str += "</tr>";
			          $('#addNewItems').append(str);

			          // Grand total quantity
			          var total_quantity = $("#text_total_quantity").html();
			          if(total_quantity == "")
			          	total_quantity = 0;
			          else
			          	total_quantity = $("#text_total_quantity").html();
			          if(total_quantity != 0)
			          {
			          	if (total_quantity.indexOf(",") >= 0)
					  	total_quantity = total_quantity.replace(',', '');	
			          }
			          var grand_total_quatity = parseInt(quantity) + parseInt(total_quantity);
			          $("#text_total_quantity").html(addCommas(grand_total_quatity));
			          $("#grand_total_quantity").val(addCommas(grand_total_quatity));
			          
			          // grand total amount
			          var grand_total_amount = $("#text_total_amount").html();
			          if(grand_total_amount == "")
			          	grand_total_amount = 0;
			          else
			          	grand_total_amount = $("#text_total_amount").html();
			          if(grand_total_amount != 0)
			          {
			          	if (grand_total_amount.indexOf(",") >= 0)
					  		grand_total_amount = grand_total_amount.replace(',', '');	
					  	if (grand_total_amount.indexOf(",") >= 0)
					  		grand_total_amount = grand_total_amount.replace(',', '');
			          }
			          list_total_amount = numberFormat(list_total_amount,2);
			          grand_total_amount = numberFormat(grand_total_amount,2);
			          var grand_total_amount1 = parseFloat(list_total_amount) + parseFloat(grand_total_amount);
			          grand_total_amount1 = numberFormat(grand_total_amount1,2);
			          $("#text_total_amount").html(addCommas(grand_total_amount1));
			          $("#grand_total_amount").val(addCommas(grand_total_amount1));
				}
				else if(product_value == "Select Item" || product_value == "Select Item" || quantity == "" || discount_value == "Select Discount" || list_price == "" || total_amount == 0)
				{
					alert('Please add empty item values!');
					$("#item_counter").val(counter - 1);
					$("#text_total_items").html(counter - 1);
					return false;		
				}
				else
				{
					alert('Product already added!');
					$("#item_counter").val(counter - 1);
					$("#text_total_items").html(counter - 1);
					emptyFieldss();	
					return false;	
				}
					emptyFieldss();
			});
			// Delete Items
			$(document).on('click', '.deleteItems', function (){
				var id = $(this).attr('id');
				//var counter = $("#item_counter").val();
				$("#item_counter").val( $("#item_counter").val() - 1);
				$("#text_total_item").html( $("#text_total_item").html() - 1 );
				$("#grand_total_item").val( $("#grand_total_item").val() - 1 );
				// For Quantity
				var remove_quantity = $("#Quantity_"+id).val();
				var total_quantity = $("#grand_total_quantity").val();
				if(total_quantity != 0)
		          {
		          	if (total_quantity.indexOf(",") >= 0)
				  	total_quantity = total_quantity.replace(',', '');	
		          }
				$("#text_total_quantity").html(addCommas(total_quantity - remove_quantity));
				$("#grand_total_quantity").val(addCommas(total_quantity - remove_quantity));
				// For Total Amount
				var remove_total_amount = $("#TotalAmount_"+id).val();
				var grand_total_amount = $("#grand_total_amount").val();
				
				if(grand_total_amount != 0)
		          {
		          	if (grand_total_amount.indexOf(",") >= 0)
				  		grand_total_amount = grand_total_amount.replace(',', '');
				  	if (grand_total_amount.indexOf(",") >= 0)
				  		grand_total_amount = grand_total_amount.replace(',', '');		
		          }
		        if(remove_total_amount != 0)
		          {
		          	if (remove_total_amount.indexOf(",") >= 0)
				  		remove_total_amount = remove_total_amount.replace(',', '');	
				  	if (remove_total_amount.indexOf(",") >= 0)
				  		remove_total_amount = remove_total_amount.replace(',', '');	
		          }  
		          var grand_total_amount1 = grand_total_amount - remove_total_amount;
		          grand_total_amount1 = numberFormat(grand_total_amount1,2);
				$("#grand_total_amount").val(addCommas(grand_total_amount1));
				$("#text_total_amount").html(addCommas(grand_total_amount1));
				// For Total Items
				//$("#grand_total_item").val(counter - 1);
				//$("#text_total_item").html(counter - 1);
				//console.log(counter);
				if(($("#item_counter").val()) == 0)
					$("#div_total_detail").addClass('hide');
				$("#Product_"+id).remove();
			});
		});
		function numberFormat(val, decimalPlaces) {

		    var multiplier = Math.pow(10, decimalPlaces);
		    return (Math.round(val * multiplier) / multiplier).toFixed(decimalPlaces);
		}
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
		function emptyFieldss() {
			$("#product_id").find('.bfh-selectbox-option').html('');
			$("#product_id").find('.bfh-selectbox-option').html('Select Item');
			$("#product_id").find("input[name='product_id']").val('');
			$("#discount").find('.bfh-selectbox-option').html('');
			$("#discount").find('.bfh-selectbox-option').html('Select Discount');
			$("#discount").find("input[name='discount']").val('');
			$(".bfh-selectbox-filter .form-control").val('');
			$("#quantity").val('');
			$("#list_price").val('');
			$("#product_id").attr('data-value','');
		}
	</script>
@endsection
