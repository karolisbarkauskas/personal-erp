@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12 mg-t-10">
            <div class="card mg-b-10">
                <div class="card-header pd-t-20 d-sm-flex align-items-start justify-content-between bd-b-0 pd-b-0">
                    <div>
                        <h6 class="mg-b-5">Data map</h6>

                        <a href="{{ route('youtrack-importer.map', ['full' => true]) }}" class="btn btn-info btn-sm">
                            Show only unmapped
                        </a>
                    </div>
                </div>

                <div class="card-header text-lg-center">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th scope="col" style="width: 20%">Sale</th>
                                            <th scope="col" style="width: 1%"></th>
                                            <th scope="col" style="width: 15%">Employee</th>
                                            <th scope="col" style="width: 20%">Task ID</th>
                                            <th scope="col" style="width: 20%">Summary</th>
                                            <th scope="col" style="width: 10%">Estimate</th>
                                            <th scope="col" style="width: 10%">Actual time</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($rows as $row)
                                            <livewire:youtrack-map :record="$row" :wire:key="$row->id">
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
