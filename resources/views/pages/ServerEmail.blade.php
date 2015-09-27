@extends('layouts.master')
@section('title') Server / Email - @parent @stop
@section('content')
    <div class="row">
        <div class="page-header">
            <h2>Server / Email</h2>
        </div>
    </div>
    <div class="row">
        <div id="emailstat_div"></div>
        @columnchart('emailstat', 'emailstat_div')
    </div>
    @if (count($emaildrops))
        <h3>Public drop list (last 20)</h3>
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
                        <td><?php echo implode('</td><td>', $emaildrop); ?></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endsection

@section('scripts')

@endsection

