@extends('layoutBack')

@section('css')

@stop

@section('content')
	<p>Page: admin.post.index</p>
	<ol class="breadcrumb">
        <li class="active">
            <i class="fa fa-file-text"></i> Posts
        </li>
    </ol>
	<ul class="nav navbar-nav">
        <li><a href="{{ route('admin.post.index') }}">View All Posts</a></li>
        <li><a href="{{ route('admin.post.create') }}">Create a Post</a>
    </ul>

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    All the current Posts
                </div><!-- /.panel-heading -->

                <div class="panel-body">
                    <div class="dataTable_wrapper">
                        <table class="table table-striped table-bordered table-hover" id="dataTable_posts">
                            <thead>
                                <tr>
                                    <th valign="middle">ID</th>
                                    <th>Title</th>
                                    <th>Slug</th>
                                    <th>Content html</th>
                                    <th>Image</th>
                                    <th>Published</th>
                                    <th>Created at</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            	@foreach($posts as $post)
	                                <tr>
	                                    <td>{{ $post->id }}</td>
	                                    <td>{{ $post->title }}</td>
	                                    <td>{{ $post->slug }}</td>
	                                    <td>{{ substr($post->content_html, 0, 50) }} [...]</td>
                                        @if ($post->image_path)
	                                       <td><img src="{{ asset($post->image_path) }}" height="40" width="120" /></td>
                                        @else
                                            <td>No image</td>
                                        @endif
                                        @if ($post->is_published)
                                            <td class="success text-center">Yes</td>
                                        @else   
                                            <td class="danger text-center">No</td>
                                        @endif
	                                    <td>{{ $post->created_at }}</td>
	                                    <td style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                            {!! Form::open(array('route' => array('admin.post.destroy', $post->id), 'method' => 'POST' )) !!}
                                                <a class="btn btn-small btn-success" href="{{ URL::to('admin/post/' . $post->id) }}" style="margin: 5px;">Show this Post</a>
                                                <a class="btn btn-small btn-info" href="{{ URL::to('admin/post/' . $post->id . '/edit') }}" style="margin: 5px;">Edit this Post</a>
                                                <input name="_method" type="hidden" value="DELETE">
                                                <input name="_token" value="{{ csrf_token() }}" type="hidden">
                                                <input class="btn btn-small btn-warning" onclick="return confirm('Are you sure you want to delete this picture ?')" value="Delete this post" style="margin: 5px;" type="submit">
                                            {!! Form::close() !!}
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
            $('#dataTable_posts').DataTable({
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