
				<!-- User menu -->
				<div class="sidebar-user">
					<div class="card-body">
						<div class="media">


							<div class="media-body">
								<div class="media-title font-weight-semibold">
                                    {{ Auth::user()->general_name }}
                                </div>
								<div class="font-size-xs opacity-50">

								</div>
							</div>


						</div>
					</div>
				</div>
				<!-- /user menu -->


				<!-- Main navigation -->
				<div class="card card-sidebar-mobile">
					<ul class="nav nav-sidebar" data-nav-type="accordion">

						<!-- Main -->
						<li class="nav-item-header"><div class="text-uppercase font-size-xs line-height-xs">Меню</div> <i class="icon-menu" title="Main"></i></li>



						<li class="nav-item nav-item-submenu">
							<a href="#" class="nav-link legitRipple"><i class="icon-copy"></i> <span>Записи</span></a>

							<ul class="nav nav-group-sub" data-submenu-title="Layouts" style="display: none;">
								<li class="nav-item"><a href="{{ url('records') }}" class="nav-link legitRipple">Все записи</a></li>
								<li class="nav-item"><a href="{{ url('confirmed_records') }}" class="nav-link legitRipple">Подтвержденные записи</a></li>
								</ul>
						</li>



                        <li class="nav-item">
							<a href="{{ url('/supervisor/view_employees') }}" class="nav-link {{ request()->is('supervisor/*') ? 'active' : ''}}">

								<i class="icon-profile"></i>
								<span>
									Сотрудники
								</span>
							</a>
						</li>

						<li class="nav-item">
							<a href="{{ url('/admin/shifts') }}" class="nav-link {{ request()->is('admin/shifts') ? 'active' : ''}}">

								<i class="icon-profile"></i>
								<span>
									Смены Сотрудников
								</span>
							</a>
						</li>

                                                <li class="nav-item">
							<a href="{{ url('admin/workzones/index') }}" class="nav-link {{ request()->is('admin/workzones/*') ? 'active' : ''}}">
								<i class="icon-grid5"></i>
								<span>
									Рабочие зоны
								</span>
							</a>
						</li>

                                                <li class="nav-item">
							<a href="{{ url('admin/assignments/assignments_index') }}" class="nav-link {{ request()->is('admin/assignments/*') ? 'active' : ''}}">
								<i class="icon-list3"></i>
								<span>
									Наряды
								</span>
							</a>
						</li>

                                                <li class="nav-item">
							<a href="{{ url('admin/clients/clients_index') }}" class="nav-link {{ request()->is('admin/clients/*') ? 'active' : ''}}">
								<i class="icon-users2"></i>
								<span>
									Клиенты
								</span>
							</a>
						</li>

                                                <li class="nav-item">
							<a href="{{ url('admin/cars_in_service/index') }}" class="nav-link {{ request()->is('admin/cars_in_service/*') ? 'active' : ''}}">
								<i class="icon-wrench"></i>
								<span>
									Машины в сервисе
								</span>
							</a>
						</li>

						<li class="nav-item">
							<a href="{{ url('/admin/supply_orders/index') }}" class="nav-link {{ request()->is('admin/supply_orders/*') ? 'active' : ''}}">
								<i class="icon-book3"></i>
								<span>
									Заказы на снабжение
								</span>
							</a>
						</li>

						<li class="nav-item">
							<a href="{{ url('/admin/finances/index') }}" class="nav-link {{ request()->is('admin/finances/*') ? 'active' : ''}}">
								<i class="icon-book3"></i>
								<span>
									Финансы
								</span>
							</a>
						</li>
						{{-- Модели Машин --}}
						<li class="nav-item">
							<a href="{{ url('/admin/cars/index') }}" class="nav-link {{ request()->is('admin/cars/*') ? 'active' : ''}}">
								<i class="icon-car"></i>
								<span>
									Модели Машин
								</span>
							</a>
						</li>

						<li class="nav-item">
							<a href="{{ url('/admin/statistics_analysis/') }}" class="nav-link {{ request()->is('admin/statistics_analysis') ? 'active' : ''}}">
								<i class="icon-graph"></i>
								<span>
									Статистика и Анализ
								</span>
							</a>
						</li>

						<li class="nav-item">
							<a href="{{ url('/admin/profitability/profitability_index/') }}" class="nav-link {{ request()->is('admin/profitability/*') ? 'active' : ''}}">
								<i class="icon-calendar3"></i>
								<span>
									Расчет рентабельности и курс валют
								</span>
							</a>
						</li>

						<li class="nav-item">
							<a href="{{ url('/delivery-passages') }}" class="nav-link {{ request()->is('delivery-passages') ? 'active' : ''}}">
								<i class="icon-man-woman"></i>
								<span>
									Проходы через систему контроля
								</span>
							</a>
						</li>

						<li class="nav-item">
							<a href="{{ url('/admin/access/all_users') }}" class="nav-link {{ request()->is('admin/access/*') ? 'active' : ''}}">
								<i class="icon-book3"></i>
								<span>
									Управление доступом
								</span>
							</a>
						</li>

						<li class="nav-item">
							<a href="{{ url('/admin/notification') }}" class="nav-link {{ request()->is('admin/notification') ? 'active' : ''}}">
								<i class="icon-circle"></i>
								<span>
									Настройка уведомлений
								</span>
							</a>
						</li>

						<li class="nav-item nav-item-submenu">
							<a href="#" class="nav-link legitRipple"><i class="icon-copy"></i> <span>Логирование</span></a>

							<ul class="nav nav-group-sub" data-submenu-title="Layouts" style="display: none;">
								<li class="nav-item"><a href="{{ url('/admin/employees_logs') }}" class="nav-link legitRipple">Сотрудники</a></li>
								<li class="nav-item"><a href="{{ url('/admin/clients_logs') }}" class="nav-link legitRipple">Клиенты</a></li>
								<li class="nav-item"><a href="{{ url('/admin/cars_in_service_logs') }}" class="nav-link legitRipple">Машины в сервисе</a></li>
								<li class="nav-item"><a href="{{ url('/admin/finances_logs') }}" class="nav-link legitRipple">Финансы</a></li>
								</ul>
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
						<hr>
						<!-- Тест телеграма -->
						<li class="nav-item-header"><div class="text-uppercase font-size-xs line-height-xs">Телеграм</div> <i class="icon-menu" title="Main"></i></li>

						<li class="nav-item">
							<a href="{{ url('/send-message') }}" class="nav-link {{ request()->is('send-message') ? 'active' : ''}}">
								<span>
									Отправить сообщение
								</span>
							</a>
						</li>

						<li class="nav-item">
							<a href="{{ url('/send-photo') }}" class="nav-link {{ request()->is('send-photo') ? 'active' : ''}}">
								<span>
									Отправить фото
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
