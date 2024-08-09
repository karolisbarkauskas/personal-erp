<tr>
    <td class="w-25">
        <select name="sale_id" id="sale_id" class="form-control" wire:model.defer="sale">
            <option value="">-- Select -- </option>
            @foreach($sales as $sls)
                <option value="{{ $sls->id }}" @if($sale == $sls->id) selected @endif>
                    {{ $sls->task }} {{ $sls->client->name }}
                </option>
            @endforeach
        </select>
    </td>
    <td>
        <button class="btn" wire:click="updateRow()">
            💾
        </button>
    </td>
    <td>
        {{ $record->group_name }}
    </td>
    <td>
        {{ $record->item }}
    </td>
    <td class="text-left">
        {{ $record->item_summary }}
    </td>
    <td>
        {{ $record->estimation }}
    </td>
    <td>
        {{ $record->decimalToTime($record->spent_time/60) }}
    </td>
</tr>
