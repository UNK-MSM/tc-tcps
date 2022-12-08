
<!DOCTYPE html>
<!-- 
Template Name: Metronic - Responsive Admin Dashboard Template build with Twitter Bootstrap 3.3.6
Version: 4.5.4
Author: KeenThemes
Website: http://www.keenthemes.com/
Contact: support@keenthemes.com
Follow: www.twitter.com/keenthemes
Like: www.facebook.com/keenthemes
Purchase: http://themeforest.net/item/metronic-responsive-admin-dashboard-template/4021469?ref=keenthemes
License: You must have a valid license purchased only from themeforest(the above link) in order to legally use the theme for your project.
-->
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
    <!--<![endif]-->
    <!-- BEGIN HEAD -->

    <head>
        <meta charset="utf-8" />
        <title>Trend Cast | {{$title}}</title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta content="" name="description" />
        <meta content="" name="author" />
        <!-- BEGIN GLOBAL MANDATORY STYLES -->
        <link href="http://fonts.googleapis.com/css?family=Droid+Arabic+Kufi" rel="stylesheet" type="text/css" />
        <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
        <link href="{{asset('assets/global/plugins/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('assets/global/plugins/simple-line-icons/simple-line-icons.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('assets/global/plugins/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('assets/global/plugins/uniform/css/uniform.default.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css')}}" rel="stylesheet" type="text/css" />
        <!-- END GLOBAL MANDATORY STYLES -->
        <!-- BEGIN PAGE LEVEL PLUGINS -->
        <link href="{{asset('assets/global/plugins/bootstrap-toastr/toastr.min.css')}}" rel="stylesheet" type="text/css" />
        @yield('page_level_plugins_styles')
        <!-- END PAGE LEVEL PLUGINS -->
        <!-- BEGIN THEME GLOBAL STYLES -->
        <link href="{{asset('assets/global/css/components-rounded.min.css')}}" rel="stylesheet" id="style_components" type="text/css" />
        <link href="{{asset('assets/global/css/plugins.min.css')}}" rel="stylesheet" type="text/css" />
        <!-- END THEME GLOBAL STYLES -->
        <!-- BEGIN PAGE LEVEL STYLES -->
        @yield('page_level_styles')
        <style type="text/css">
            .tooltip{
                z-index: 10052;
            }
        </style>
        <!-- END PAGE LEVEL STYLES -->
        <!-- BEGIN THEME LAYOUT STYLES -->
        <link href="{{asset('assets/layouts/layout3/css/layout.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('assets/layouts/layout3/css/themes/default.min.css')}}" rel="stylesheet" type="text/css" id="style_color" />
        <link href="{{asset('assets/layouts/layout3/css/custom.min.css')}}" rel="stylesheet" type="text/css" />

        <link href="{{asset('assets/global/css/app_custom.css?v=3')}}" rel="stylesheet" type="text/css" />
        <!-- END THEME LAYOUT STYLES -->
        <link rel="shortcut icon" href="favicon.ico" /> </head>
    <!-- END HEAD -->

    <body class="page-container-bg-solid page-boxed">
        <!-- BEGIN HEADER -->
        <div class="page-header">
            <!-- BEGIN HEADER MENU -->
            <div class="page-header-menu">
                <div class="container">
                    <!-- BEGIN LOGO -->
                    <div class="page-logo pull-left">
                        <a href="#">
                            <img src="{{asset('assets/layouts/layout3/img/logo.png')}}" alt="logo" class="logo-default">
                        </a>
                    </div>
                    <!-- END LOGO -->
                    <!-- BEGIN MEGA MENU -->
                    <!-- DOC: Apply "hor-menu-light" class after the "hor-menu" class below to have a horizontal menu with white background -->
                    <!-- DOC: Remove data-hover="dropdown" and data-close-others="true" attributes below to disable the dropdown opening on mouse hover -->
                    <div class="hor-menu  ">
                        <ul class="nav navbar-nav">
                        @if(Auth::user()->user_type->serial_no===1)
                            <li class="nav-item">
                                <a href="{{route('user.index')}}"><i class="icon-users"></i> {{trans('sidebar.user')}}
                                    <span class="arrow"></span>
                                </a>
                            </li>
                            <li class="menu-dropdown classic-menu-dropdown">
                                <a href="javascript:;"><i class="icon-graph"></i> {{trans('sidebar.stock_management')}}
                                    <span class="arrow"></span>
                                </a>
                                <ul class="dropdown-menu pull-left">
                                    <li class=" ">
                                        <a href="{{route('stock.index')}}" class="nav-link">
                                            <i class="icon-graph"></i> {{trans('sidebar.stock')}}
                                        </a>
                                    </li>
                                    <li class=" ">
                                        <a href="{{route('stock_market.index')}}" class="nav-link  ">
                                            <i class="icon-bar-chart"></i> {{trans('sidebar.stock_market')}} </a>
                                    </li>
                                    <li class=" ">
                                        <a href="{{route('stock_market_vacation.index')}}" class="nav-link  ">
                                            <i class="icon-calendar"></i> {{trans('sidebar.stock_market_vacation')}} </a>
                                    </li>
                                    <li class=" ">
                                        <a href="{{route('stock_closing_reading.index')}}" class="nav-link  ">
                                            <i class="icon-bar-chart"></i> {{trans('sidebar.stock_closing_reading')}} </a>
                                    </li>
                                    <li class=" ">
                                        <a href="{{route('urgent_cause.index')}}" class="nav-link  ">
                                            <i class="icon-shield"></i> {{trans('sidebar.urgent_cause')}} </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="menu-dropdown classic-menu-dropdown">
                                <a href="javascript:;"><i class="icon-credit-card"></i> {{trans('sidebar.membership')}}
                                    <span class="arrow"></span>
                                </a>
                                <ul class="dropdown-menu pull-left" style="width: 225px;">
                                    <li class=" ">
                                        <a href="{{route('point.index')}}" class="nav-link">
                                            <i class="fa fa-diamond"></i> {{trans('sidebar.point')}}
                                        </a>
                                    </li>
                                    <li class=" ">
                                        <a href="{{route('discount_code.index')}}" class="nav-link">
                                            <i class="fa fa-tags"></i> {{trans('sidebar.discount_code')}}
                                        </a>
                                    </li>
                                    <li class=" ">
                                        <a href="{{route('discount_code_usage.index')}}" class="nav-link">
                                            <i class="fa fa-tags"></i> {{trans('sidebar.discount_code_usage')}}
                                        </a>
                                    </li>
                                    <li class=" ">
                                        <a href="{{route('membership.index')}}" class="nav-link">
                                            <i class="icon-credit-card"></i> {{trans('sidebar.membership')}}
                                        </a>
                                    </li>
                                    <li class=" ">
                                        <a href="{{route('membership_plan.index')}}" class="nav-link  ">
                                            <i class="icon-layers"></i> {{trans('sidebar.membership_plan')}} </a>
                                    </li>
                                    <li class=" ">
                                        <a href="{{route('membership_plan_instance.index')}}" class="nav-link  ">
                                            <i class="icon-equalizer"></i> {{trans('sidebar.membership_plan_instance')}} </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="menu-dropdown classic-menu-dropdown">
                                <a href="javascript:;"><i class="icon-settings"></i> {{trans('sidebar.system_configuration')}}
                                    <span class="arrow"></span>
                                </a>
                                <ul class="dropdown-menu pull-left">
                                    <li class="">
                                        <a href="{{route('user_status.index')}}" class="nav-link">
                                            <i class="fa fa-gears"></i> {{trans('sidebar.user_status')}}
                                        </a>
                                    </li>
                                    <li class=" ">
                                        <a href="{{route('user_type.index')}}" class="nav-link  ">
                                            <i class="fa fa-gears"></i> {{trans('sidebar.user_type')}} </a>
                                    </li>
                                    <li class=" ">
                                        <a href="{{route('currency.index')}}" class="nav-link  ">
                                            <i class="fa fa-dollar"></i> {{trans('sidebar.currency')}} </a>
                                    </li>
                                    <li class=" ">
                                        <a href="{{route('stock_type.index')}}" class="nav-link  ">
                                            <i class="fa fa-gears"></i> {{trans('sidebar.stock_type')}} </a>
                                    </li>
                                    <li class=" ">
                                        <a href="{{route('stock_entry_source.index')}}" class="nav-link  ">
                                            <i class="fa fa-gears"></i> {{trans('sidebar.stock_entry_source')}} </a>
                                    </li>
                                    <li class=" ">
                                        <a href="{{route('prediction_status.index')}}" class="nav-link  ">
                                            <i class="fa fa-gears"></i> {{trans('sidebar.prediction_status')}} </a>
                                    </li>
                                    <li class=" ">
                                        <a href="{{route('membership_type.index')}}" class="nav-link  ">
                                            <i class="fa fa-gears"></i> {{trans('sidebar.membership_type')}} </a>
                                    </li>
                                    <li class=" ">
                                        <a href="{{route('country.index')}}" class="nav-link  ">
                                            <i class="fa fa-gears"></i> {{trans('sidebar.country')}} </a>
                                    </li>

                                    <li class=" ">
                                        <a data-toggle="confirmation" data-popout="true" data-singleton="true" data-url="{{url('backup')}}" class="backup"  data-placement="bottom" title='Create backup of current database and files?'><i class="fa fa-download"></i> {{trans('sidebar.system_backup')}}
                                            <span class="arrow"></span>
                                        </a>
                                    </li>
                                    <li class=" ">
                                        <a data-toggle="confirmation" data-popout="true" data-singleton="true" data-url="{{url('clear_cache')}}" class="clear_cache"  data-placement="bottom" title='Clear cache?'><i class="fa fa-eraser"></i> {{trans('sidebar.clear_cache')}}
                                            <span class="arrow"></span>
                                        </a>
                                    </li>
                                    <li class=" ">
                                        <a href="{{route('final_analysis_message.index')}}" class="nav-link  ">
                                            <i class="fa fa-gears"></i> {{trans('sidebar.final_analysis_message')}} </a>
                                    </li>
                                    <li class=" ">
                                        <a href="{{route('setting.edit', 1)}}" class="nav-link  ">
                                            <i class="fa fa-gears"></i> {{trans('sidebar.setting')}} </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="menu-dropdown classic-menu-dropdown">
                                <a href="javascript:;"><i class="icon-calculator"></i> {{trans('sidebar.calculation')}}
                                    <span class="arrow"></span>
                                </a>
                                <ul class="dropdown-menu pull-left" style="min-width: 225px;">
                                    <li class="">
                                        <a href="{{url('calculation/method-one')}}" class="nav-link">
                                            <i class="fa fa-calculator"></i> {{trans('sidebar.method_1')}}
                                        </a>
                                    </li>
                                    <li class=" ">
                                        <a href="{{url('calculation/method-two')}}" class="nav-link  ">
                                            <i class="fa fa-calculator"></i> {{trans('sidebar.method_2')}} </a>
                                    </li>
                                    <li class=" ">
                                        <a href="{{url('calculation/method-three')}}" class="nav-link  ">
                                            <i class="fa fa-calculator"></i> {{trans('sidebar.method_3')}} </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="nav-item">
                                <a href="{{route('stock.calibration', \App\Stock::max('serial_no'))}}"><i class="fa fa-sliders"></i> {{trans('sidebar.calibration')}}
                                    <span class="arrow"></span>
                                </a>
                            </li>
                            <li class="menu-dropdown classic-menu-dropdown">
                                <a href="javascript:;"><i class="icon-pencil"></i> {{trans('sidebar.content_management')}}
                                    <span class="arrow"></span>
                                </a>
                                <ul class="dropdown-menu pull-left" style="min-width: 225px;">
                                    <li class="">
                                        <a href="{{route('news.index')}}" class="nav-link">
                                            <i class="fa fa-newspaper-o"></i> {{trans('sidebar.news')}}
                                        </a>
                                    </li>
                                    <li class=" ">
                                        <a href="{{route('marquee_content.index')}}" class="nav-link  ">
                                            <i class="fa fa-rss"></i> {{trans('sidebar.marquee_content')}} </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="menu-dropdown classic-menu-dropdown">
                                <a href="javascript:;"><i class="icon-bar-chart"></i> {{trans('sidebar.reports')}}
                                    <span class="arrow"></span>
                                </a>
                                <ul class="dropdown-menu pull-left">
                                    <li class=" ">
                                        <a href="{{route('stock.analytical_report')}}" class="nav-link  ">
                                            <i class="icon-equalizer"></i> {{trans('sidebar.analytical_report')}} </a>
                                    </li>
                                    <li class=" ">
                                        <a href="{{route('stock.levels_report')}}" class="nav-link  ">
                                            <i class="icon-equalizer"></i> {{trans('sidebar.levels_report')}} </a>
                                    </li>
                                </ul>
                            </li>
                        @else
                            <li class="nav-item">
                                <a href="{{route('stock_closing_reading.index')}}">
                                <i class="icon-bar-chart"></i> {{trans('sidebar.stock_closing_reading')}}
                                    <span class="arrow"></span>
                                </a>
                            </li>
                        @endif
                        </ul>
                    </div>
                    <!-- END MEGA MENU -->
                    
                    <!-- BEGIN TOP NAVIGATION MENU -->
                    <div class="top-menu pull-right" style="color: #BCC2CB;font-size: 14px; font-weight: 400;padding: 16px 18px 15px;">
                        {{trans('messages.hello')}} <span class="username username-hide-mobile">{{Auth::user()->full_name}}</span>
                        |
                        <a href="{{ url('/logout') }}" class="font-red-pink" style="text-decoration: none;">
                            <i class="icon-key"></i> {{trans('actions.logout')}}
                        </a>
                        |
                        @if(\Session::get('lang', 'en') == 'ar')
                        <a title="View in English" data-placement="bottom" href="{{ route('lang', 'en') }}" class="font-yellow-crusta tooltips" style="text-decoration: none;">
                            EN
                        </a>
                        @else
                        <a title="عرض باللغة العربية" data-placement="bottom" href="{{ route('lang', 'ar') }}" class="font-yellow-crusta tooltips" style="text-decoration: none;">
                            ع
                        </a>
                        @endif
                    </div>
                    <!-- END TOP NAVIGATION MENU -->
                </div>
            </div>
            <!-- END HEADER MENU -->
        </div>
        <!-- END HEADER -->
        <!-- BEGIN CONTAINER -->
        <div class="page-container">
            <!-- BEGIN CONTENT -->
            <div class="page-content-wrapper">
                            @yield('content')
            </div>
            <!-- END CONTENT -->
        </div>
        <!-- END CONTAINER -->
        <!-- BEGIN FOOTER -->
        <!-- BEGIN INNER FOOTER -->
        <div class="page-footer">
            <div class="container"> 2016 &copy; Trend Cast
            </div>
        </div>
        <div class="scroll-to-top">
            <i class="icon-arrow-up"></i>
        </div>
        <!-- END INNER FOOTER -->
        <!-- END FOOTER -->
        <!--[if lt IE 9]>
