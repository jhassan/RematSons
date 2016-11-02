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
		@if (Session::has('message'))
		   <div class="alert alert-info">{{ Session::get('message') }}</div>
		@endif
		<div class="panel-body">
			<form role="form" method="POST" action="{{ url('/products/add_new') }}">
			<input type="hidden" name="_token" value="{!! csrf_token() !!}" />
			<fieldset>
				<div class="panel-heading">
					<legend>Add Product</legend>
				</div>	
				<div class="form-group col-sm-7">
					<label>Product Name</label>
					<input class="form-control" maxlength="100" placeholder="Name" name="name" type="text" value="">
				</div>
			</fieldset>
			<div class="form-group col-sm-3">
		      <button type="submit" class="btn btn-primary">Save</button>
		    </div>
		</form>
		</div>

	</div>
</div>
@endsection