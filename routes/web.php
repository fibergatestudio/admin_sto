<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    /* return view('welcome'); */
    return redirect('login');
});

/* Стандартная авторизация ларавела */
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

/*
********** Общедоступные (публичные) пути **********
*/

    /* Страница с формой записи + пути для обработки данных из формы */
    Route::get('/make_appointment', 'PublicFrontController@show_make_appointment_form');
    Route::post('/make_appointment', 'PublicFrontController@appointment_form_post_processing');


/*
********** АДМИНИСТРАТОР: секция **********
*/

/* Общая доска */
Route::get('/dashboard_admin', 'DashboardController@dashboard_index')->name('dashboard_admin')->middleware('can:admin_rights');

/***** Работа с клиентами *****/

    /* Работа с заявками, зашедшими с формы */
        /* Просмотр всех заявок, зашедших с формы */
        Route::get('/view_appointments', 'ClientAppointmentsController@show_client_appointments')->middleware('can:admin_rights');

        /* Одобрение заявки*/
        Route::post('/approve_appointment', 'ClientAppointmentsController@approve_appointment')->middleware('can:admin_rights');


/***** Работа с сотрудниками *****/

    /* Отобразить список сотрудников и переход к другим разделам */
    Route::get('/view_employees', 'EmployeesAdminController@view_employees')->name('view_employees')->middleware('can:admin_rights');

    /* Страница добавления нового сотрудника */
    Route::get('/add_employee', 'EmployeesAdminController@add_employee')->middleware('can:admin_rights');

        /* Страница обработки запроса на добавление нового сотрудника */
        Route::post('/add_employee', 'EmployeesAdminController@add_employee_post')->middleware('can:admin_rights');

    /* Страница управления статусом сотрудника*/
    Route::get('/supervisor/manage_employee_status/{employee_id}', 'EmployeesAdminController@manage_employee_status');
        /* Действие архивация сотрудника (условное "увольнение") */
        Route::post('/archive_employee', 'EmployeesAdminController@archive_employee')->middleware('can:admin_rights');
    
    /* Страница архива сотрудников */
    Route::get('/admin/employee_archive', 'EmployeesAdminController@show_employee_archive')->middleware('can:admin_rights');

    /* Страница финансов сотрудника */
    Route::get('/supervisor/employee_finances/{employee_id}', 'EmployeesAdminController@employee_finances')->name('employee_finances_admin')->middleware('can:admin_rights');

        /* Изменить ставку сотруднику : POST */
        Route::post('/admin/employee_finances/change_standard_shift_wage', 'EmployeesAdminController@change_standard_shift_wage');

    /* --- Страница начислений по сотруднику --- */
    Route::get('/supervisor/employee_finances/credit/{employee_id}', 'EmployeesAdminController@employee_credit_page')->middleware('can:admin_rights');

    /* - Страница начислений по сотруднику : POST - */
    	Route::post('/supervisor/employee_finances/credit/add_payment_manualy', 'EmployeesAdminController@add_employee_payment_manualy')->middleware('can:admin_rights');

    /* Страница штрафов сотрудника */
    Route::get('/supervisor/employee_fines/{employee_id}', 'EmployeesAdminController@view_employee_fines')->name('employee_fines')->middleware('can:admin_rights');

        /* Применить штраф */
        Route::get('/supervisor/employee_fines/apply_fine/{fine_id}', 'EmployeesAdminController@apply_fine')->name('apply_fine')->middleware('can:admin_rights');

        /* Отменить штраф */
        Route::get('/supervisor/employee_fines/quash_fine/{fine_id}', 'EmployeesAdminController@quash_fine')->name('quash_fine')->middleware('can:admin_rights');

        /* Добавить штраф вручную */
        Route::post('/supervisor/employee_fines/add_fine_manually', 'EmployeesAdminController@add_fine_manually')->middleware('can:admin_rights')->middleware('can:admin_rights');
    
    /* Жетоны на кофе */
    Route::get('/supervisor/employee_coffee_tokens/{employee_id}', 'EmployeesAdminController@employee_coffee_token_index')->middleware('can:admin_rights');

        /* Выдать жетоны */
        Route::post('/supervisor/employee_coffee_tokens/add',
                    'EmployeesAdminController@employee_coffee_token_issue')->middleware('can:admin_rights');


        /* - Добавление примечания к сотруднику: страница - */
        Route::get('/admin/employee/add_note_to_employee/{employee_id}', 'EmployeesAdminController@add_note_to_employee_page')->middleware('can:admin_rights');

        /* - Добавление примечания к сотруднику: POST  - */
        Route::post('/admin/employee/add_note_to_employee', 'EmployeesAdminController@add_note_to_employee_post')->middleware('can:admin_rights');

        /* - Страница всех примечаний сотрудника - */
        Route::get('/admin/employee/{employee_id}', 'EmployeesAdminController@single_employee_notes')->middleware('can:admin_rights');

        /* -- Редактировать примечание к сотруднику : страница -- */
        Route::get('/admin/employee/edit_note/{note_id}', 'EmployeesAdminController@edit_employee_note')->middleware('can:admin_rights');

        /* -- Редактировать примечание сотрудника : POST --*/
        Route::post('admin/employee/edit_employee_note', 'EmployeesAdminController@edit_employee_note_post')->middleware('can:admin_rights');

        /* - Удаление примечания к сотруднику - */
        Route::get('/admin/employee/delete_employee_note/{note_id}', 'EmployeesAdminController@delete_employee_note')->middleware('can:admin_rights');


