@extends('layouts.master')
@section('title') Server / Email - @parent @stop
@section('content')
    <div class="row">
        <div class="page-header">
            <h2>Server / Email</h2>
        </div>
    </div>
    <div class="row">
        <div id="emaildel_div"></div>
        @columnchart('emaildel', 'emaildel_div')
    </div>
    <div class="row">
        <div id="emaildrops_div"></div>
        @linechart('emaildrops', 'emaildrops_div')
    </div>
    @if (count($EmailPublicDropList))
        <h3>Public drop list (last 20)</h3>
        <div class="row">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th><?php echo implode('</th><th>', array_keys(current($EmailPublicDropList))); ?></th>
                </tr>
                </thead>
                <tbody>
                @foreach ($EmailPublicDropList as $EmailPublicDrops)
                    <tr>
                        <td><?php echo implode('</td><td>', $EmailPublicDrops); ?></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endsection

@section('scripts')

@endsection

