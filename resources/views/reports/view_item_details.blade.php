@extends('layouts.app')

@section('content')
<div class="col-md-9">
	<div class="content-box-large">
	<div class="panel-heading">
		<legend>View Item Stock of ( {{ $ProductName }})</legend>
	</div>	
	<div class="panel-body">
			<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered">
			<thead>
				<tr>
					<th width="25%">Date</th>
					<th width="25%">Party Name</th>
					<th width="25%">Purchase</th>
					<th width="25%">Sale</th>
				</tr>
			</thead>
			<tbody>
				@foreach($all_stcok_data as $stock)
					<tr class="odd gradeX">
						<td>{{ date("d-m-Y", strtotime($stock->sale_purchase_date))}}</td>
						<td>{{ $stock->name }}</td>
						<td>{{ number_format($stock->product_credit) }}</td>
						<td>{{ number_format($stock->product_debit) }}</td>
					</tr>
				@endforeach
			</tbody>
			</table>
			<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered">
			<thead>
				<tr>
					<th width="25%">Total In Stock : {{number_format($total_quantity)}}</th>
					<th width="25%"></th>
					<th width="25%">Purchase : {{number_format($credit)}}</th>
					<th width="25%">Sale : {{number_format($debit)}}</th>
				</tr>
			</tbody>
			</table>

			{{ $all_stcok_data->render() }}
			<input type="hidden" value="<?php echo csrf_token(); ?>" name="_token">
		</div>	
	</div>
</div>
<style type="text/css">
table {margin-bottom: 0px !important;}
</style>
@endsection

@section('custom_js')

	<script src="/vendors/datatables/js/jquery.dataTables.min.js"></script>
    <script src="/vendors/datatables/dataTables.bootstrap.js"></script>
	
	<script type="text/javascript">
	$(document).ready(function() {
	    $('#example').dataTable();
	} );
	</script>
@endsection