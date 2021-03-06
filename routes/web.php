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

/*Путь к клиенту*/
Route::get('/client', 'Client_Controller@client')->middleware('can:client_rights');

/*Путь к нарядам клиента*/
Route::get('/client/assignments/{id}', 'Client_Controller@assignments')->middleware('can:client_rights');

/*Путь к зональным нарядам клиента*/
Route::get('/client/sub_assignments/{id}', 'Client_Controller@sub_assignments')->middleware('can:client_rights');

/*Путь к архивным нарядам клиента*/
Route::get('/client/assignments_archive/{id}', 'Client_Controller@assignments_archive')->middleware('can:client_rights');

/*Путь к мастеру*/
Route::get('/master', 'Client_Controller@master')->middleware('can:master_rights');

/*Путь к управлению нарядами мастером*/
Route::get('/master/assignments', 'Client_Controller@master_assignments')->middleware('can:master_rights');


/*Путь к  наряду на странице мастера*/
Route::get('/master/assignments/view/{id}', 'Client_Controller@master_view_assignment')->middleware('can:master_rights');

/*Путь к профилям рабочих для просмотра мастером*/
Route::get('/master/employees', 'Client_Controller@master_employees')->middleware('can:master_rights');

/*Путь к профилям рабочих для просмотра финансов сотрудника*/
Route::get('/master/employee_finances/{id}', 'Client_Controller@employee_finances')->middleware('can:master_rights');

/*Деавторизация*/
Route::get('/logout', '\App\Http\Controllers\Auth\LoginController@logout');
/*Деавторизация*/
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


/***** Записи *****/

    /* Страница записей */
    Route::get('/records', 'RecordsController@records_index')->middleware('can:admin_rights');
        /* Добавить запись */
        Route::post('/add_record', 'RecordsController@add_record')->middleware('can:admin_rights');
        /* Подтвердить запись */
        Route::get('/complete_record/{record_id}', 'RecordsController@complete_record')->middleware('can:admin_rights');

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

        /* Редактирование сотрудника */
        Route::get('/supervisor/manage_employee_status/{employee_id}/employee_edit', 'EmployeesAdminController@employee_edit');

            /* Применить изменения сотрудника */
            Route::post('/supervisor/manage_employee_status/{employee_id}/employee_edit/apply_employee_edit', 'EmployeesAdminController@apply_employee_edit');

    /* Страница архива сотрудников */
    Route::get('/admin/employee_archive', 'EmployeesAdminController@show_employee_archive')->middleware('can:admin_rights');

    /* Страница финансов сотрудника */
    Route::get('/supervisor/employee_finances/{employee_id}', 'EmployeesAdminController@employee_finances')->name('employee_finances_admin')->middleware('can:admin_rights');

        /* Изменить ставку сотруднику : POST */
        Route::post('/admin/employee_finances/change_standard_shift_wage', 'EmployeesAdminController@change_standard_shift_wage');

    /* --- Страница начислений по сотруднику --- */
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

       /* Изменение рабочей зоны */
    Route::get('admin/workzones/edit/{workzone_id}', 'Workzones_Admin_Controller@edit_workzone')->middleware('can:admin_rights');
  /* Изменение рабочей зоны */
        Route::post('/admin/workzones/edit', 'Workzones_Admin_Controller@edit_workzone_id')->middleware('can:admin_rights');
        /* удалить рабочую зону */
 Route::get('admin/workzones/delete/{workzone_id}', 'Workzones_Admin_Controller@delete_workzone')->middleware('can:admin_rights');





/****** Клиенты: Администратор ******/
    Route::get('admin/clients_index', 'Clients_Admin_Controller@clients_index')->middleware('can:admin_rights');

    /* Добавить клиента: страница */
    Route::get('admin/add_client', 'Clients_Admin_Controller@add_client_page')->middleware('can:admin_rights');

        /* Добавить клиента: POST запрос */
        Route::post('admin/add_client', 'Clients_Admin_Controller@add_client_post')->middleware('can:admin_rights');

    /* Просмотр клиента: страница */
    Route::get('admin/view_client/{client_id}', 'Clients_Admin_Controller@single_client_view')->name('admin_view_client')->middleware('can:admin_rights');


    /*Добавить примечание о клиенте : страница*/
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

        /* API для марок машин */
        Route::get('admin/cars_in_service/api_brands', 'Cars_in_service_Admin_Controller@api_brands')->middleware('can:admin_rights');

        /* API для моделей машин (подтягиваются по бренду) */
        Route::get('admin/cars_in_service/api_models/{brand}', 'Cars_in_service_Admin_Controller@api_models')->middleware('can:admin_rights');

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
    /* Обновление (перестановка) елементов таблицы */
    Route::post('admin/assignments/view/{assignment_id}', 'Assignments_Admin_Controller@updateOrder');

        /* Управление зонального наряда */
        Route::get('admin/assignments/view/{sub_assignment_id}/management', 'Assignments_Admin_Controller@assignment_management');

             /* Добавить зональную доходную часть : POST */
            Route::post('admin/assignments/view/{sub_assignment_id}/management/add_zonal_assignment_income', 'Assignments_Admin_Controller@add_zonal_assignment_income');

            /* Добавить зональную расходную часть : POST */
            Route::post('admin/assignments/view/{sub_assignment_id}/management/add_zonal_assignment_expense', 'Assignments_Admin_Controller@add_zonal_assignment_expense');

            /* Добавить зональный список выполненых работ : POST */
            Route::post('admin/assignments/view/{sub_assignment_id}/management/add_zonal_assignment_works', 'Assignments_Admin_Controller@add_zonal_assignment_works');

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
        /* Загрузка фото принятой машины в CRM : Post */
        Route::post('/admin/assignments/add_accepted_photo_to_assignment', 'Assignments_Admin_Controller@add_accepted_photo_to_assignment_post');
        /* Загрузка фото процесса ремонта в CRM : Post */
        Route::post('/admin/assignments/add_repair_photo_to_assignment', 'Assignments_Admin_Controller@add_repair_photo_to_assignment_post');
        /* Загрузка фото готовой машины в CRM : Post */
        Route::post('/admin/assignments/add_finished_photo_to_assignment', 'Assignments_Admin_Controller@add_finished_photo_to_assignment_post');

    /* Удаление фотографий : Страница */
    Route::get('/admin/assignments/{assignment_id}/delete_photos_page', 'Assignments_Admin_Controller@delete_photos_page');

        /* Удаление фотографий : POST */
        Route::post('/admin/assignments/delete_photo_from_assignment', 'Assignments_Admin_Controller@delete_photos_post');

    /* АДМИН УПРАВЛЕНИЕ НАРЯДАМИ
    /* Админ страница управления одним нарядом */
    Route::get('/admin/manage_assignment/{assignment_id}', 'Assignments_Admin_Controller@manage_assignment');

        /* Админ Добавить доходную часть : POST */
        Route::post('/admin/manage_assignment/add_income_entry', 'Assignments_Admin_Controller@add_income_post');

         /* Админ Добавить расходную часть : POST */
         Route::post('/admin/manage_assignment/add_expense_entry', 'Assignments_Admin_Controller@add_expense_post');

         /* Админ Добавить список выполненых работ : POST */
         Route::post('/admin/manage_assignment/add_works_entry', 'Assignments_Admin_Controller@add_works_post');

