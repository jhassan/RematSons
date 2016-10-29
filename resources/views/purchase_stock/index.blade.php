@extends('layouts.app')

@section('content')
<div class="col-md-9">
	<div class="content-box-large">
		<div class="panel-heading">
			<legend>View Purchase Stock</legend>
		</div>
		@if (Session::has('purchase_stock_edit'))
		   <div class="alert alert-info">{{ Session::get('purchase_stock_edit') }}</div>
		@endif
		<div class="panel-body">
			<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="example">
			<thead>
				<tr>
					<th>Purchase Date</th>
					<th>Party Name</th>
					<th>Bilty #</th>
					<th class="text-right">Total Items Qty</th>
					<th class="text-right">Total Amount</th>
					<th>User Name</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
				@foreach($arrayStock as $stock)
					<tr class="odd gradeX" id="row_{{ $stock->id }}">
						<td>{{ date("d-m-Y", strtotime($stock->purchase_date)) }}</td>
						<td>{{ $stock->party_name }}</td>
						<td>{{ $stock->bilty_no }}</td>
						<td class="text-right">{{ number_format($stock->total_quantity) }}</td>
						<td class="text-right">{{ number_format($stock->grand_total,2) }}</td>
						<td>{{ $stock->user_name }}</td>
						<td class="center"><a href="/purchase_stock/{{ $stock->id }}/edit"><img src="/images/edit.png" alt="Edit"></a>&nbsp; &nbsp;<a id="{{ $stock->id }}" class="deleteRecord"><img src="/images/delete.png" alt="Delete" style="cursor:pointer;"></a></td>
					</tr>
				@endforeach
			</tbody>
			</table>
			<input type="hidden" value="<?php echo csrf_token(); ?>" name="_token">
		</div>
	</div>
</div>
<div id="dialog-confirm-delete" title="Delete Reocrs" style="display:none;">Do you want to delete this record?</div>
@endsection

@section('custom_js')

	<script src="/js/jquery.ui.dialog.js"></script>
	<script src="/vendors/datatables/js/jquery.dataTables.min.js"></script>
    <script src="/vendors/datatables/dataTables.bootstrap.js"></script>
	
	<script type="text/javascript">
	$(document).ready(function() {
	    $('#example').dataTable();
	    $(document).on('click','.deleteRecord',function(e){
				var DelID = jQuery(this).attr("id");
				var token = $('input[name="_token"]').val();
				$("#dialog-confirm-delete").dialog({
								resizable: false,
								height:170,
								width: 400,
								modal: true,
								title: 'Delete Purchase Stock',
								buttons: {
									Delete: function() {
										$(this).dialog('close');
										$.ajax({
                          type: "GET",
                      				url: '/purchase_stock/delete_purchase_stock',
                          data: { DelID: DelID }
                      }).done(function( msg ) {
                          //alert( msg+'ttttt' );
                          if(msg == "delete")
                            $("#row_"+DelID).remove();
                      });
									},
									Cancel: function() {
									   $(this).dialog('close');
									}
								}
							});
									   
						return false;
						});
	} );
	</script>
@endsection
