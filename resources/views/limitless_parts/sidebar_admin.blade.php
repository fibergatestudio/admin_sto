
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
					

                        <li class="nav-item">
							<a href="{{ url('view_employees') }}" class="nav-link {{ request()->is('view_employees') ? 'active' : ''}}">

								<i class="icon-profile"></i>
								<span>
									Сотрудники
								</span>
							</a>
						</li>

                        <li class="nav-item">
							<a href="{{ url('admin/workzones/index') }}" class="nav-link">
								<i class="icon-grid5"></i>
								<span>
									Рабочие зоны
								</span>
							</a>
						</li>

                        <li class="nav-item">
							<a href="{{ url('admin/assignments_index') }}" class="nav-link">
								<i class="icon-list3"></i>
								<span>
									Наряды
								</span>
							</a>
						</li>

                        <li class="nav-item">
							<a href="{{ url('admin/clients_index') }}" class="nav-link">
								<i class="icon-users2"></i>
								<span>
									Клиенты
								</span>
							</a>
						</li>

                        <li class="nav-item">
							<a href="{{ url('admin/cars_in_service/index') }}" class="nav-link">
								<i class="icon-wrench"></i>
								<span>
									Машины в сервисе
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
