@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12 mg-t-10">
            <div class="card mg-b-10">
                <div class="card-header pd-t-20 d-sm-flex align-items-start justify-content-between bd-b-0 pd-b-0">
                    <div>
                        <h6 class="mg-b-5">Import</h6>
                    </div>
                </div>

                <div class="card-header text-lg-center">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header pd-b-0 pd-t-20 bd-b-0">
                                <h6 class="mg-b-0">
                                    Import data from bank (csv;) - SWEDBANK
                                </h6>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('import.store') }}"
                                      method="post" enctype="multipart/form-data">
                                    @csrf
                                    <input type="file" autocomplete="off"
                                           name="swedbank[]" multiple required>
                                    <button class="btn btn-success">
                                        Upload!
                                    </button>
                                </form>
                                <pre class="text-left">
                                    ATASKAITAI imant:
                                    Kasdieninės paslaugos > Sąskaitos išrašas

                                    - Išplėstas formatas
                                    - Grupiniai atskirom sumom
                                    - Banko mokesčiai kaip viena suma
                                </pre>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
