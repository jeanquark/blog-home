@extends('layoutBack')

@section('content')
	<p>Page: admin.user.index</p>
	<ol class="breadcrumb">
        <li class="active">
            <i class="fa fa-users"></i> Users
        </li>
    </ol>
	<ul class="nav navbar-nav">
        <li><a href="{{ route('admin.user.index') }}">View All Users</a></li>
        <li><a href="{{ route('admin.user.create') }}">Create a User</a>
    </ul>

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
                                    <th>Active</th>
                                    <th>Created at</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            	@foreach($users as $user)
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
                                        @if (Activation::completed(Sentinel::findById($user->id)))
                                            <td>Yes</td>
                                        @else
                                            <td>No</td>
                                        @endif
	                                    <td>{{ $user->created_at }}</td>
	                                    <td style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
	                                    	<form method="POST" action="{{ URL::to('admin/user/' . $user->id) }}" accept-charset="UTF-8">
                                                <a class="btn btn-small btn-success" href="{{ URL::to('admin/user/' . $user->id) }}" style="margin: 5px;">Show this user</a>
                                                <a class="btn btn-small btn-info" href="{{ URL::to('admin/user/' . $user->id . '/edit') }}" style="margin: 5px;">Edit this User</a>
                                                <input name="_method" type="hidden" value="DELETE">
                                                <input name="_token" value="{{ csrf_token() }}" type="hidden">
                                                <input class="btn btn-small btn-warning" onclick="return confirm('Are you sure you want to delete this picture ?')" value="Delete this user" style="margin: 5px;" type="submit">
                                            </form>
                                        </td>
	                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div><!-- /.table-responsive -->
                </div><!-- /.panel-body -->
            </div><!-- /.panel -->
        </div><!-- /.col-lg-12 -->
    </div><!-- /.row -->
@stop

@section('scripts')
    <script>
        // dataTable
        $(document).ready(function() {
            $('#dataTable_users').DataTable({
                //responsive: true,
                //scrollX: true,
                pageLength: 10,
                order: [[ 0, "desc" ]],
                lengthMenu: [ 2, 4, 10, 20, 50 ],
                columnDefs: [
                    { "orderable": false, "targets": 7 }
                ]
            });
        });
    </script>
@stop