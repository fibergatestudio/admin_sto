<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

    <title>Список заявок</title>
  </head>
  <body>
    <div class="container">
        
       <table class="table">
       @foreach($client_appointments as $client_appointment)
          <tr>
            {{-- Имя клиента --}}
            <td>{{ $client_appointment->client_name }}</td>
            
            {{-- Марка авто клиента --}}
            <td>{{ $client_appointment->client_car_mark }}</td>

            {{-- Модель авто клиента --}}
            <td>{{ $client_appointment->client_car_model }}</td>

            {{-- Год производства --}}
            <td>{{ $client_appointment->client_car_year_of_production }}</td>

            {{-- Выпадающее меню с выбором времени --}}
            <form method="POST" action="{{ url('approve_appointment') }}">
            @csrf
            <input type="hidden" name="appointment_id" value="{{ $client_appointment->id }}">
            <td>
              <div class="form-group">
                <select class="form-control" name="time_for_appointment">
                  {{-- !! СДЕЛАТЬ ВЫВОД ОПЦИЙ ВРЕМЕНИ --}}
                  <option value="09:00:00">09:00</option>
                  <option value="09:30:00">09:30</option>
                  <option value="10:00:00">10:00</option>
                  <option value="10:30:00">10:30</option>
                  <option value="11:00:00">11:00</option>
                  <option value="11:30:00">11:30</option>
                  <option value="12:00:00">12:00</option>
                  <option value="12:30:00">12:30</option>
                  <option value="13:00:00">13:00</option>
                  <option value="13:30:00">13:30</option>
                  <option value="14:00:00">14:00</option>
                  <option value="14:30:00">14:30</option>
                  <option value="15:00:00">15:00</option>
                  <option value="15:30:00">15:30</option>
                  <option value="16:00:00">16:00</option>
                  <option value="16:30:00">16:30</option>
                  <option value="17:00:00">17:00</option>
                  <option value="17:30:00">17:30</option>
                  <option value="18:00:00">18:00</option>
                  <option value="18:30:00">18:30</option>
                  <option value="19:00:00">19:00</option>
                  <option value="19:30:00">19:30</option>
                  <option value="20:00:00">20:00</option>

                </select>
              </div>
            </td>

            {{-- Кнопка "сохранить, эта заявка проработана и одобрена " --}}
            <td>
              <button type="sumbit" class="btn btn-primary">
                Сохранить
              </button>
            </td>
            </form>
          
          
          </tr>
       @endforeach
       </table>

    </div><!-- / CONTAINER -->

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
  </body>
</html>