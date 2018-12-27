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

/* Работа с клиентами*/

    /* Работа с заявками, зашедшими с формы */
        /* Просмотр всех заявок, зашедших с формы */
        Route::get('/view_appointments', 'ClientAppointmentsController@show_client_appointments')->middleware('can:admin_rights');

        /* Одобрение заявки*/
        Route::post('/approve_appointment', 'ClientAppointmentsController@approve_appointment')->middleware('can:admin_rights');


/* Работа с сотрудниками */

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

    /* Страница начислений по сотруднику */
    Route::get('/supervisor/employee_finances/credit/{employee_id}', 'EmployeesAdminController@employee_credit_page')->middleware('can:admin_rights');

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
    Route::get('admin/view_client/{client_id}', 'Clients_Admin_Controller@view_client')->name('admin_view_client')->middleware('can:admin_rights');


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
    Route::get('/admin/cars_in_service/add_note_to_car/{car_id}', 'Cars_in_service_Admin_Controller@add_note_to_car_page');

        /* Добавить примечание к машине : POST */
        Route::post('admin/cars_in_service/add_note_to_car', 'Cars_in_service_Admin_Controller@add_note_to_car_post');

        /* Удалить примечание */
        Route::get('admin/cars_in_service/delete_note/{note_id}', 'Cars_in_service_Admin_Controller@delete_note');

    /* История машины */
    // ...
    
/****** Наряды: Администратор ******/
Route::get('/admin/assignments_index', 'Assignments_Admin_Controller@assignments_index')->middleware('can:admin_rights');

    /* Добавление наряда на изначально выбранную машину : страница */
    Route::get('admin/assignments/add/{car_id}', 'Assignments_Admin_Controller@add_assignment_page')->middleware('can:admin_rights');


/****** РАБОТНИК : секция ******/
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
    // ...

    /* История смен */
    // ...


/****** КЛИЕНТ : секция ******/
// Route::get('/client/dashboard', ... );