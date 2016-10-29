@extends('layouts.app')

@section('content')
<div class="col-md-9">
	<div class="content-box-large">
	<div class="panel-heading">
		<legend>View Sale Stock</legend>
	</div>	
	<div class="panel-body">
			<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="example">
			<thead>
				<tr>
					<th>Sale Date</th>
					<th>Party Name</th>
					<th>Bility #</th>
					<th>Item Name</th>
					<th>Quantity</th>
					<th>Brand Name</th>
					<th>Sale Price</th>
					<th>Total Amount</th>
				</tr>
			</thead>
			<tbody>
				@foreach($all_stcok_sale as $stock)
					<tr class="odd gradeX" id="row_{{ $stock->id }}">
						<td>{{ date("d-m-Y", strtotime($stock->sale_date)) }}</td>
						<td>{{ $stock->party_name }}</td>
						<td class="text-right">{{ $stock->bilty_no }}</td>
						<td>{{ $stock->product_name }}</td>
						<td class="text-right">{{ number_format($stock->quantity) }}</td>
						<td class="text-right">{{ number_format($stock->list_price) }}</td>
						<td class="text-right">{{ number_format($stock->net_price) }}</td>
						<td class="text-right">{{ number_format($stock->total_amount) }}</td>
					</tr>
				@endforeach
				<tr>
					<th>Total:</th>
					<th></th>
					<th></th>
					<th></th>
					<th class="text-right">{{number_format($total_quantity)}}</th>
					<th class="text-right"></th>
					<th class="text-right"></th>
					<th class="text-right">{{number_format($total_amount)}}</th>
				</tr>
			</tbody>
			</table>
			{{ $all_stcok_sale->render() }}
			<input type="hidden" value="<?php echo csrf_token(); ?>" name="_token">
		</div>	
	</div>
</div>
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