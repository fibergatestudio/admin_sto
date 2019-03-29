<!-- User menu -->
<!-- Main navigation -->
<div class="card card-sidebar-mobile">
	<ul class="nav nav-sidebar" data-nav-type="accordion">
		<!-- Main -->
		<li class="nav-item-header"><div class="text-uppercase font-size-xs line-height-xs">Меню</div> <i class="icon-menu" title="Main"></i></li>

		<li class="nav-item">
                    <a  href="{{ url('master/assignments/list') }}" class="nav-link {{ request()->is('master/assignments/*') ? 'active' : ''}}">
			<i class="icon-list"></i>
                        <span>
                            Управление нарядами
                        </span>
                    </a>
		</li>

		<li class="nav-item">
                    <a href="{{ url('master/employees/profiles') }}" class="nav-link {{ request()->is('master/employees/*') ? 'active' : ''}}">
			<i class="icon-list"></i>
			<span>
                            Просмотр профилей
			</span>
                    </a>
		</li>

		<li class="nav-item">
                    <a href="{{ url('master/confirm') }}" class="nav-link {{ request()->is('master/confirm') ? 'active' : ''}}">
			<i class="icon-list"></i>
			<span>
                            Подтвержденные записи
			</span>
                    </a>
		</li>

		<li class="nav-item">
			<a class="nav-link" href="{{ url('/logout') }}"
			   onclick="event.preventDefault();
           document.getElementById('logout-form').submit();">
				<i class="icon-exit2"></i>
				<span>
				Выход
	      		</span>
			</a>
		</li>
		{{-- Стандартный логаут в ларавел осуществляется через POST форму --}}
		<form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
			{{ csrf_field() }}
		</form>
		<!-- /page kits -->
	</ul>
</div>
<!-- /main navigation -->
