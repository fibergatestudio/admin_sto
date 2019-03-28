

				<!-- User menu -->
				<div class="sidebar-user">
					<div class="card-body">
						<div class="media">


							<div class="media-body">
								<div class="media-title font-weight-semibold">
                                    {{ Auth::user()->general_name }}
                                </div>
								<div class="font-size-xs opacity-50">
								<!-- Вывод баланса аккаунта -->
									Баланс: {{ Auth::user()->getBalance() }}
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


						<li class="nav-item">
							<a  href="{{ url('employee/employee_profile') }}" class="nav-link {{ request()->is('employee/employee_profile') ? 'active' : ''}}">
							<i class="icon-person"></i>
							<span>
								Мой профиль
							</span>
							</a>
                                                </li>

						<li class="nav-item">
							<a  href="{{ url('employee/assignments/my_assignments') }}" class="nav-link {{ request()->is('employee/assignments/*') ? 'active' : ''}}">
							<i class="icon-list"></i>
							<span>
								Мои наряды
							</span>
							</a>
                                                </li>


						<li class="nav-item">
							<a  href="{{ url('employee/my_completed_assignments') }}" class="nav-link {{ request()->is('employee/my_completed_assignments') ? 'active' : ''}}">
							<i class="icon-check"></i>
								<span>
									Выполненые наряды
								</span>
							</a>
						</li>
						<li class="nav-item">
							<a  href="{{ url('employee/my_uncompleted_assignments') }}" class="nav-link {{ request()->is('employee/my_uncompleted_assignments') ? 'active' : ''}}">
							<i class="icon-fire"></i>
								<span>
									Невыполненые наряды
								</span>
							</a>
						</li>


						<li class="nav-item">
							<a href="{{ url('employee/shifts/index') }}" class="nav-link {{ request()->is('employee/shifts/index') ? 'active' : ''}}">
							<i class="icon-book"></i>
							<span>
								Мои cмены
							</span>
							</a>
						</li>
								<!-- Заказы работника -->
						<li class="nav-item">
							<a href="{{ url('/employee/orders/index') }}" class="nav-link {{ request()->is('employee/orders/*') ? 'active' : ''}}" >
							<i class="icon-color-sampler"></i>
							<span>
								Мои заказы
							</span>								
							</a>
                        </li>

						<li class="nav-item">
							<a href="{{ url('employee/finance/finance_history') }}" class="nav-link {{ request()->is('employee/finance/*') ? 'active' : ''}}" >
							<i class="icon-history"></i>
							<span>
								История финансов
							</span>
							</a>
      					</li>

						
						  <li class="nav-item">
							<a href="{{ url('/employee/notification') }}" class="nav-link {{ request()->is('employee/notification') ? 'active' : ''}}">
								<i class="icon-circle"></i>
								<span>
									Настройка уведомлений
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
