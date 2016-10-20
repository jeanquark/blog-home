@extends('layoutBack')

@section('content')
	<p>Page: admin.comment.create </p>
	<ol class="breadcrumb">
        <li>
            <i class="fa fa-flag"></i>  <a href="{{ route('admin.comment.index') }}">Comments</a>
        </li>
        <li class="active">
            <i class="fa fa-list-ol"></i> Create
        </li>
    </ol>
@stop