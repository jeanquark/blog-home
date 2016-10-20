@extends('layoutBack')

@section('css')
    
@stop

@section('content')
	<p>Page: admin.post.create</p>
	<ol class="breadcrumb">
        <li>
            <i class="fa fa-file-text"></i>  <a href="{{ route('admin.post.index') }}">Posts</a>
        </li>
        <li class="active">
            <i class="fa fa-plus-square"></i> Create
        </li>
    </ol>

	<div class="row">
		<div class="col-md-8 col-md-offset-2">

			{!! Form::open(array('route' => 'admin.post.store', 'method' => 'POST', 'files' => true )) !!}

	            <h2 class="">Create a new Post</h2>
		 
	            <div class="form-group">
	             	{!! Form::label('title', 'Post Title') !!}    
                    {!! Form::text('title', Input::old('title'), array('class' => 'form-control', 'placeholder' => 'Title')) !!}
                </div>

	            <div class="form-group">
	            	{!! Form::label('slug', 'Post slug') !!}    
                    {!! Form::text('slug', Input::old('slug'), array('class' => 'form-control', 'placeholder' => 'Slug')) !!}
	            </div>

                <div class="form-group">
	                {!! Form::label('image', 'Upload Image') !!}
	                {!! Form::file('image') !!}
	            </div>

                <div class="form-group" style="">
                    {!! Form::label('tags', 'Link tags') !!}
                    {!! Form::select('tags', $tags, NULL, ['class' => 'form-control chosen-select', 'name' => 'tags[]', 'multiple tabindex' => 6]) !!}
                </div>

		        <div class="form-group">
		        	{!! Form::label('text', 'Text as Markdown') !!} Click <a href="https://blog.ghost.org/markdown/" target="_blank" >here</a> to get some tips on how to write Markdown.
		        	{!! Form::textarea('text', Input::old('text')) !!} 
		        </div>

	            {!! Form::submit('Create Post', array('class'=>'btn btn-primary')) !!}
	            
			{!! Form::close() !!}

	    </div><!-- /.col-md-12 -->
    </div><!-- /.row -->

@stop

@section('scripts')
	<script>
		var simplemde = new SimpleMDE({
		    element: document.getElementById("text"),
		    renderingConfig: {
		        singleLineBreaks: false,
		        codeSyntaxHighlighting: true,
		    }
		});
	</script>
@stop