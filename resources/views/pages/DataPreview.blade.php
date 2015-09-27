@extends('layouts.master')
@section('title') {{ $header }} datapreview - @parent @stop
@section('content')
    <div class="row">
        <div class="page-header">
            <h2>{{ $header }} - A small data preview</h2>
        </div>
    </div>
    @if (count($posts))
        <div class="row">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th><?php echo implode('</th><th>', array_keys(current($posts))); ?></th>
                </tr>
                </thead>
                <tbody>
                @foreach ($posts as $post)
                    <tr>
                        <td><?php echo implode('</td><td>', $post); ?></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endsection
@stop
