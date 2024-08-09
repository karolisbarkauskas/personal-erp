    <div>

        <div class="card-header pd-b-0 pd-x-20 bd-b-0">
            <div class="d-sm-flex align-items-center justify-content-between">
                <h3 class="mg-b-0 card-body tx-center">
                    Tasks report
                </h3>
            </div>
        </div>

        <table class="table table-striped table-hover">
            <thead>
            <tr>
                <th scope="col" class="tx-left" style="width: 10%">Task</th>
                <th scope="col" class="tx-left" style="width: 30%">Task description</th>
                <th scope="col" class="tx-center" style="width: 5%">Hours</th>
                <th scope="col" class="tx-center" style="width: 5%">Hourly rate</th>
                <th scope="col" class="tx-center" style="width: 5%">Total</th>
                <th scope="col" class="tx-center" style="width: 5%">Is task done?</th>
                <th scope="col" class="tx-center" style="width: 5%">Add to report?</th>
                <th scope="col" class="tx-center" style="width: 10%"></th>
                <th scope="col" class="tx-center" style="width: 10%"></th>
            </tr>
            </thead>
            <tbody>
            @foreach($income->reports as $report)
                <livewire:income-report-row :reportLine="$report" :wire:key="$report->id">
            @endforeach
            </tbody>
            <tfoot>
            <tr>
                <td>
                    <input type="text" class="form-control" wire:model="taskId"
                           id="taskId" name="text"/>
                    @error('taskId') <span class="error">{{ $message }}</span> @enderror
                </td>
                <td>
                    <textarea rows="5" class="form-control" wire:model="name"
                              id="name" name="text"/></textarea>
                    <input type="text" class="form-control" wire:model="taskLink"
                           id="taskLink" name="text" placeholder="Task link (asana/helpdesk/etc)"/>
                    @error('name') <span class="error">{{ $message }}</span> @enderror
                </td>
                <td>
                    <input type="text" class="form-control" wire:model="hours"
                           id="hours" name="text"/>
                    @error('hours') <span class="error">{{ $message }}</span> @enderror
                </td>
                <td>
                    <input type="text" class="form-control" wire:model="hourly_rate"
                           id="hourly_rate" name="text"/>
                    @error('hourly_rate') <span class="error">{{ $message }}</span> @enderror
                </td>
                <td>
                    <input type="text" class="form-control" wire:model="total_row"
                           id="total_row" name="text"/>
                    @error('total_row') <span class="error">{{ $message }}</span> @enderror
                </td>
                <td class="text-center">
                    <input type="checkbox" wire:model="done"
                           id="done" name="text"/>
                    @error('done') <span class="error">{{ $message }}</span> @enderror
                </td>
                <td class="text-center">
                    <input type="checkbox" wire:model="include"
                           id="include" name="text"/>
                    @error('include') <span class="error">{{ $message }}</span> @enderror
                </td>
                <td>

                </td>
                <td>
                    <button type="submit" class="btn btn-primary w-100" wire:click="add()">
                        ADD
                    </button>
                </td>
            </tr>
            <tr>
                <td colspan="8" class="tx-right"><b>Total:</b></td>
                <td class="tx-center">
                    {{ \App\Label::formatPrice($total) }}
                </td>
            </tr>
            <tr>
                <td colspan="8" class="tx-right"><b>Average profit:</b></td>
                <td class="tx-center">
                    {!! round($income->reports->average('profit'), 2) !!}%
                </td>
            </tr>
            </tfoot>
        </table>
    </div>
