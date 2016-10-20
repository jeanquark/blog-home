@extends('layoutBack')

@section('content')
	<p>Page: admin.user.edit</p>

	<ol class="breadcrumb">
        <li>
            <i class="fa fa-users"></i>  <a href="{{ route('admin.user.index') }}">Users</a>
        </li>
        <li class="active">
            <i class="fa fa-pencil"></i> Edit
        </li>
    </ol>

    <div class="row">
		<div class="col-md-6 col-md-offset-3">
			
			<h2 class="">Edit {{ $user->email }}</h2>

			{!! Form::model($user, ['route' => ['admin.user.update', $user->id], 'method' => 'PATCH']) !!}
				
                <div class="form-group {{ ($errors->has('first_name')) ? 'has-error' : '' }}">
                    {!! Form::label('first_name', 'First name') !!}
                    {!! Form::text('first_name', null, array('class' => 'form-control')) !!}
                </div>
                <div class="form-group {{ ($errors->has('last_name')) ? 'has-error' : '' }}">
                    {!! Form::label('last_name', 'Last name') !!}
                    {!! Form::text('last_name', null, array('class' => 'form-control')) !!}
                </div>

	            {!! Form::submit('Edit User', array('class' => 'btn btn-primary')) !!}
	            <a href="{{ route('admin.user.index') }}" class="btn btn-default">Cancel</a>

	        {!!Form::close()!!}
            
            <hr>
            <h4>Update activation status</h4>
			@if (Activation::completed(Sentinel::findById($user->id)))
				<div class="well">
					{!! Form::model($user, ['route' => ['admin.user.activation', $user->id], 'method' => 'POST', 'class' => 'form-inline']) !!}
						<b>Account is active</b> 
		                @if (!$user->roles()->get()->isEmpty() && $user->roles()->first()->slug == 'admin') 
							{!! Form::submit('Desactivate User', array('class' => 'btn btn-primary', 'style' => 'margin-left: 20px;', 'onclick' => 'return confirm("Are you sure you want to sesactivate this user with admin priviledges?")')) !!}
		                @else
		                	{!! Form::submit('Desactivate Account', array('class' => 'btn btn-primary', 'style' => 'margin-left: 20px;')) !!}
		                @endif
		                
		            {!! Form::close() !!}
				</div>
			@else
				<div class="well">
					{!! Form::model($user, ['route' => ['admin.user.activation', $user->id], 'method' => 'POST', 'class' => 'form-inline']) !!}
						<b>Account is not active</b>
						{!! Form::submit('Activate Account', array('class' => 'btn btn-primary', 'style' => 'margin-left: 20px;' )) !!}
		            {!! Form::close() !!}
				</div>
			@endif

			@if (!($user->roles()->get()->isEmpty()))
		        <h4>Change Role</h4>
		        <div class="well">

		            {!! Form::model($user, ['route' => ['admin.user.role', $user->id], 'method' => 'POST', 'class' => 'form-inline']) !!}
		                <div class="form-group">
		                    @foreach($roles as $role)
		                		@if ($role->id == $user->roles->first()->id)
		                			<label class="radio-inline">
		           						{!! Form::radio('role',  $role->id, 'checked') !!} {{ $role->name }}
		           					</label>
								@else
									<label class="radio-inline">
										{!! Form::radio('role', $role->id, null) !!} {{ $role->name }}
									</label>
								@endif
		                    @endforeach
		                </div><!-- /.form-group -->
		                {!! Form::submit('Update Role', array('class' => 'btn btn-primary', 'style' => 'margin-left: 20px;')) !!}
		            {!! Form::close() !!}
		        </div><!-- /.well -->
		    @else 	 
				<h4>Apply Role</h4>
		        <div class="well">
		            {!! Form::model($user, ['route' => ['admin.user.role', $user->id], 'method' => 'POST', 'class' => 'form-inline']) !!}
		                <div class="form-group">
		                    @foreach($roles as $role)
								<label class="radio-inline">
									{!! Form::radio('role', $role->id, null) !!} {{ $role->name }}
								</label>
		                    @endforeach
		                </div><!-- /.form-group -->
		                {!! Form::submit('Apply Role', array('class'=>'btn btn-primary', 'style' => 'margin-left: 20px;')) !!}
		            {!! Form::close() !!}
		        </div><!-- /.well -->
		    @endif

	        @if ($user->id == Sentinel::check()->id)
		        <h4>Change Password</h4>
		        <div class="well">

		            {!! Form::model($user, ['route' => ['admin.user.password', $user->id], 'method' => 'POST']) !!}
		                <div class="form-group">
		                    {!! Form::label('old_password', 'Old Password') !!}
	                    	{!! Form::password('old_password', array('class' => 'form-control', 'placeholder' => 'Old Password')) !!}
		                </div>
		                <div class="form-group">
		                    {!! Form::label('new_password', 'New Password') !!}
	                    	{!! Form::password('new_password', array('class' => 'form-control', 'placeholder' => 'New Password')) !!}
		                </div>
		                <div class="form-group">
		                    {!! Form::label('new_password_confirmation', 'Confirm New Password') !!}
	                    	{!! Form::password('new_password_confirmation', array('class' => 'form-control', 'placeholder' => 'Confirm New Password')) !!}
		                </div>
		                
		                {!! Form::submit('Change Password', array('class'=>'btn btn-primary')) !!}

		            {!! Form::close() !!}
		        </div><!-- ./well -->
		    </div><!-- /.row -->
		@endif
	</div><!-- /.col-md-6 -->
@stop