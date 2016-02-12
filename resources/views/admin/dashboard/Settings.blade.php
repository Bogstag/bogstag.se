@extends('admin.layouts.default')

{{-- Web site Title --}}
@section('title') {!! $title = 'Settings' !!} :: @parent @stop

{{-- Content --}}
@section('main')
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">{!! $title !!}</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <div class="row">
        <div class="col-lg-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Api Key
                </div>
                <div class="panel-body">
                    <p>
                    <form metod="POST" action="{{ URL::secure('/admin/settings/resetapitoken')}}">
                        {!! csrf_field() !!}
                        <div class="form-group input-group">
                            <span class="input-group-addon"><i class="fa fa-key"></i></span>
                            <input type="text" id="RefreshApiToken" name="RefreshApiToken" class="form-control" value="{{$ApiToken}}" placeholder="No Api Token">
                        </div>
                        <button type="submit" class="btn btn-default">Refresh ApiToken</button>
                    </form>
                    </p>
                </div>
                <div class="panel-footer">
                    Secret key, reset if known.
                </div>
            </div>
        </div>
        <!-- /.col-lg-4 -->
        <div class="col-lg-4">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    Primary Panel
                </div>
                <div class="panel-body">
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum tincidunt est vitae ultrices
                        accumsan. Aliquam ornare lacus adipiscing, posuere lectus et, fringilla augue.</p>
                </div>
                <div class="panel-footer">
                    Panel Footer
                </div>
            </div>
        </div>
        <!-- /.col-lg-4 -->
        <div class="col-lg-4">
            <div class="panel panel-success">
                <div class="panel-heading">
                    Success Panel
                </div>
                <div class="panel-body">
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum tincidunt est vitae ultrices
                        accumsan. Aliquam ornare lacus adipiscing, posuere lectus et, fringilla augue.</p>
                </div>
                <div class="panel-footer">
                    Panel Footer
                </div>
            </div>
        </div>
        <!-- /.col-lg-4 -->
    </div>
@endsection
