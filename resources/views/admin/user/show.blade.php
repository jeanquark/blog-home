@extends('layoutBack')

@section('content')
	<p>Page: admin.user.show</p>
	<ol class="breadcrumb">
        <li>
            <i class="fa fa-users"></i>  <a href="{{ route('admin.user.index') }}">Users</a>
        </li>
        <li class="active">
            <i class="fa fa-eye"></i> Show
        </li>
    </ol>

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    All the current Users
                </div><!-- /.panel-heading -->

                <div class="panel-body">
                    <div class="dataTable_wrapper">
                        <table class="table table-striped table-bordered table-hover" id="dataTable_users">
                            <thead>
                                <tr>
                                    <th valign="middle">ID</th>
                                    <th>Email</th>
                                    <th>First name</th>
                                    <th>Last name</th>
                                    <th>Role</th>
                                    <th>Created at</th>
                                    <th>Updated at</th>
                                </tr>
                            </thead>
                            <tbody>
	                                <tr>
	                                    <td>{{ $user->id }}</td>
	                                    <td>{{ $user->email }}</td>
	                                    <td>{{ $user->first_name }}</td>
	                                    <td>{{ $user->last_name }}</td>
                                        @if (!$user->roles()->get()->isEmpty())
                                            <td>{{ $user->roles()->first()->name }}</td>
                                        @else
                                            <td><i>- No status -</i></td>
                                        @endif
	                                    <td>{{ $user->created_at }}</td>
	                                    <td>{{ $user->updated_at }}</td>
	                                </tr>
                            </tbody>
                        </table>
                    </div><!-- /.table-responsive -->
                </div><!-- /.panel-body -->
            </div><!-- /.panel -->
        </div><!-- /.col-lg-12 -->
    </div><!-- /.row -->

@stop