/****** Финансы : Администратор ******/
Route::get('/admin/finances/index', 'Finances_Admin_Controller@finances_index')->middleware('can:admin_rights');

/****** Модели машин : Администратор ******/
Route::get('/admin/cars/index', 'Cars_Admin_Controller@cars_index')->middleware('can:admin_rights');

        /* Добавить модель машины */
        Route::post('/admin/cars/add_car_entry', 'Cars_Admin_Controller@add_car_entry')->middleware('can:admin_rights');

        /* Страница редактирования модели машины */
        Route::get('/admin/cars/{car_entry_id}/car_edit', 'Cars_Admin_Controller@edit_car_entry')->middleware('can:admin_rights');

            /* Редактировать модель машины */
            Route::post('/admin/cars/{car_entry_id}/submit_car_entry', 'Cars_Admin_Controller@submit_car_entry')->middleware('can:admin_rights');

        /* Удалить модель машины */
        Route::get('/admin/cars/{car_entry_id}/delete', 'Cars_Admin_Controller@delete_car_entry')->middleware('can:admin_rights');


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


/********** Допустимые названия авто : Администратор **********/
// ... Просмотр списка

// ... Добавить авто

// ... Удалить авто

// ... Редактировать авто

/****************************************/
/********** РАБОТНИК : секция **********/
/****************************************/
Route::get('/employee/dashboard', 'Employee_Dashboard_Controller@index');

/**** Профиль ****/
    /* Мой профиль */
    Route::get('/employee/employee_profile', 'Employee_Dashboard_Controller@employee_profile');

/**** История Финансов ****/
    /* Мой профиль */
    Route::get('/employee/finance_history', 'Employee_Dashboard_Controller@finance_history');

/**** Наряды : работник ****/
    /* Мои наряды */
    Route::get('/employee/my_assignments', 'Employee_Dashboard_Controller@my_assignments');

    /* Страница управления одним нарядом */
    Route::get('/employee/manage_assignment/{assignment_id}', 'Employee_Dashboard_Controller@manage_assignment');

        /* Добавить доходную часть : POST */
        Route::post('/employee/manage_assignment/add_income_entry', 'Employee_Dashboard_Controller@add_income_post');

        /* Добавить расходную часть : POST */
        Route::post('/employee/manage_assignment/add_expense_entry', 'Employee_Dashboard_Controller@add_expense_post');

        /* Добавить список выполненых работ : POST */
        Route::post('/employee/manage_assignment/add_works_entry', 'Employee_Dashboard_Controller@add_works_post');

            /* Наряд выполнен : POST */
            Route::get('/employee/manage_assignment/{assignment_id}/assignment_complete', 'Employee_Dashboard_Controller@assignment_complete');

            /* Наряд невыполнен : POST */
            Route::get('/employee/manage_assignment/{assignment_id}/assignment_uncomplete', 'Employee_Dashboard_Controller@assignment_uncomplete');

    /* Архив моих нарядов */
    Route::get('/employee/my_assignments_archive', 'Employee_Dashboard_Controller@my_assignment_archive');

    /* Выполненые наряды */
    Route::get('/employee/my_completed_assignments', 'Employee_Dashboard_Controller@my_complete_assignments');

    /* Невыполненые наряды */
    Route::get('/employee/my_uncompleted_assignments', 'Employee_Dashboard_Controller@my_uncomplete_assignments');


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
/********** ТЕЛЕГРАМ : секция **********/
/****************************************/

    /* Телеграм */
    Route::get('/send-message', 'TelegramBotController@sendMessage');
    Route::post('/store-message', 'TelegramBotController@storeMessage');
    Route::get('/send-photo', 'TelegramBotController@sendPhoto');
    Route::post('/store-photo', 'TelegramBotController@storePhoto');
    Route::get('/updated-activity', 'TelegramBotController@updatedActivity');

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
