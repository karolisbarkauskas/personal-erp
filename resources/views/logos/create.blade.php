@extends('layouts.app')

@section('content')
    <div class="container pd-x-0 pd-lg-x-10 pd-xl-x-0">

        <h4 id="section3" class="mg-b-10">
            Create new
        </h4>

        <div data-label="Example" class="df-example demo-forms">

            <form method="post" action="{{ route('logos.store') }}"
                  method="post" enctype="multipart/form-data">
                @csrf
                <div class="form-row">
                    <div class="form-group col-md-12">
                        @csrf

                        <input type="file" autocomplete="off"
                               name="files[]" multiple>

                        <button class="btn btn-success">
                            Upload!
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