/****** Рабочие зоны: Администратор ******/

    /* Просмотр рабочих зон */
    Route::get('admin/workzones/index', 'Workzones_Admin_Controller@index')->middleware('can:admin_rights');

    /* Добавление рабочей зоны : страница */
    Route::get('admin/workzones/add', 'Workzones_Admin_Controller@add_workzone')->middleware('can:admin_rights');

        /* Добавление рабочей зоны : действие */
        Route::post('admin/workzones/add', 'Workzones_Admin_Controller@add_workzone_post')->middleware('can:admin_rights');

    /* Изменение рабочей зоны */
    Route::get('admin/workzones/edit/{workzone_id}', 'Workzones_Admin_Controller@edit_workzone')->middleware('can:admin_rights');

    


/****** Клиенты: Администратор ******/
    Route::get('admin/clients_index', 'Clients_Admin_Controller@clients_index')->middleware('can:admin_rights');

    /* Добавить клиента: страница */
    Route::get('admin/add_client', 'Clients_Admin_Controller@add_client_page')->middleware('can:admin_rights');
    
        /* Добавить клиента: POST запрос */
        Route::post('admin/add_client', 'Clients_Admin_Controller@add_client_post')->middleware('can:admin_rights'); 

    /* Просмотр клиента: страница */
    Route::get('admin/view_client/{client_id}', 'Clients_Admin_Controller@single_client_view')->name('admin_view_client')->middleware('can:admin_rights');

    
    /* Добавить примечание о клиенте : страница */
    Route::get('/admin/clients/add_note_to_client/{client_id}', 'Clients_Admin_Controller@add_note_to_client_page');

        /* Добавить примечание к клиенту : POST */
        Route::post('admin/clients/add_note_to_client', 'Clients_Admin_Controller@add_note_to_client_post');

    /* - Редактирование примечания о клиенте : страница - */
    Route::get('/admin/clients/edit_client_note/{note_id}', 'Clients_Admin_Controller@edit_client_note')->middleware('can:admin_rights');

    	/* - Редактирование примечания о клиенте : POST - */
    	Route::post('/admin/clients/edit_client_note', 'Clients_Admin_Controller@edit_client_note_post')->middleware('can:admin_rights');

        /* Один клиент : страница */
    Route::get('admin/clietns/view/{client_id}', 'Clients_Admin_Controller@single_client_view')->middleware('can:admin_rights');

        /* Удалить примечание */
        Route::get('admin/client/delete_client_note/{note_id}', 'Clients_Admin_Controller@delete_client_note');
    /**/    
    



/****** Машины на обслуживании: Администратор ******/
    
    /* Страница всех машин на сервисе */
    Route::get('admin/cars_in_service/index', 'Cars_in_service_Admin_Controller@index')->middleware('can:admin_rights');

    /* Добавление машины : страница */
    Route::get('admin/cars_in_service/add/{client_id?}', 'Cars_in_service_Admin_Controller@add_car')->middleware('can:admin_rights');

        /* Добавление машины : POST */
        Route::post('admin/cars_in_service/add', 'Cars_in_service_Admin_Controller@add_car_post')->middleware('can:admin_rights');

    /* Одна машина : страница */
    Route::get('admin/cars_in_service/view/{car_id}', 'Cars_in_service_Admin_Controller@single_car_view')->middleware('can:admin_rights');

    /* Добавить примечание к машине : страница */
    Route::get('/admin/cars_in_service/add_note_to_car/{car_id}', 'Cars_in_service_Admin_Controller@add_note_to_car_page')->middleware('can:admin_rights');;

        /* - Добавить примечание к машине : POST - */
        Route::post('admin/cars_in_service/add_note_to_car', 'Cars_in_service_Admin_Controller@add_note_to_car_post')->middleware('can:admin_rights');;

        /* - Редактировать примечание к машине : страница - */
        Route::get('admin/cars_in_service/edit_note_to_car/{note_id}', 'Cars_in_service_Admin_Controller@edit_note_to_car')->middleware('can:admin_rights');;

        /* - Редактировать примечание к машине : POST - */
        Route::post('admin/cars_in_service/edit_note_to_car', 'Cars_in_service_Admin_Controller@edit_note_to_car_post')->middleware('can:admin_rights');; 

        /* -  Удалить примечание - */
        Route::get('admin/cars_in_service/delete_note/{note_id}', 'Cars_in_service_Admin_Controller@delete_note')->middleware('can:admin_rights');;

    /* История машины */
    // ...
    
