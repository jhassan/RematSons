@extends('layouts.app')

@section('content')
<div class="col-md-9">
	<div class="content-box-large">
	<div class="panel-heading">
		<legend>View Today Stock</legend>
	</div>	
	<div class="panel-body">
			<table cellpadding="0" cellspacing="0" border="0" class="table table-responsive table-bordered" id="example">
			<thead>
				<tr>
					<th>Item Name</th>
					<th>Stock In Quantity</th>
				</tr>
			</thead>
			<tbody>
				@foreach($array_today_stock as $stock)
					<tr class="odd gradeX">
						<td>{{ $stock->product_name }}</td>
						<td>{{ $stock->TotalStock }}</td>
					</tr>
				@endforeach
			</tbody>
			</table>
			{{ $array_today_stock->render() }}
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