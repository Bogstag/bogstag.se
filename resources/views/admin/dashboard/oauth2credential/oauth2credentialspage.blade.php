@extends('admin.layouts.default')

{{-- Web site Title --}}
@section('title') {!! $title !!} :: @parent @stop

{{-- Content --}}
@section('main')
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">{!! $title !!}</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Authorize new credentials
                </div>
                <div class="panel-body">
                    <p>
                    <form action="{{URL::secure('admin/oauth2credential')}}" method="POST">
                        {!! csrf_field() !!}
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon">Provider</span>
                                <input type="text" id="provider" name="provider" class="form-control" value=""
                                       placeholder="Missing Provider">
                            </div>
                            <div class="input-group">
                                <span class="input-group-addon">Client Id</span>
                                <input type="text" id="clientid" name="clientid" class="form-control" value=""
                                       placeholder="Missing Client Id">
                            </div>
                            <div class="input-group">
                                <span class="input-group-addon">Client Secret</span>
                                <input type="text" id="clientsecret" name="clientsecret" class="form-control" value=""
                                       placeholder="Missing Client Secret">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-default">Authorize</button>
                    </form>
                    </p>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        @if ($oauth2Credentials)
            @foreach ($oauth2Credentials as $oauth2Credential)
                <div class="col-lg-4">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            {{ $oauth2Credential->provider }}
                        </div>
                        <div class="panel-body">
                            <p>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon">Client Id</span>
                                    <input type="text" id="clientid" name="clientid" class="form-control"
                                           value="{{ $oauth2Credential->clientid }}"
                                           placeholder="Missing Client Id">
                                </div>
                                <div class="input-group">
                                    <span class="input-group-addon">Client Secret</span>
                                    <input type="text" id="clientsecret" name="clientsecret" class="form-control"
                                           value="{{ $oauth2Credential->clientsecret }}"
                                           placeholder="Missing Client Secret">
                                </div>
                                <div class="input-group">
                                    <span class="input-group-addon">Access Token</span>
                                    <input type="text" id="accesstoken" name="accesstoken" class="form-control"
                                           value="{{ $oauth2Credential->accesstoken }}"
                                           placeholder="Missing Access Token">
                                </div>
                                <div class="input-group">
                                    <span class="input-group-addon">Refresh Token</span>
                                    <input type="text" id="refreshtoken" name="refreshtoken" class="form-control"
                                           value="{{ $oauth2Credential->refreshtoken }}"
                                           placeholder="Missing Refresh Token">
                                </div>
                                <div class="input-group">
                                    <span class="input-group-addon">Expires</span>
                                    <input type="text" id="expires" name="expires" class="form-control"
                                           value="{{ $oauth2Credential->expires }}"
                                           placeholder="Missing Expires">
                                </div>
                                <div class="input-group">
                                    <span class="input-group-addon">Redirect Uri</span>
                                    <input type="text" id="redirecturi" name="redirecturi" class="form-control"
                                           value="{{ $oauth2Credential->redirecturi }}"
                                           placeholder="Missing Redirect Uri">
                                </div>
                            </div>
                            </p>
                        </div>
                        <div class="panel-footer">
                            Last updated: {{ $oauth2Credential->updated_at }}
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
@endsection
