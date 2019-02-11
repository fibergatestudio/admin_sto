<?php
/*
* Контроллер админ версии нарядов
*/

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

use App\Employee;
use App\Client;
use App\Cars_in_service;
use App\Assignment;
use App\Sub_assignment;
use App\Workzone;

class Assignments_Admin_Controller extends Controller
{
    
    /* Отображения списка всех нарядов */
    public function assignments_index(){
        /* Получаем всю нужную информацию по нарядам */
        $assignments_data =
            DB::table('assignments')
                ->join('employees', 'assignments.responsible_employee_id', '=', 'employees.id')
                ->join('cars_in_service', 'assignments.car_id', '=', 'cars_in_service.id')
                ->select(
                        'assignments.*',
                        'employees.general_name AS employee_name',
                        'cars_in_service.general_name AS car_name'
                    )
                ->get();

        return view('assignments_admin.assignments_admin_index', ['assignments' => $assignments_data]);
    }

    /* Добавления наряда: страница с формой */
    public function add_assignment_page($car_id){
        
        $client = Client::get_client_by_car_id($car_id);
        
        /* Собираем информация по сотрудникам, которых можно указать как ответственных */
        $employees = Employee::where('status', 'active')->get();

        /* Информация о машине */
        $car = Cars_in_service::find($car_id);
        
        return view(
            'admin.assignments.add_assignment_page',
            [
                'employees' => $employees,
                'owner' => $client,
                'car' => $car
            ]
        );
    }
    
    /* Добавления наряда: страница обработки POST данных*/
    public function add_assignment_page_post(Request $request){
        /* Получаем данные из запроса */
        $responsible_employee_id = $request->responsible_employee_id;
        $assignment_description = $request->assignment_description;
        $car_id = $request->car_id;

        /* Создаём новый наряд и сохраняем его*/
        $new_assignment = new Assignment();
        $new_assignment->responsible_employee_id = $responsible_employee_id;
        $new_assignment->description = $assignment_description;
        $new_assignment->car_id = $car_id;
        $new_assignment->date_of_creation = date('Y-m-d');
        $new_assignment->status = 'active';
        $new_assignment->save();

        /* - Добавление в логи создание наряда -*/
        $create_new_assigment_log = new Assignments_logs();
        $create_new_assigment_log_entry->assignment_id = $assignment_id;  //Id наряда
        $create_new_assigment_log_entry->car_id = $car_id;

        /* - Название машины - */
        $car = Cars_in_service::find($car_id);
        $car_name = $car->general_name;
        /* - Имя ответственного сотрудника - */
        $responsible_employee = Employees::find($employee_id);
        $responsible_employee_name = $responsible_employee->general_name;
        /* - Имя автора - */
        $author = Users::find($author_id);
        $author_name = $author->general_name;

        $create_new_assigment_log_entry->text = 'Создан новый наряд  - ' .$assigment_id. 'по машине - ' .$car_name. 'автором - ' .$author_name. 'ответственный сотрудник - ' .$responsible_employee_name. 'дата - ' .date('Y-m-d'); // текст лога о создании нового наряда(номер наряда) по машине (название) автором(имя) с ответвенным сотрудником(имя), дата(date)
        $create_new_assigment_log_entry->save();
 
        /* Возвращаемся на страницу нарядов по авто */
        return redirect('admin/cars_in_service/view/'.$car_id);
    }

    /* Просмотр наряда : страница */
    public function view_assignment($assignment_id){
        /* Получаем информацию по наряду */
        $assignment = Assignment::find($assignment_id);

        //echo asset('storage/file.txt');
        //Storage::get('/app/2/owl.jpg');

        //echo "<img src='".Storage::url('/app/2/owl.jpg')."'>";

        /* Возвращаем представление */
        return view('admin.assignments.view_assignment_page', ['assignment' => $assignment]);
    }

    /* Добавление зонального наряда : страница */
    public function add_sub_assignment_page($assignment_id){
        /* Получаем данные по основному наряду */
        $assignment = Assignment::find($assignment_id);

        /* Данные по рабочим зонам */
        $workzones = Workzone::all();

        /* Данные по сотрудникам, которых можно сделать ответственными */
        $employees = Employee::all();

        /* Возвращаем страницу добавления зонального наряда */
        return view('admin.assignments.add_sub_assignment_page', [
            'assignment' => $assignment, // "Родительский" наряд
            'workzones' => $workzones, //  Рабочие зоны
            'employees' => $employees // Сотрудники
        ]);
    }

    /* Добавление зонального наряда : POST */
    public function add_sub_assignment_post(Request $request){
        
        /* Получаем данные из запроса */
        $main_assignment_id = $request->assignment_id; // ID "родительского" наряда

        /* Сохранение зонального наряда */
        // ...

        /* Возвращаемся на страницу */
        return redirect('/admin/assignments/view/'.$main_assignment_id);
    }

    /* Добавление фотографий к наряду : Страница */
    public function add_photo_to_assignment_page($assignment_id){
        /* Получаем текущий наряд, к которому будут добавляться фото */
        $assignment = Assignment::find($assignment_id);
        
        /* Отображаем представление */
        return view('admin.assignments.add_photo_to_assignment_page', ['assignment' => $assignment]);
    }

    /* Добавление фотографий к наряду : Обработка запроса */
    public function add_photo_to_assignment_post(Request $request){
        /* Получаем данные из запроса */
        /* ID наряда, к которому добавляем фото */
        $assignment_id = $request->assignment_id;

        /* Сохраняем фото */
        //print_r(Input::all()); - дебаг
        $request->test->store('public/'.$assignment_id);

        $create_photo_to_assignment_log = new Photos_to_assigments_logs();
        $create_photo_to_assignment_log->assigment_id = $assigment_id;
        $create_photo_to_assignment_log->author_id = $author_id;

        /* - Имя автора - */
        $author = Users::find($author_id);
        $author_name = $author->general_name;

        $create_photo_to_assignment_log->text = 'Добавлено фото к наряду - ' .$assignment_id. 'автором - ' .$author_id. 'дата - ' .date('Y-m-d');  // текст добавления лога о добавлении фото к наряду(номер наряда) автором(имя), дата(date)
        $create_photo_to_assignment_log->save();
        
        /* Возвращаемся на страницу авто */
        return redirect('admin/assignments/view/'.$assignment_id);
    }

}
