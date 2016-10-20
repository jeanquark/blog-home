<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SB Admin - Bootstrap Admin Template</title>

    <!-- Bootstrap Core CSS -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="{{ asset('css/sb-admin.css') }}" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="{{ asset('font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css">

    <!-- Chosen -->
    <link href="{{ asset('chosen/bootstrap.min.css') }}" rel="stylesheet">

    <!-- DataTables CSS -->
    <link href="{{ asset('dataTables/css/dataTables.bootstrap.css') }}"rel="stylesheet">
    <!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.10/css/dataTables.bootstrap.min.css">-->
    <link href="http://cdn.datatables.net/responsive/1.0.2/css/dataTables.responsive.css" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    
    <link href="//cdn.jsdelivr.net/simplemde/latest/simplemde.min.css" rel="stylesheet">
    <script src="//cdn.jsdelivr.net/simplemde/latest/simplemde.min.js"></script>
    
    <style>
        body {
            height: 100%;
            background-color: #fff;
        }
        pre {
            border: none;
            background: #fff;
            padding: 0;
        }
        /*.hljs {
            padding: 1.5em;
        }*/
        pre code {
            border-radius: 20px;
            overflow: auto;
            word-wrap: normal;
            white-space: pre;
        }

        /* Panel */
        .panel-lightblue {
            border-color: #5bc0de;
        }

        .panel-lightblue > .panel-heading {
            border-color: #5bc0de;
            color: #fff;
            background-color: #5bc0de;
        }

        .panel-lightblue > a {
            color: #5bc0de;
        }

        .panel-lightblue > a:hover {
            color: #31b0d5;
        }

    </style>
    @yield('css')

</head>

<body>

    <div id="wrapper">

        <!-- Navigation -->
        <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand pull-left" href="{{ route('home') }}">Back to site</a>
            </div><!-- /.navbar-header -->

            <!-- Top Menu Items -->
            <ul class="nav navbar-right top-nav">
                <li class="dropdown">
                    @if (Sentinel::check())
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> {{ Sentinel::getUser()->email }} <b class="caret"></b></a>
                    @else
                        <a href="#">You are not logged in</a>
                    @endif
                    <ul class="dropdown-menu">

                        @if (Sentinel::check())
                            <li>
                                <a href="{{ route('logout') }}">Logout</a>
                            </li>
                        @endif

                    </ul>
                </li>
            </ul>
            <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
            <div class="collapse navbar-collapse navbar-ex1-collapse">
                <ul class="nav navbar-nav side-nav">
                    <li class="{{ active_class(if_route(['admin.index'])) }}">
                        <a href="{{ route('admin.index') }}"><i class="fa fa-fw fa-dashboard"></i> Dashboard</a>
                    </li>
                    <li class="{{ active_class(if_route(['admin.post.index', 'admin.post.create', 'admin.post.edit', 'admin.post.show'])) }}">
                        <a href="{{ route('admin.post.index') }}"><i class="fa fa-fw fa-file-text-o"></i> Manage Posts</a>
                    </li>
                    <li class="{{ active_class(if_route(['admin.tag.index', 'admin.tag.create', 'admin.tag.edit', 'admin.tag.show'])) }}">
                        <a href="{{ route('admin.tag.index') }}"><i class="fa fa-fw fa-tags"></i> Manage Tags</a>
                    </li>
                    <li class="{{ active_class(if_route(['admin.comment.index', 'admin.comment.create', 'admin.comment.edit', 'admin.comment.show', 'admin.reply.edit'])) }}">
                        <a href="{{ route('admin.comment.index') }}"><i class="fa fa-fw fa-comments"></i> Manage Comments</a>
                    </li>
                    @if (Sentinel::check() && Sentinel::inRole('admin'))
                        <li class="{{ active_class(if_route(['admin.user.index', 'admin.user.create', 'admin.user.edit', 'admin.user.show'] )) }}">
                            <a href="{{ route('admin.user.index') }}"><i class="fa fa-fw fa-users"></i> Manage Users</a>
                        </li>
                    @endif
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </nav>

        <div id="page-wrapper">

            <div class="container-fluid">
                
                <!-- Notifications -->
                @include('sentinel.notifications')

                <!-- /.content -->
                @yield('content')

            </div><!-- /.container-fluid -->

        </div><!-- /#page-wrapper -->

    </div><!-- /#wrapper -->

    <!-- jQuery -->
    <script src="{{ asset('js/jquery.js') }}"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>

    <!-- Select Chosen -->
    <script src="http://harvesthq.github.io/chosen/chosen.jquery.js"></script>
    <script>
        $(function() {
            $('.chosen-select').chosen({max_selected_options: 3});
            $('.chosen-select-deselect').chosen({ allow_single_deselect: true });
        });
    </script>

    <!-- DataTables JavaScript -->
    <script src="{{ asset('dataTables/js/jquery.dataTables.min.js') }}"></script>
    <!--<script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.10/js/jquery.dataTables.min.js"></script>-->
    <script src="//cdn.datatables.net/responsive/1.0.2/js/dataTables.responsive.js"></script>
    <script src="{{ asset('dataTables/js/dataTables.bootstrap.min.js') }}"></script>
    <!--<script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.10/js/dataTables.bootstrap.min.js"></script>-->

    @yield('scripts')

</body>

</html>