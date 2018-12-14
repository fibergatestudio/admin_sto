<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand" href="#">CRM Автосервиса</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNavDropdown">
    <ul class="navbar-nav">
      <li class="nav-item active">
        <a class="nav-link" href="{{ url('view_employees') }}">
            Сотрудники
        </a>
      </li>

      <li class="nav-item active">
        <a class="nav-link" href="{{ url('admin/assignments_index') }}">
            Наряды
        </a>
      </li>

      <li class="nav-item active">
        <a class="nav-link" href="{{ url('admin/clients_index') }}">
            Клиенты
        </a>
      </li>
      
      <li class="nav-item active">
        <a class="nav-link" href="{{ url('admin/cars_in_service/index') }}">
            Машины в сервисе
        </a>
      </li>


      <li class="nav-item">
        <!-- <a class="nav-link" href="#">Features</a> -->
      </li>
      <li class="nav-item">
        <!-- <a class="nav-link" href="#">Pricing</a> -->
      </li>
      
    </ul>
  </div>
</nav>