<div>
    <table class="table table-striped table-hover">
        <tbody>
            @foreach($depts as $dept)
                <livewire:expenses.dept :dept="$dept" :wire:key="'dept-' .$dept->id" >
            @endforeach
            <tr>
                <td>
                    Total:
                </td>
                <td>
                    {{ \App\Label::formatPrice($depts->sum('size')) }}
                </td>
            </tr>
        </tbody>
    </table>
</div>
