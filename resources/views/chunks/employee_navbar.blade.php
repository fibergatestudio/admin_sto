<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand" href="{{ url('/') }}">CRM Автосервиса</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNavDropdown">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" href="{{ url('employee/my_assignments') }}">
            Мои наряды
        </a>
      </li>
      
      <li class="nav-item active">
        <a class="nav-link" href="{{ url('/logout') }}"
          onclick="event.preventDefault();
          document.getElementById('logout-form').submit();">
            Выход
        </a>
      </li> 

      {{-- Стандартный логаут в ларавел осуществляется через POST форму --}}
      <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
        {{ csrf_field() }}
      </form>

      <li class="nav-item">
        <!-- <a class="nav-link" href="#">Features</a> -->
      </li>
      <li class="nav-item">
        <!-- <a class="nav-link" href="#">Pricing</a> -->
      </li>
      
    </ul>
  </div>
</nav>