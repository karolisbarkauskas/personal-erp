<div>
    <div class="form-group col-md-6">
        <label for="name">Task name / ID *</label>
        <input type="text" class="form-control" id="task" name="task" wire:model="task"/>
        <button type="submit" class="btn btn-primary" wire:click="assign()">Assign</button>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card mg-b-10">
                <div class="card-body">
                    <table class="table table-dashboard mg-b-0 table-hover table-striped">
                        <thead>
                            <tr>
                                <th>Task ID</th>
                                <th class="text-center">Client time sold</th>
                                <th class="text-center">Client time used</th>
                                <td></td>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($project->tasks as $task)
                            <livewire:resource-task-line :task="$task" :project="$project" :wire:key="$task->id">
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
