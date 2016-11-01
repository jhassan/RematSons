@extends('layouts.app')

@section('content')
<div class="col-md-9">
	<div class="content-box-large">
		<div class="panel-heading">
			<legend>Show All Items List Price</legend>
		</div>
		@if (Session::has('message_update'))
		   <div class="alert alert-info">{{ Session::get('message_update') }}</div>
		@endif
		<div class="panel-body">
			<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered">
			<thead>
				<tr>
					<th>Item Name</th>
					<th>List Price</th>
					<th>Update List Price</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
			@foreach($arrayList as $list)
				<tr class="odd gradeX" id="row_{{ $list->product_id }}">
					<td>{{ $list->product_name }}</td>
					<td id="current_list_price_{{ $list->product_id }}">{{ number_format($list->list_price) }}</td>
					<td><input type="text" class="number_only" id="update_list_price_{{ $list->product_id }}" value=""></td>
					<td class="center"><a href="#" id="{{ $list->product_id }}" class="UpdateListPrice" style="cursor:pointer;"><img src="/images/edit.png" alt="Edit"></a></td>
				</tr>
			@endforeach
			</tbody>
			</table>
			{{ $arrayList->render() }}
			<input type="hidden" value="<?php echo csrf_token(); ?>" name="_token">
		</div>
	</div>
</div>
<div id="dialog-confirm-delete" title="Delete Reocrs" style="display:none;">Do you want to update this record?</div>
@endsection

@section('custom_js')

	<script src="/js/jquery.ui.dialog.js"></script>
	<script src="/vendors/datatables/js/jquery.dataTables.min.js"></script>
    <script src="/vendors/datatables/dataTables.bootstrap.js"></script>
	
	<script type="text/javascript">
	$(document).ready(function() {
	    $('#example').dataTable();
	    $(document).on('click','.UpdateListPrice',function(e){
				var ID = jQuery(this).attr("id");
				var list_price = $("#update_list_price_"+ID).val();
				if(list_price == "")
				{
					alert("Please update list price!");
					return false;
				}
				else
				{
					var token = $('input[name="_token"]').val();
					$("#dialog-confirm-delete").dialog({
					resizable: false,
					height:170,
					width: 400,
					modal: true,
					title: 'Update List Price',
					buttons: {
						Update: function() {
							$(this).dialog('close');
							$.ajax({
				          type: "GET",
				      	  url: '/list_price/update_list_price',
				          data: { ID: ID, list_price: list_price }
				      }).done(function( response ) {
				      	  $("#current_list_price_"+ID).html(response);	
				      	  $("#update_list_price_"+ID).val('');
				      });
						},
						Cancel: function() {
						   $(this).dialog('close');
						}
					}
				});
						   
			return false;
				}
				
		});
	} );
	</script>
@endsection
