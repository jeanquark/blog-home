@extends('layoutBack')

@section('css')
    <style>
        .table th, td {
          overflow: hidden; /* this is what fixes the expansion */
          text-overflow: ellipsis; /* not supported in all browsers, but I accepted the tradeoff */
          white-space: nowrap;
        }
    </style>
@stop

@section('content')
	<p>Page: admin.tag.index </p>
	<ol class="breadcrumb">
        <li class="active">
            <i class="fa fa-tags"></i> Tags
        </li>
    </ol>
	<ul class="nav navbar-nav">
        <li><a href="{{ route('admin.tag.index') }}">View All Tags</a></li>
        <li><a href="{{ route('admin.tag.create') }}">Create a Tag</a>
    </ul>

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    All the current Tags
                </div><!-- /.panel-heading -->

                <div class="panel-body">
                    <div class="dataTable_wrapper">
                        <table class="table table-striped table-bordered table-hover" id="dataTable_tags">
                            <thead>
                                <tr>
                                    <th valign="middle">ID</th>
                                    <th>Name</th>
                                    <th>Slug</th>
                                    <th>Color</th>
                                    <th>Created at</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            	@foreach($tags as $tag)
	                                <tr>
	                                    <td>{{ $tag->id }}</td>
	                                    <td>{{ $tag->name }}</td>
	                                    <td>{{ $tag->slug }}</td>
                                        <td style="background-color: <?php echo $tag->color; ?>"></td>
	                                    <td>{{ $tag->created_at }}</td>
	                                    <td style="">
	                                    	<form method="POST" action="{{ URL::to('admin/tag/' . $tag->id) }}" accept-charset="UTF-8">
                                                <a class="btn btn-small btn-info" href="{{ URL::to('admin/tag/' . $tag->id . '/edit') }}" style="margin: 5px;">Edit this Tag</a>
                                                <input name="_method" type="hidden" value="DELETE">
                                                <input name="_token" value="{{ csrf_token() }}" type="hidden">
                                                <input class="btn btn-small btn-warning" onclick="return confirm('Are you sure you want to delete this picture ?')" value="Delete this tag" style="margin: 5px;" type="submit">
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
            $('#dataTable_tags').DataTable({
                //responsive: true,
                //scrollX: true,
                pageLength: 10,
                order: [[ 0, "desc" ]],
                lengthMenu: [ 2, 4, 10, 20, 50 ],
                columnDefs: [
                    { "orderable": false, "targets": 4,
                      "orderable": false, "targets": 3
                    }
                ]
            });
        });
    </script>
@stop