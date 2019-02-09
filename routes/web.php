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

/* Путь с редиректами по ролям */
Route::get('/home', 'HomeController@index')->name('home');

/****************************************/
/***** Общедоступные (публичные) пути ***/
/****************************************/

    /* Страница с формой записи + пути для обработки данных из формы */
    Route::get('/make_appointment', 'PublicFrontController@show_make_appointment_form');
    Route::post('/make_appointment', 'PublicFrontController@appointment_form_post_processing');


/****************************************/
/********** АДМИНИСТРАТОР: секция *******/
/****************************************/

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

    /* Страница начислений по сотруднику */
    Route::get('/supervisor/employee_finances/credit/{employee_id}', 'EmployeesAdminController@employee_credit_page')->middleware('can:admin_rights');

    /* Страница начислений по сотруднику /POST */
    Route::post('/supervisor/employee_finances/credit', 'EmployeesAdminController@employee_credit_page_post')->middleware('can:admin_rights');

        /* Отображение истории начислений*/
        Route::get('/supervisor/employee_finances/credit', 'EmployeesAdminController@index')->middleware('can:admin_rights');

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

    /* Страница добавления документов сотрудника*/
    Route::get('/add_documents/{employee_id}', 'EmployeesAdminController@add_documents')->middleware('can:admin_rights');

        // Добавление документов POST
        Route::post('/add_documents_post/', 'EmployeesAdminController@add_documents_post')->middleware('can:admin_rights');
        
    // Страница сотрудника с его документами
    Route::get('/documents/{employee_id}', 'EmployeesAdminController@show_employee_documents')->middleware('can:admin_rights');
    
    // Страница удаления документов
    Route::get('/documents_delete/{employee_id}', 'EmployeesAdminController@documents_delete')->middleware('can:admin_rights');
    
    // Удаление документов сотрудника POST
    Route::post('/documents_delete_post/', 'EmployeesAdminController@documents_delete_post')->middleware('can:admin_rights');

    // Управление доступом
    Route::get('/admin/all_users', 'EmployeesAdminController@all_users')->middleware('can:admin_rights');

    Route::get('admin/change_access_rights/{employee_id}', 'EmployeesAdminController@change_access_rights')->middleware('can:admin_rights');

    Route::post('admin/change_access_rights_post/', 'EmployeesAdminController@change_access_rights_post')->middleware('can:admin_rights');


    
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

    
    /*Добавить примечание о клиенте : страница*/
    Route::get('/admin/clients/add_note_to_client/{client_id}', 'Clients_Admin_Controller@add_note_to_client_page');

        /* Добавить примечание к клиенту : POST */
        Route::post('admin/clients/add_note_to_client', 'Clients_Admin_Controller@add_note_to_client_post');

        /* Удалить примечание */
        Route::get('admin/clients/delete_client_note/{note_id}', 'Clients_Admin_Controller@delete_note');
    /**/    

        /* Живой поиск клиентов*/
        Route::get('/clients/search', 'Clients_Admin_Controller@search')->name('clients_search')->middleware('can:admin_rights');
    



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

    /* Добавление наряда на изначально выбранную машину : Страница */
    Route::get('/admin/assignments/add/{car_id}', 'Assignments_Admin_Controller@add_assignment_page')->middleware('can:admin_rights');

        /* Добавление наряда на изначально выбранную машину : POST */
        Route::post('/admin/assignments/add', 'Assignments_Admin_Controller@add_assignment_page_post');

    /* Просмотр наряда : страница */
    Route::get('admin/assignments/view/{assignment_id}', 'Assignments_Admin_Controller@view_assignment');

        /* Изменение названия наряда */
        Route::post('/admin/assignments/change_name', 'Assignments_Admin_Controller@change_assignment_name');

    /* Добавление зонального наряда : страница */
    Route::get('admin/assignments/add_sub_assignment/{assignment_id}', 'Assignments_Admin_Controller@add_sub_assignment_page');

        /* Добавление зонального наряда : POST */
        Route::post('admin/assignments/add_sub_assignment', 'Assignments_Admin_Controller@add_sub_assignment_post');

    /* Загрузка фотографии в CRM : Страница */
    Route::get('/admin/assignments/{assignment_id}/add_photo_page', 'Assignments_Admin_Controller@add_photo_to_assignment_page');

        /* Загрузка фотографий в CRM : Post */
        Route::post('/admin/assignments/add_photo_to_assignment', 'Assignments_Admin_Controller@add_photo_to_assignment_post');

    /* Удаление фотографий : Страница */
    Route::get('/admin/assignments/{assignment_id}/delete_photos_page', 'Assignments_Admin_Controller@delete_photos_page');

        /* Удаление фотографий : POST */
        Route::post('/admin/assignments/delete_photo_from_assignment', 'Assignments_Admin_Controller@delete_photos_post');

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
Route::get('/admin/supply_orders/manage/{supply_order_id}', 'Supply_orders_Admin_Controller@manage_supply_order')->middleware('can:admin_rights');

