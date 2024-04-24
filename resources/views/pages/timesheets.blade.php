@extends('layouts.app')

@section('content')
<div class="">
    <div class="row">
        <div class="col-md-12  d-flex justify-content-end">
         
        </div>
    </div>
    <div class="row  m-4">
            <div class="col-md-12">
                <table class="table is-striped" id="timesheets">
                    <thead>
                    <tr>
                        
                        <th>Employee Name</th>
                        <th>SP Number</th>
                        <th>Time In</th>
                        <th>Time Out</th>
                        <th>Total Hours</th>
                        <th>Client Name</th>
                        <th>Service Code</th>
                        <th>Status</th>
                        
                    </tr>
                    </thead>
                    <tbody>
                        @foreach($timesheets as $timesheet)
                            <tr >
                                <td>{{$timesheet->employee->first_name}} {{$timesheet->employee->last_name}}</td>
                                <td>{{$timesheet->employee->SP_number}}</td>
                                <td>{{$timesheet->start_date}}  {{$timesheet->start_time}}</td>
                                <td>{{$timesheet->end_date}}  {{$timesheet->end_time}}</td>
                                <td>{{$timesheet->total_hours}} Hours</td>
                                <td>{{$timesheet->client->first_name}} {{$timesheet->client->last_name}}</td>
                                <td>{{$timesheet->service_code ?? 'Na'}}</td>
                                <td>
                                    @if($timesheet->status > 0)
                                        Approved
                                    @elseif($timesheet->status == 0)
                                        Pending
                                    @else
                                        Declined
                                    @endif
                                </td>
                                
                              
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
    </div>
</div>
 <script>
    $(document).ready(function() {
        var table = new DataTable('#timesheets',{
            dom: 'Bfrtip',
            order: [[0, 'desc']],
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ],
            select: true
        });
    });
 </script>
@endsection