@extends('admin.layouts.default')

{{-- Web site Title --}}
@section('title') {!! $title !!} :: @parent @stop

{{-- Content --}}
@section('main')
    @if (count($emaildrop))
        <div class="row">
            <div class="page-header">
                <h2>Email Drop ({{$emaildrop['id']}})</h2>
            </div>
        </div>

        <div class="row">
            <table class="table table-striped">
                <thead>
                <tr>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>Id</td>
                    <td><a href="{{ URL::to('/admin/emaildrop', $emaildrop['id'])}}">{{$emaildrop['id']}}</a></td>
                </tr>
                <tr>
                    <td>recipient</td>
                    <td>{{$emaildrop['recipient']}}</td>
                </tr>
                <tr>
                    <td>sender</td>
                    <td>{{$emaildrop['sender']}}</td>
                </tr>
                <tr>
                    <td>subject</td>
                    <td>{{$emaildrop['subject']}}</td>
                </tr>
                <tr>
                    <td>Spf</td>
                    <td>{{$emaildrop['Spf']}}</td>
                </tr>
                <tr>
                    <td>Spamscore</td>
                    <td>{{$emaildrop['Spamscore']}}</td>
                </tr>
                <tr>
                    <td>Spamflag</td>
                    <td>{{$emaildrop['Spamflag']}}</td>
                </tr>
                <tr>
                    <td>DkimCheck</td>
                    <td>{{$emaildrop['DkimCheck']}}</td>
                </tr>
                <tr>
                    <td>Public</td>
                    <td>{{$emaildrop['public']}}</td>
                </tr>
                <tr>
                    <td>Created</td>
                    <td>{{$emaildrop['created_at']}}</td>
                </tr>
                <tr>
                    <td>Plain Body</td>
                    <td>{{$emaildrop['bodyplain']}}</td>
                </tr>
                <tr>
                    <td>Headers</td>
                    <td>{{$emaildrop['messageheaders']}}</td>
                </tr>
                </tbody>
            </table>
        </div>
    @endif
@endsection
