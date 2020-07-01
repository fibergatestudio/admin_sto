@extends('layouts.limitless')

@section('page_name')
    Форма добавления клиента
    {{-- Вернуться к списку клиентов --}}
     <a href="{{ url('admin/clients/clients_index') }}" title="К списку клиентов">
        <div class="btn btn-danger">
            Вернуться
        </div>

    </a>
@endsection

@section('content')
    <div class="card card-p">
        <form  action="{{ url('admin/clients/add_client') }}" method="POST">
        @csrf
        <!-- <div class="form-group">
            <label>Имя клиента или название организации (Old)</label>
            <input class="form-control" type="text" name="general_name">
        </div> -->

            <div class="form-group">
                <label>Выбрать реферала</label>
                <!-- <input class="form-control" type="name" name="name" required> -->
                <select id="service_id" class="form-control" name="referral_id">
                    <option value="">-- Выберите --</option>
                    @foreach ($clients as $client)
                        <option value="{{ $client->id }}">№{{ $client->id }} {{ $client->fio }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Имя</label>
                <input class="form-control" type="name" name="name" required>
            </div>
            <div class="form-group">
                <label>Фамилия</label>
                <input class="form-control" type="text" name="surname" required>
            </div>

            <div class="form-group">
                <label>Организация</label>
                <input id="org" class="form-control" type="text" name="organization">
            </div>
            <div class="form-group">
                <label>Телефон</label>
                <input id="phone" class="form-control" type="number" name="phone" required>
            </div>

            <div class="form-group">
                <label>Скидка</label>
                <input class="form-control" type="text" name="discount" value="0">
            </div>

            <!-- <div class="form-group">
                <label>Реферал?</label>
                <div class="row">
                    <div class="p-1">
                        <input type="radio" id="da" name="referral" value="yes">
                        <label for="da">Да</label>
                    </div>
            
                    <div class="p-1">
                        <input type="radio" id="net" name="referral" value="no" checked>
                        <label for="net">Нет</label>
                    </div>
                </div>
            </div> -->

            <div class="form-group">
                <label>Примечание</label>
                <input class="form-control" type="text" name="client_note">
            </div>

            <button type="submit" class="px-5 btn btn-primary">
                Добавить
            </button>
            <p class="mt-3">* После добавления клиента можно будет добавить машину клиента</p>

        </form>
    </div>


<script>

$('#service_id').on('change',function(){
    var fio = $(this).children('option:selected').data('fio');
    $('#fio').val(fio);
    var org = $(this).children('option:selected').data('org');
    $('#org').val(org);
    var phone = $(this).children('option:selected').data('phone');
    $('#phone').val(phone);
});

</script>

@endsection