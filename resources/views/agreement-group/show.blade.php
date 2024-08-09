@extends('layouts.app')

@section('content')
    <div class="container pd-x-0 pd-lg-x-10 pd-xl-x-0">
        <div class="row">
            <div class="col-md-4 text-left">
               <a href="{{ route('sales.edit', $agreementGroup->sale) }}" class="btn btn-primary">{{ $agreementGroup->sale->name }}</a>
                <h4 id="section3" class="mg-b-0">{{ $agreementGroup->name }}</h4>
            </div>
            <div class="col-md-4 text-right text-success">{{ $agreementGroup->getBudgetStatus() }}</div>
            <div class="col-md-4 text-right">{{ $agreementGroup->deadline }}<br>{{ $agreementGroup->getDeadlineStatus() }}</div>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-3">
                <div class="row">
                    <div class="col-md-12 mb-2">
                        <div class="card">
                            <div class="card-header h5">{{ __('Task group in hand:') }}</div>
                            <div class="card-body row">
                                <div class="form-group col-md-12">
                                    <label for="sale_id">Sale</label>
                                    <select name="sale_id" id="sale_id" class="form-control" disabled>
                                        <option value="">--- Select ---</option>
                                        @foreach($sales as $sale)
                                            <option value="{{ $sale->id }}"
                                                    @if(old('sale_id', $agreementGroup->sale_id) == $sale->id) selected="selected" @endif>
                                                {{ $sale->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="start">Start</label>
                                    <input type="date" class="form-control" id="start" disabled name="start" value="{{ old('start', $agreementGroup->start) }}">
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="deadline">Deadline</label>
                                    <input type="date" class="form-control" id="deadline" disabled name="deadline" value="{{ old('deadline', $agreementGroup->deadline) }}">
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="budget">Budget</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="budget" disabled name="budget"
                                               value="{{ old('budget', $agreementGroup->budget) }}">
                                        <div class="input-group-append">
                                            <span class="input-group-text">€</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="row">
                    <div class="col-md-12 mb-2">
                        <div class="card">
                            <div class="card-header h5">{{ __('Task group in hand:') }}</div>
                            <div class="card-body">
                                {!! $agreementGroup->description !!}
                            </div>
                        </div>

                    </div>
                    <div class="col-md-12 mb-2">
                        <div class="card">
                            <div class="card-header h5">{{ __('Files') }}</div>
                            <div class="card-body">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 mb-2">
                        <div class="row" id="accordion">
                            <div class="col-md-12 mb-2">
                                <div class="card">
                                    <div class="card-header h5">{{ __('Assigned tasks:') }}</div>
                                    <div class="card-body row">
                                        <div class="col-md-12">
                                            <table class="table table-striped table-hover">
                                                <thead>
                                                <tr>
                                                    <th scope="col" class="text-left">ID</th>
                                                    <th scope="col" class="text-center">Name</th>
                                                    <th scope="col" class="text-center">List</th>
                                                    <th scope="col" class="text-center">Book./Est.</th>
                                                    <th scope="col" class="text-center">Budget</th>
                                                    <th scope="col" class="text-center">From</th>
                                                    <th scope="col" class="text-center">To</th>
                                                    <th scope="col" class="text-center">Progress</th>
                                                    <th scope="col" class="text-right" style="width: 80px;">Actions</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($agreementGroup->agreements as $agreement)
                                                    <tr>
                                                        <td class="text-left small">{{ $agreement->id }}</td>
                                                        <td class="text-center small">
                                                            <a href="https://tasks.onesoft.io/projects/{{ $agreement->task->project_id }}?modal=Task-{!! $agreement->task->id !!}-{{ $agreement->task->project_id }}"
                                                               target="_blank">{{ $agreement->name }}
                                                            </a>
                                                        </td>
                                                        <td class="text-center small">{{ $agreement->task->taskList->name }}</td>
                                                        <td class="text-center small">{{ $agreement->task->getBookedTime() }} / {{ $agreement->estimate }} h</td>
                                                        <td class="text-center small">{{ $agreement->budget ? \App\Label::formatPrice($agreement->budget) : '' }}</td>
                                                        <td class="text-center small">{{ $agreement->from ?? $agreement->task->start_on }}</td>
                                                        <td class="text-center small">{{ $agreement->to ?? $agreement->task->start_due }}</td>
                                                        <td class="text-center small">{{ $agreement->getProgress() }}</td>
                                                        <td class="text-right small">
                                                            <a href="{{ route('agreement.edit', $agreement) }}" class="tx-info">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                                                                    <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"/>
                                                                </svg>
                                                            </a>
                                                            <a class="tx-success"
                                                               href="https://tasks.onesoft.io/projects/{{ $agreement->task->project_id }}?modal=Task-{!! $agreement->task->id !!}-{{ $agreement->task->project_id }}"
                                                               target="_blank"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                                                                    <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                                                                </svg>
                                                            </a>
                                                            <a class="tx-danger" href="#" id="deleteButton"
                                                               data-delete-route="{{ route('agreement.destroy', $agreement) }}"
                                                               data-id-agreement="{{ $agreement->id }}"
                                                            >
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                                                    <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                                                                    <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                                                                </svg>
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
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header h5 d-inline">
                                        <div class="float-left">{{ __('Assign new task:') }}</div>
                                        <div class="float-right"><button class="btn-xs btn-primary collapsed"
                                                data-toggle="collapse"
                                                data-target="#newTask"
                                                aria-expanded="false"
                                                aria-controls="newTask"
                                            >New Task</button>
                                        </div>
                                    </div>
                                    <div class="card-body collapse row" id="newTask" aria-labelledby="newTask" data-parent="#accordion">
                                        <div class="col-md-12">
                                            <form method="post" action="{{ route('agreement.store') }}">
                                            @csrf
                                            <input type="hidden" name="agreement_group_id" value="{{ $agreementGroup->id }}">
                                            <div class="form-row">
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <div class="form-group col-md-6">
                                                            <label for="task_id">Collab not assigned task from Sale project*</label>
                                                            <select name="task_id" id="task_id" class="form-control">
                                                                <option value="" disabled>CREATE NEW TASK IN COLLAB</option>
                                                                @foreach($agreementGroup->sale->tasks as $task)
                                                                    @if (!$task->isActive() || $agreementGroup->agreements->contains('task_id', $task->id)) @continue @endif
                                                                    <option value="{{ $task->id }}">{{ $task->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label for="depends_id">Depends on another task*</label>
                                                            <select name="depends_id" id="depends_id" class="form-control" disabled>
                                                                <option value="">--- Select ---</option>
                                                                @foreach($agreementGroup->agreements as $agreement)
                                                                    <option value="{{ $agreement->task->id }}">{{ $agreement->task->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group col-md-3">
                                                    <label for="name">Name</label>
                                                    <input type="text" class="form-control" id="name" name="name"
                                                           value="{{ old('name') }}">
                                                </div>
                                                <div class="col-md-9">
                                                    <div class="row">
                                                        <div class="form-group col-md-3">
                                                            <label for="budget">Estimate (€)</label>
                                                            <div class="input-group">
                                                                <input type="text" class="form-control" id="budget"
                                                                       @if($agreementGroup->budged) disabled @endif
                                                                       name="budget"
                                                                       value="{{ old('budget') }}">
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text">€</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group col-md-3">
                                                            <label for="estimate">Estimate (h)</label>
                                                            <div class="input-group">
                                                                <input type="text" class="form-control" id="estimate"
                                                                       @if($agreementGroup->budged) disabled @endif
                                                                       name="estimate"
                                                                       value="{{ old('estimate') }}">
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text">h</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group col-md-3">
                                                            <label for="from">From</label>
                                                            <input type="date" class="form-control"
                                                                   min="{{ \Carbon\Carbon::createFromFormat('Y-m-d', $agreementGroup->start)->format('Y-m-d') }}"
                                                                   max="{{ $agreementGroup->deadline }}"
                                                                   id="from" name="from" value="{{ old('from') }}">
                                                        </div>
                                                        <div class="form-group col-md-3">
                                                            <label for="to">To</label>
                                                            <input type="date" class="form-control"
                                                                   min="{{ \Carbon\Carbon::createFromFormat('Y-m-d', $agreementGroup->start)->format('Y-m-d') }}"
                                                                   max="{{ $agreementGroup->deadline }}"
                                                                   id="to" name="to" value="{{ old('to') }}">
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="form-group col-md-12">
                                                    <label for="description">Description</label>
                                                    <textarea name="description" id="description" cols="30" class="form-control"
                                                              rows="10">{{ old('description') }}</textarea>
                                                </div>
                                            </div>
                                            <button type="submit" class="btn btn-primary">Add</button>
                                        </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 mb-2">
                        <div class="card">
                            <div class="card-header h5">{{ __('Comments') }}</div>
                            <div class="card-body">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .select2 {
            width: 100%!important; /* overrides computed width, 100px in your demo */
        }
    </style>
@endpush()
@push('javascript')
    <script src="https://cdn.ckeditor.com/4.17.1/standard/ckeditor.js"></script>
    <script>
        CKEDITOR.replace('description');
        $("[id^='deleteButton']").on("click", function (e) {
            e.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
                text: "This action is not reversible!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.value) {
                    let url = $(this).data('delete-route');
                    axios({
                        url: url,
                        method: 'POST',
                        timeout: 8000,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        params: {
                            _method: 'DELETE'
                        }
                    })
                        .then(res => {
                            Swal.fire({
                                title: res.data.message,
                                text: res.data.text,
                                icon: res.data.status,
                            }).then(function() {
                                window.location.reload();
                            })
                            return false;
                        })
                        .catch(err => {
                            Swal.fire({
                                title: 'Error!',
                                text: err,
                                icon: 'error'
                            }).then(function () {
                                window.location.reload();
                            })
                        });
                }
            });
        });
    </script>
@endpush


