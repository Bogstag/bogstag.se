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
    <div class="panel panel-default">
        <div class="panel-heading">Add movie tickets</div>
        <table class="table table-bordered" id="emaildrops-table">
            <thead>
            <tr>
                <col width="17%">
                <col width="20%">
                <col width="13%">
                <col width="13%">
                <col width="13%">
                <col width="13%">
                <col width="11%">
                <th>IMDB Id</th>
                <th>Ticket DateTime</th>
                <th>Ticket Price</th>
                <th>Ticket Row</th>
                <th>Ticket Seat</th>
                <th>Ticket Image</th>
                <th>Submit</th>
            </tr>
            </thead>
                <tr>
                    <form action="{{ Request::url() }}" method="POST">
                        {!! csrf_field() !!}
                        <td><input type="text" id="title" name="imdbid" class="form-control" value=""
                                   placeholder="imdb_id"></td>
                        <td><input type="text" id="ticket_datetime" name="ticket_datetime" class="form-control"
                                   value=""
                                   placeholder="ticket_datetime"></td>
                        <td><input type="text" id="ticket_price" name="ticket_price" class="form-control" value=""
                                   placeholder="ticket_price"></td>
                        <td><input type="text" id="ticket_row" name="ticket_row" class="form-control" value=""
                                   placeholder="ticket_row"></td>
                        <td><input type="text" id="ticket_seat" name="ticket_seat" class="form-control" value=""
                                   placeholder="ticket_seat"></td>
                        <td><input type="text" id="ticket_image" name="ticket_image" class="form-control" value=""
                                   placeholder="ticket_image"></td>
                        <td>
                            <button type="submit" class="btn btn-default">Submit</button>
                        </td>
                    </form>
                </tr>
        </table>
    </div>
@endsection
