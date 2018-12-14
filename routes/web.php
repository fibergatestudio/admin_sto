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
    return redirect('dashboard_admin');
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
Route::get('/dashboard_admin', 'DashboardController@dashboard_index')->name('dashboard_admin');

/* Работа с клиентами*/

    /* Работа с заявками, зашедшими с формы */
        /* Просмотр всех заявок, зашедших с формы */
        Route::get('/view_appointments', 'ClientAppointmentsController@show_client_appointments');

        /* Одобрение заявки*/
        Route::post('/approve_appointment', 'ClientAppointmentsController@approve_appointment');


/* Работа с сотрудниками */

    /* Отобразить список сотрудников и переход к другим разделам */
    Route::get('/view_employees', 'EmployeesAdminController@view_employees')->name('view_employees');

    /* Страница добавления нового сотрудника */
    Route::get('/add_employee', 'EmployeesAdminController@add_employee');

        /* Страница обработки запроса на добавление нового сотрудника */
        Route::post('/add_employee', 'EmployeesAdminController@add_employee_post');

    /* Страница управления статусом сотрудника*/
    Route::get('/supervisor/manage_employee_status/{employee_id}', 'EmployeesAdminController@manage_employee_status');
        /* Действие архивация сотрудника (условное "увольнение") */
        Route::post('/archive_employee', 'EmployeesAdminController@archive_employee');
    
    /* Страница архива сотрудников */
    Route::get('/admin/employee_archive', 'EmployeesAdminController@show_employee_archive');        

    /* Страница финансов сотрудника */
    Route::get('/supervisor/employee_finances/{employee_id}', 'EmployeesAdminController@employee_finances')->name('employee_finances_admin');

    /* Страница начислений по сотруднику */
    Route::get('/supervisor/employee_finances/credit/{employee_id}', 'EmployeesAdminController@employee_credit_page');

    /* Страница штрафов сотрудника */
    Route::get('/supervisor/employee_fines/{employee_id}', 'EmployeesAdminController@view_employee_fines')->name('employee_fines');

        /* Применить штраф */
        Route::get('/supervisor/employee_fines/apply_fine/{fine_id}', 'EmployeesAdminController@apply_fine')->name('apply_fine');

        /* Отменить штраф */
        Route::get('/supervisor/employee_fines/quash_fine/{fine_id}', 'EmployeesAdminController@quash_fine')->name('quash_fine');

        /* Добавить штраф вручную */
        Route::post('/supervisor/employee_fines/add_fine_manually', 'EmployeesAdminController@add_fine_manually');
    
    /* Жетоны на кофе */
    Route::get('/supervisor/employee_coffee_tokens/{employee_id}', 'EmployeesAdminController@employee_coffee_token_index');

        /* Выдать жетоны */
        Route::post('/supervisor/employee_coffee_tokens/add',
                    'EmployeesAdminController@employee_coffee_token_issue');




/****** Клиенты: Администратор ******/
    Route::get('admin/clients_index', 'Clients_Admin_Controller@clients_index');

    /* Добавить клиента: страница */
    Route::get('admin/add_client', 'Clients_Admin_Controller@add_client_page');
    
        /* Добавить клиента: POST запрос */
        Route::post('admin/add_client', 'Clients_Admin_Controller@add_client_post');

    /* Просмотр клиента: страница */
    Route::get('admin/view_client/{client_id}', 'Clients_Admin_Controller@view_client')->name('admin_view_client');


/****** Машины на обслуживании: Администратор ******/
    
    /* Страница всех машин на сервисе */
    Route::get('admin/cars_in_service/index', 'Cars_in_service_Admin_Controller@index');

    /* Добавление машины : страница */
    Route::get('admin/cars_in_service/add/{client_id?}', 'Cars_in_service_Admin_Controller@add_car');

    /* Добавление машины : POST */
    Route::post('admin/cars_in_service/add', 'Cars_in_service_Admin_Controller@add_car_post');

    /* Одна машина : страница */
    Route::get('admin/cars_in_service/view/{car_id}', 'Cars_in_service_Admin_Controller@single_car_view');


    
/****** Наряды: Администратор ******/
Route::get('/admin/assignments_index', 'Assignments_Admin_Controller@assignments_index');

    /* Добавление наряда на изначально выбранную машину : страница */
    Route::get('admin/assignments/add/{car_id}', 'Assignments_Admin_Controller@add_assignment_page');