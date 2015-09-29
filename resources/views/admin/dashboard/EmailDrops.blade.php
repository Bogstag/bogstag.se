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
        <h3>Last 200 Drops</h3>
        <div class="row">
            <table class="table table-bordered" id="emaildrops-table">
                <thead>
                <tr>
                    <th>Id</th>
                    <th>Created At</th>
                    <th>Recipient</th>
                    <th>Sender</th>
                    <th>Subject</th>
                </tr>
                </thead>
            </table>
        </div>
@endsection

@push('scripts')
<script>
    $(function() {
        $('#emaildrops-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{!! url('admin/emaildrop/getEmailDropsData') !!}',
            order: [[0, "desc"]],
            columns: [
                {data: 'id', name: 'id'},
                {data: 'created_at', name: 'name'},
                {data: 'recipient', name: 'email'},
                {data: 'sender', name: 'created_at'},
                {data: 'subject', name: 'updated_at'}
            ]
        });
    });
</script>
@endpush
