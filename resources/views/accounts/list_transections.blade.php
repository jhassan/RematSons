@extends('layouts.app')

@section('content')
<div class="col-md-9">
	<div class="content-box-large">
	<div class="panel-heading">
		<legend>View All Vouchers</legend>
	</div>	
	<div class="panel-body">
			<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered">
			<thead>
				<tr>
					<th>Date</th>
					<th>Voucher Type</th>
					<th>Party Name</th>
					<th>Bility #</th>
					<th>Category Name</th>
					<th>Quantity</th>
					<th>Total Amount</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
				@foreach($arrayList as $list)
					<tr class="odd gradeX" id="row_{{ $list->id }}">
						<td>{{ date("d-m-Y", strtotime($list->purchase_date)) }}</td>
						<td>{{ $list->voucher_type }}</td>
						<td>{{ $list->party_name }}</td>
						<td class="text-right">{{ $list->bilty_no }}</td>
						<td>{{ $list->category_name }}</td>
						<td class="text-right">{{ number_format($list->total_quantity) }}</td>
						<td class="text-right">{{ number_format($list->grand_total) }}</td>
						<td>Action</td>
					</tr>
				@endforeach
			</tbody>
			</table>
			{{ $arrayList->render() }}
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
	
@endsection