<script src="../assets/global/plugins/respond.min.js"></script>
<script src="../assets/global/plugins/excanvas.min.js"></script> 
<![endif]-->
        <!-- BEGIN CORE PLUGINS -->
        <script src="{{asset('assets/global/plugins/jquery.min.js')}}" type="text/javascript"></script>
        <script src="{{asset('assets/global/plugins/jquery-ui/jquery-ui.min.js')}}" type="text/javascript"></script>
        <script>
            /*** Handle jQuery plugin naming conflict between jQuery UI and Bootstrap ***/
            $.widget.bridge('uibutton', $.ui.button);
            $.widget.bridge('uitooltip', $.ui.tooltip);
        </script>
        <script src="{{asset('assets/global/plugins/bootstrap/js/bootstrap.min.js')}}" type="text/javascript"></script>
        <script src="{{asset('assets/global/plugins/js.cookie.min.js')}}" type="text/javascript"></script>
        <script src="{{asset('assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js')}}" type="text/javascript"></script>
        <script src="{{asset('assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js')}}" type="text/javascript"></script>
        <script src="{{asset('assets/global/plugins/jquery.blockui.min.js')}}" type="text/javascript"></script>
        <script src="{{asset('assets/global/plugins/uniform/jquery.uniform.min.js')}}" type="text/javascript"></script>
        <script src="{{asset('assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js')}}" type="text/javascript"></script>
        <!-- END CORE PLUGINS -->
        <!-- BEGIN PAGE LEVEL PLUGINS -->
        <script src="{{asset('assets/global/plugins/bootstrap-toastr/toastr.min.js')}}" type="text/javascript"></script>
        <script src="{{asset('assets/global/plugins/bootstrap-confirmation/bootstrap-confirmation.min.js')}}" type="text/javascript"></script>
        @yield('page_level_plugins_scripts')
        <!-- END PAGE LEVEL PLUGINS -->
        <!-- BEGIN THEME GLOBAL SCRIPTS -->
        <script src="{{asset('assets/global/scripts/app.js')}}" type="text/javascript"></script>
        <script src="{{asset('assets/global/scripts/app_custom.js?v=3')}}" type="text/javascript"></script>
        <script type="text/javascript">
            $(document).ready(function(){

                $('body').on('confirmed.bs.confirmation', '[data-toggle="confirmation"].backup, [data-toggle="confirmation"].clear_cache', function () {
                    var currentElement = $(this);
                    $(currentElement).attr('disabled', 'disabled');

                    var url = $(currentElement).data('url');

                    var target = $('body');
                    App.blockUI({
                        message: 'Process started, Please Wait...',
                        target: target,
                        overlayColor: 'black',
                        cenrerY: true,
                        boxed: true
                    });
                    $.ajax({
                        url : url,
                        type: 'POST',
                        headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }}).done(function(data){
                        toastr['success']("Process finished successfully!", "Success");
                    }).fail(function(jqXHR, textStatus, errorThrown){
                        if(jqXHR.status === 500)
                        {
                            toastr['error']("Internal Error code: 500", "Error")
                        }else
                        {
                            var data = jqXHR.responseJSON;
                            toastr['error'](data, "Error")
                        }
                    }).always(function(data) {
                        App.unblockUI(target);
                    });
                });
            });
        </script>
        @stack('scripts')
        <!-- END THEME GLOBAL SCRIPTS -->
        <!-- BEGIN PAGE LEVEL SCRIPTS -->
        @yield('page_level_scripts')
        <!-- END PAGE LEVEL SCRIPTS -->
        <!-- BEGIN THEME LAYOUT SCRIPTS -->
        <script src="{{asset('assets/layouts/layout3/scripts/layout.min.js')}}" type="text/javascript"></script>
        <script src="{{asset('assets/layouts/layout3/scripts/demo.min.js')}}" type="text/javascript"></script>
        <script src="{{asset('assets/layouts/global/scripts/quick-sidebar.min.js')}}" type="text/javascript"></script>
        <!-- END THEME LAYOUT SCRIPTS -->
    </body>

</html>