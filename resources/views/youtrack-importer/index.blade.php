@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12 mg-t-10">
            <div class="card mg-b-10">
                <div class="card-header pd-t-20 d-sm-flex align-items-start justify-content-between bd-b-0 pd-b-0">
                    <div>
                        <h6 class="mg-b-5">Import</h6>

                        <a href="{{ route('youtrack-importer.map', ['full' => 1]) }}" class="btn btn-success btn-sm">
                            MAP
                        </a>
                    </div>
                </div>

                <div class="card-header text-lg-center">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header pd-b-0 pd-t-20 bd-b-0">
                                <h6 class="mg-b-0">
                                    Youtrack file import
                                </h6>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('youtrack-importer.store') }}"
                                      method="post" enctype="multipart/form-data">
                                    @csrf
                                    <input type="file" autocomplete="off"
                                           name="data[]" multiple required>
                                    <button class="btn btn-success">
                                        Upload!
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
