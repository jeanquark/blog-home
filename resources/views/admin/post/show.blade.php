@extends('layoutBack')

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/9.1.0/styles/monokai-sublime.min.css">
    <script src="https://cdn.jsdelivr.net/highlight.js/latest/highlight.min.js"></script>
    <script>hljs.initHighlightingOnLoad();</script>
    <script type="text/javascript" src="https://cdn.mathjax.org/mathjax/latest/MathJax.js?config=TeX-AMS_HTML"></script>  

    <style>
        /* update monokai-sublime.min.css for code display */
        .hljs {
                padding: 1em;
            }
    </style>
@stop

@section('content')
    <?php 
        echo Sentinel::getUser()->id;
    ?>
	<p>Page: admin.post.show</p>
	<ol class="breadcrumb">
        <li>
            <i class="fa fa-file-text"></i>  <a href="{{ route('admin.post.index') }}">Posts</a>
        </li>
        <li class="active">
            <i class="fa fa-eye"></i> Show
        </li>
    </ol>
   
    <!-- Blog post retrieved from DB -->
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <h2>
                {{ $post->title }}
            </h2>
            <p class="lead">
                by {{ $post->user->first_name }}&nbsp;{{ $post->user->last_name }}
                @foreach ($post->tags as $tag)
        			<span class="badge" style="background-color: {{ $tag->color }}; vertical-align: 25%;">{{ $tag->name }}</span>
        		@endforeach
            </p>
            <p><span class="glyphicon glyphicon-time"></span> Created on {{ $post->created_at->format('F d, Y \a\t g:i A') }}</p>
            <hr>
            @if (!empty($post->image_path))
                <img class="img-responsive" src="{{ asset($post->image_path) }}" alt="">
                <hr>
            @endif
            <?php echo $post->content_html; ?>
        </div><!-- /. col-md-6 -->
    </div><!-- /. row -->
@stop