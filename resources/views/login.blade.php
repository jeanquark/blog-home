@extends('layoutFront')

@section('title', 'Login')

@section('content')
    <!-- Page Content -->
    <div class="col-lg-8">
        <!-- Notifications -->
        @include('sentinel.notifications')
        <!-- Content -->
        <div class="row">
			<div class="col-md-6 col-md-offset-3">
				<br/>
				{!! Form::open(array('route' => 'session.store', 'method' => 'POST', 'class' => 'form-horizontal' )) !!}
					<div class="panel panel-primary">
	                    <div class="panel-heading">
	                        <h2 class="panel-title">Sign In</h2>
	                    </div>

	        			<div class="panel-body" style="padding-top: 30px">
							<input type="hidden" name="_token" value="{{ csrf_token() }}">
							
	                        <div class="input-group">
	                        	<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>   
		                    	{!! Form::text('email', Input::old('email'), array('class' => 'form-control', 'placeholder' => 'Email')) !!}
		                    </div>
		                    <br/>

	                        <div class="input-group">
	                       		<span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
	                       		{!! Form::password('password', array('class' => 'form-control', 'placeholder' => 'Password')) !!}
	                        </div>
	                        <br/>

							<div class="input-group">
								<div class="checkbox">
									<label>
										{!! Form::checkbox('remember', Null, false, ['id' => 'remember']) !!} Remember Me
									</label>
								</div>
							</div>
							<br/>

	                        {!! Form::submit('Sign In', array('class'=>'btn btn-success')) !!}
							<br/>
							<br/>
							<p>To sign in as administrator, please enter :
								@if (!empty($user))
									<ul>
										<li>Email: <b><pre>{{ $user->email }}</pre></b></li>
										<li>Password: <b><pre>secret</pre></b></li>
									</ul>
								@else 
									<p class="alert alert-danger text-center">No registered administrator.</p>
								@endif	
							</p>
						</div><!-- /.panel-body -->
					</div><!-- /.panel panel-info -->
				{!! Form::close() !!}
				<!--</form>-->
			</div><!-- /.col-md-6 col-md-offset-3 -->
		</div><!-- /.row -->
    </div><!-- /.col-lg-8 -->
@stop