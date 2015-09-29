@extends('admin.layouts.default')

{{-- Web site Title --}}
@section('title') {!! $title !!} :: @parent @stop

{{-- Content --}}
@section('main')
    <div class="row">
        <div class="page-header">
            <h2>Email Drops</h2>
        </div>
    </div>
    @if (count($emaildrops))
        <h3>Last 200 Drops</h3>
        <div class="row">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th><?php echo implode('</th><th>', array_keys(current($emaildrops))); ?></th>
                </tr>
                </thead>
                <tbody>
                @foreach ($emaildrops as $emaildrop)
                    <tr>
                        <td><a href="{{ URL::to('/admin/emaildrop', $emaildrop['id'])}}">{{$emaildrop['id']}}</a></td>
                        <td>{{$emaildrop['recipient']}}</td>
                        <td>{{$emaildrop['sender']}}</td>
                        <td>{{$emaildrop['subject']}}</td>
                        <td>{{$emaildrop['Spamscore']}}</td>
                        <td>{{$emaildrop['Spamflag']}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endsection
