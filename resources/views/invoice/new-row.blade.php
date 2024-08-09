<style>
    table {
        width: 100%;
        text-align: center;
    }

    table tr:nth-child(even) {
        background: #eee;
    }
</style>

# Klientas

Sukurta nauja eilutė kliento {{ $client->name }} sąskaitos kortelėje. Reikia sutikrinti sąskaitos dydį.

# Tarpinė ataskaita


| Užduoties ID    | Valandų kiekis                |Viso EUR                    |
|----------------|-------------------------------|-----------------------------|
@foreach($reports as $value)
|@if($value->task_id)[{{ $value->task_id }}](https://invoyer.youtrack.cloud/issue/{{ $value->task_id }}) @else No task @endif | {{ $value->hours }}h| {{ \App\Label::formatPrice($value->hourly_rate * $value->hours) }} |
@endforeach


#### Tarpinė suma: {{ \App\Label::formatPrice($reports->sum('total')) }}
#### Kliento kreditas:  {{ \App\Label::formatPrice($client->credit) }}
