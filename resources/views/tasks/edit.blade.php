@extends('layouts.app')
@section('title', $title. ' | ')

@section('content')
    <div class="row mt-3">
        <div class="col-sm-5">
            <livewire:rates-calculator>
        </div>
        <div class="col-sm-2">
            <div class="card card-body">
                <livewire:week-sums :list="$list">
            </div>
            <div class="mt-3">
                <livewire:week-locker :list="$list">
            </div>
            <div class="mt-3">
                <div class="card">
                    <div class="card-header pd-b-0 pd-t-20 bd-b-0">
                        <h6 class="mg-b-0">
                            Youtrack weekly report
                        </h6>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('week.import', $list) }}"
                              method="post" enctype="multipart/form-data">
                            @csrf
                            <input type="file" autocomplete="off"
                                   name="youtrack" required>
                            <button class="badge badge-success">
                                Upload!
                            </button>
                            <small>
                                hours (with fractional values)
                            </small>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-5">
            <div class="card card-body">
                <h6 class="tx-uppercase tx-11 tx-spacing-1 tx-color-02 tx-semibold mg-b-8">
                    Your notes for weeks (visible only to you): <br>
                </h6>
                <div class="mt-3">
                    <livewire:task-notes>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-md-12">
            <table class="table table-striped table-hover">
                <thead>
                <tr>
                    @foreach($employees as $employee)
                        <th scope="col" class="tx-center" style="width: {{ (100/$employees->count()) }}%">
                            <livewire:add-to-week :employee="$employee" :week="$list"
                                                  :wire:key="'empl-' . $employee->id">
                        </th>
                    @endforeach
                </tr>
                </thead>
                <tbody>
                <tr>
                    @foreach($employees as $employee)
                        <livewire:week-component :employee="$employee" :week="$list" :wire:key="$employee->id">
                    @endforeach
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection
