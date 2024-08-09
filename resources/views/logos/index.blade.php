@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12 mg-t-10">
            <div class="card mg-b-10">
                <div class="card-header pd-t-20 d-sm-flex align-items-start justify-content-between bd-b-0 pd-b-0">
                    <div>
                        <h6 class="mg-b-5">All TV partners logos</h6>
                    </div>
                    <div class="d-flex mg-t-20 mg-sm-t-0">
                        <div class="btn-group flex-fill">
                            <a href="{{ route('logos.create') }}" class="btn btn-success btn-sm">CREATE</a>
                        </div>
                    </div>
                </div>

                <div class="table-responsive table-hover">
                    <table class="table table-dashboard mg-b-0">
                        <thead>
                            <tr>
                                <th class="text-center">Logo</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($logos as $logo)
                            <tr>
                                <td class="tx-medium text-center">
                                    <img src="{{ $logo->getFirstMediaUrl('brands') }}" alt="" width="150">
                                </td>
                                <td class="tx-medium text-center">
                                    <form method="post" action="{{ route('logos.destroy', $logo) }}" class="float-right">
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
