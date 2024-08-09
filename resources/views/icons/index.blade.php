@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12 mg-t-10">
            <div class="card mg-b-10">
                <div class="card-header pd-t-20 d-sm-flex align-items-start justify-content-between bd-b-0 pd-b-0">
                    <div>
                        <h6 class="mg-b-5">All icons for new</h6>
                    </div>
                    <div class="d-flex mg-t-20 mg-sm-t-0">
                        <div class="btn-group flex-fill">
                            <a href="{{ route('icons.create') }}" class="btn btn-success btn-sm">CREATE</a>
                        </div>
                    </div>
                </div>

                <div class="table-responsive table-hover">
                    <table class="table table-dashboard mg-b-0">
                        <thead>
                            <tr>
                                <th class="text-center">Title</th>
                                <th class="text-center">ICON</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($icons as $logo)
                            <tr>
                                <td class="tx-medium text-center">
                                    {!! $logo->name !!}
                                </td>
                                <td class="tx-medium text-center">
                                    <img src="{{ $logo->getFirstMediaUrl('icons') }}" alt="" width="150">
                                </td>
                                <td class="tx-medium text-center">
                                    <form method="post" action="{{ route('icons.destroy', $logo) }}" >
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="badge badge-danger">DELETE</button>
                                    </form>
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
