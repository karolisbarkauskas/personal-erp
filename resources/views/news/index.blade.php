@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12 mg-t-10">
            <div class="card mg-b-10">
                <div class="card-header pd-t-20 d-sm-flex align-items-start justify-content-between bd-b-0 pd-b-0">
                    <div>
                        <h6 class="mg-b-5">All TV news</h6>
                    </div>
                    <div class="d-flex mg-t-20 mg-sm-t-0">
                        <div class="btn-group flex-fill">
                            <a href="{{ route('news.create') }}" class="btn btn-success btn-sm">CREATE</a>
                        </div>
                    </div>
                </div>

                <div class="table-responsive table-hover">
                    <table class="table table-dashboard mg-b-0">
                        <thead>
                        <tr>
                            <th class="text-center">Title</th>
                            <th class="text-center">Active from -> to</th>
                            <th class="text-center">Active?</th>
                            <th class="text-center">Created By</th>
                            <th class="text-center">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($news as $new)
                            <tr>
                                <td class="tx-medium text-center">
                                    {{ $new->title }}
                                </td>
                                <td class="tx-medium text-center">
                                    {{ $new->getActive() }}
                                </td>
                                <td class="tx-medium text-center">
                                    @if($new->isActive())
                                        <span class="badge badge-success">VISIBLE</span>
                                    @else
                                        <span class="badge badge-warning">NOT VISIBLE</span>
                                    @endif
                                </td>
                                <td class="tx-medium text-center">
                                    {!! $new->createdBy->name !!}
                                </td>
                                <td class="tx-medium text-center">
                                    <a href="{{ route('news.edit', $new) }}" class="btn btn-info btn-xs">
                                        Edit
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