/* Редактирование заказа : Страница */
Route::get('/admin/supply_orders/edit/{supply_order_id}', 'Supply_orders_Admin_Controller@edit_supply_order')->middleware('can:admin_rights');

/* Редактирование заказа : POST */
Route::post('/admin/supply_orders/edit_post/{supply_order_id}', 'Supply_orders_Admin_Controller@edit_supply_order_post')->middleware('can:admin_rights');

/*Заказы для подтверждения (статус - worker)*/
Route::get('/admin/supply_orders/worker', 'Supply_orders_Admin_Controller@supply_orders_worker_index')->middleware('can:admin_rights');

/*Подтверждение заказа (статус изменяется на - active )*/
Route::get('/admin/supply_orders/confirm/{supply_order_id}', 'Supply_orders_Admin_Controller@confirm_supply_order')->middleware('can:admin_rights');
   
/* Архивировать заказ */
    Route::get('/admin/supply_orders/archive/{supply_order_id}', 'Supply_orders_Admin_Controller@archive_supply_order');

/* Архив заказов */
Route::get('/admin/supply_orders/archive', 'Supply_orders_Admin_Controller@archive_index');

    /* Удалить заказ (доступно только в архиве) */
    Route::get('/admin/supply_orders/archive/delete/{order_id}', 'Supply_orders_Admin_Controller@delete_archived_order');

/****************************************/
/********** РАБОТНИК : секция **********/
/****************************************/
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
    
/**** Заказы : работник *****/   
    
    /* Мои заказы */
    Route::get('/employee/orders/index', 'Employee_Dashboard_Controller@employee_orders_index');
    
    /* Новый заказ : страница  */
    Route::get('/employee/order/new', 'Employee_Dashboard_Controller@employee_order_new');
    
    /* Новый заказ : POST  */
    Route::post('/employee/order/new_post', 'Employee_Dashboard_Controller@employee_order_new_post');
    
    /* Редакторование заказа : страница*/
    Route::get('/employee/order/edit/{supply_order_id}', 'Employee_Dashboard_Controller@employee_order_edit');
    
    /* Редактирование заказа : POST*/
     Route::post('/employee/order/edit_post/{supply_order_id}', 'Employee_Dashboard_Controller@employee_order_edit_post');
    
    /* Подтвержденные заказы */
    Route::get('/employee/orders/active', 'Employee_Dashboard_Controller@employee_orders_active_index');
    
    /* Завершенные заказы */
    Route::get('/employee/orders/completed', 'Employee_Dashboard_Controller@employee_orders_completed_index');

/****************************************/
/********** СНАБЖЕНЕЦ : секция **********/
/****************************************/
    
    /* Главная страница */
    Route::get('/supply_officer/index', 'Supply_officer_Controller@index')->middleware('can:supply_officer_rights');

    /* Активные заказы : список */
    Route::get('/supply_officer/all_orders', 'Supply_officer_Controller@all_orders')->middleware('can:supply_officer_rights');

    /* Выполненные заказы : список */
    Route::get('/supply_officer/completed_orders', 'Supply_officer_Controller@completed_orders')->middleware('can:supply_officer_rights');

    /* Страница одного заказа : просмотр */
    Route::get('/supply_officer/view_order/{order_id}', 'Supply_officer_Controller@view_order')->middleware('can:supply_officer_rights');

    /* Заказ выполнен : POST */
    Route::post('/supply_officer/order_completed_action', 'Supply_officer_Controller@order_completed_action')->middleware('can:supply_officer_rights');

/****************************************/
/*********** КЛИЕНТ : секция ************/
/****************************************/
// Route::get('/client/dashboard', ... );