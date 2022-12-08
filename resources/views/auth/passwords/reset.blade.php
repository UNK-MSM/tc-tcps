@extends('Site::layouts.master')

@section('content')


<style type="text/css">
    .help-block
    {
        font-size: 11px;
    }
</style>


<!-- BEGIN CONTENT BODY -->
<!-- BEGIN PAGE CONTENT BODY -->
<div class="page-content">
    <div class="container">
        <!-- BEGIN PAGE BREADCRUMBS -->
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <a href="#">Home</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>Reset Password</span>
            </li>
        </ul>
        <!-- END PAGE BREADCRUMBS -->
        <!-- BEGIN PAGE CONTENT INNER -->
        <div class="page-content-inner">

            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <span class="caption-subject font-red-sunglo bold uppercase">Reset Password</span>
                    </div>
                </div>
                <div class="portlet-body form">
                    @if(!empty($errors))
                    @foreach($errors->all() as $error)
                    <div class="alert alert-danger alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
                        {{$error}}
                    </div>
                    @endforeach
                    @endif
                    <!-- BEGIN FORM-->
                    
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/password/reset') }}">
                        {!! csrf_field() !!}
                        <input type="hidden" name="token" value="{{ $token }}">


                        <div class="form-body">
                            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3">E-Mail Address</label>
                                <div class="col-md-3">
                                    <input type="email" class="form-control input-sm" name="email" value="{{ $email or old('email') }}">
                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3">Password</label>
                                <div class="col-md-3">
                                    <input type="password" name="password" class="form-control input-sm" />

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3">Confirm Password</label>
                                <div class="col-md-3">
                                    <input type="password" name="password_confirmation" class="form-control input-sm" />

                                @if ($errors->has('password_confirmation'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                                @endif
                                </div>
                            </div>
                        </div>

                        <div class="form-actions right">
                            <button type="submit" class="btn green"><i class="fa fa-spinner fa-spin hidden"> </i> Reset Password</button>
                        </div>
                    </form>
                    <!-- END FORM-->
                </div>
            </div>



        </div>
        <!-- END PAGE CONTENT INNER -->
    </div>
</div>
<!-- END PAGE CONTENT BODY -->
<!-- END CONTENT BODY -->

@endsection
