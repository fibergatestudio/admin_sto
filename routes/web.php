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
    return view('welcome');
});

/* Стандартная авторизация ларавела */
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

/*
* Общедоступные (публичные) пути
*/

    /* Страница с формой записи + пути для обработки данных из формы */
    Route::get('/make_appointment', 'PublicFrontController@show_make_appointment_form');
    Route::post('/make_appointment', 'PublicFrontController@appointment_form_post_processing');


/* Пути для персонала СТО */

/* Работа с клиентами*/

    /* Работа с заявками, зашедшими с формы */
        /* Просмотр всех заявок, зашедших с формы */
        Route::get('/view_appointments', 'ClientAppointmentsController@show_client_appointments');

        /* Одобрение заявки*/
        Route::post('/approve_appointment', 'ClientAppointmentsController@approve_appointment');