/****** Наряды: Администратор ******/
Route::get('/admin/assignments_index', 'Assignments_Admin_Controller@assignments_index')->middleware('can:admin_rights');

    /* Добавление наряда на изначально выбранную машину : Страница */
    Route::get('/admin/assignments/add/{car_id}', 'Assignments_Admin_Controller@add_assignment_page')->middleware('can:admin_rights');

        /* Добавление наряда на изначально выбранную машину : POST */
        Route::post('/admin/assignments/add', 'Assignments_Admin_Controller@add_assignment_page_post');

    /* Просмотр наряда : страница */
    Route::get('admin/assignments/view/{assignment_id}', 'Assignments_Admin_Controller@view_assignment');

    /* Добавление зонального наряда : страница */
    Route::get('admin/assignments/add_sub_assignment/{assignment_id}', 'Assignments_Admin_Controller@add_sub_assignment_page');

        /* Добавление зонального наряда : POST */
        Route::post('admin/assignments/add_sub_assignment', 'Assignments_Admin_Controller@add_sub_assignment_post');

    /* Загрузка фотографии в CRM : Страница */
    Route::get('/admin/assignments/{assignment_id}/add_photo_page', 'Assignments_Admin_Controller@add_photo_to_assignment_page');

        /* Загрузка фотографий в CRM : Post */
        Route::post('/admin/assignments/add_photo_to_assignment', 'Assignments_Admin_Controller@add_photo_to_assignment_post');

/****** Финансы : Администратор ******/
Route::get('/admin/finances/index', 'Finances_Admin_Controller@finances_index')->middleware('can:admin_rights');


/****** Supply orders : Администратор ******/

/* Главная страница */
Route::get('/admin/supply_orders/index', 'Supply_orders_Admin_Controller@supply_orders_index')->middleware('can:admin_rights');

/* Добавить заказ */
Route::get('/admin/supply_orders/new', 'Supply_orders_Admin_Controller@new_supply_order')->middleware('can:admin_rights');

    /* Добавить заказ : POST */
    Route::post('/admin/supply_orders/new', 'Supply_orders_Admin_Controller@new_supply_order_post')->middleware('can:admin_rights');

/* Управление заказом : Страница */
Route::get('/admin/supply_orders/manage/{supply_order_id}', 'Supply_orders_Admin_Controller@manage_supply_order');

    /* Архивировать заказ */
    Route::get('/admin/supply_orders/archive/{supply_order_id}', 'Supply_orders_Admin_Controller@archive_supply_order');

/* Архив заказов */
Route::get('/admin/supply_orders/archive', 'Supply_orders_Admin_Controller@archive_index');

    /* Удалить заказ (доступно только в архиве) */
    Route::get('/admin/supply_orders/archive/delete/{order_id}', 'Supply_orders_Admin_Controller@delete_archived_order');

/********** РАБОТНИК : секция **********/
Route::get('/employee/dashboard', 'Employee_Dashboard_Controller@index');

    /* Мои наряды */
    Route::get('/employee/my_assignments', 'Employee_Dashboard_Controller@my_assignments');

    /* Страница управления нарядом */
    Route::get('/employee/manage_assignment/{assignment_id}', 'Employee_Dashboard_Controller@manage_assignment');

    /* Архив моих нарядов */
    Route::get('/employee/my_assignments_archive', 'Employee_Dashboard_Controller@my_assignment_archive');

/**** Смены : работник ****/

    /* Страница смены (сегодня) */
    Route::get('/employee/shifts/index', 'Employee_Dashboard_Controller@shifts_index');

    /* Открыть смену */
    Route::get('/employee/shifts/start', 'Employee_Dashboard_Controller@start_shift');

    /* Закрыть смену */
    Route::post('/employee/shifts/end/', 'Employee_Dashboard_Controller@end_shift');

    /* История смен */
    // ...


/****** КЛИЕНТ : секция ******/
// Route::get('/client/dashboard', ... );
