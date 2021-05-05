<?php

namespace Database\Seeders;

use App\Models\Car;
use App\Models\CarFirm;
use App\Models\CarModel;
use App\Models\Customer;
use App\Models\Material;
use App\Models\Post;
use App\Models\User;
use App\Models\Work;
use App\Models\Worker;
use App\Models\WorkHasPost;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class TemplateDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $customers=[
            [
                'first_name'=>'Сергей',
                'last_name'=>'Сергеев',
                'father_name'=>'Сергеевич',
                'phone'=>89997771221
            ],
            [
                'first_name'=>'Алексей',
                'last_name'=>'Потапов',
                'father_name'=>'Олегович',
                'phone'=>89999999999
            ],
            [
                'first_name'=>'Тагир',
                'last_name'=>'Моргенов',
                'father_name'=>'Алишерович',
                'phone'=>89996666666
            ],
        ];
        $firms = [
            'Toyota',
            'Mazda',
            'Ferrari'
        ];
        $models=[
            [
                'firm_id'=>1,
                'name'=>'Camry 3.5 V6 GR Sport Auto',
                'year_release'=>'2021'
            ],
            [
                'firm_id'=>1,
                'name'=>'Sprinter marino',
                'year_release'=>'1993'
            ],
            [
                'firm_id'=>2,
                'name'=>'Mazda6',
                'year_release'=>'2021'
            ],
            [
                'firm_id'=>3,
                'name'=>'812 Superfast',
                'year_release'=>'2021'
            ],
        ];
        $cars=[
            [
                'model_id'=>1,
                'customer_id'=>1,
                'state_number'=>'С315НА38'
            ],
            [
                'model_id'=>2,
                'customer_id'=>1,
                'state_number'=>'Х719ЛН38'
            ],
            [
                'model_id'=>3,
                'customer_id'=>2,
                'state_number'=>'М111АС38'
            ],
            [
                'model_id'=>4,
                'customer_id'=>3,
                'state_number'=>'Т666МА38'
            ],
        ];
        $posts =[
            [
                'name'=>'Стажёр',
                'salary'=>5000
            ],
            [
                'name'=>'Главный специалист',
                'salary'=>15000
            ],
        ];
        $workers = [
            [
              'post_id'=>2,
              'first_name'=>'Иван',
              'last_name'=>'Иванов',
              'father_name'=>'Иванович',
              'phone'=>89649991812
            ],
            [
                'post_id'=>1,
                'first_name'=>'Павел',
                'last_name'=>'Иванов',
                'father_name'=>'Иванович',
                'phone'=>89649991842
            ],
            [
                'post_id'=>1,
                'first_name'=>'Алексей',
                'last_name'=>'Иванов',
                'father_name'=>'Иванович',
                'phone'=>89649991843
            ],
        ];
        $works=[
            [
                'name'=>'Заменить свечу зажигания',
                'price'=>700
            ],
            [
                'name'=>'Залить масло',
                'price'=>500
            ],
            [
                'name'=>'Замена двигателя',
                'price'=>50000
            ],
        ];
        $workHasPosts=[
            [
                'post_id'=>1,
                'work_id'=>1
            ],
            [
                'post_id'=>1,
                'work_id'=>2
            ],
            [
                'post_id'=>2,
                'work_id'=>1
            ],
            [
                'post_id'=>2,
                'work_id'=>2
            ],
            [
                'post_id'=>2,
                'work_id'=>3
            ],
        ];
        $materials=[
            [
                'name'=>'Свеча зажигания NGK SP-153',
                'price'=>260,
                'count'=>100
            ],
            [
                'name'=>'Моторное масло 5W-30 AP',
                'price'=>1800,
                'count'=>100
            ],
            [
                'name'=>'Двигатель Toyota 3,5 2GR',
                'price'=>124000,
                'count'=>0
            ],
        ];
        $roleHasPermissions=[
            '9'=>9,10,11,
            '14'=>14,15,16,
            '19'=>19,
            '24'=>24,
            '28'=>28,29,30,
            '33'=>33,34,
            '37'=>37,38,
            '41'=>41
        ];
        foreach ($customers as $customer){
            Customer::create([
                'first_name'=>$customer['first_name'],
                'last_name'=>$customer['last_name'],
                'father_name'=>$customer['father_name'],
                'phone'=>$customer['phone']
            ]);
        }
        foreach ($firms as $firm){
            CarFirm::create([
                'name'=>$firm
            ]);
        }
        foreach ($models as $model){
            CarModel::create([
                'firm_id'=>$model['firm_id'],
                'name'=>$model['name'],
                'year_release'=>$model['year_release']
            ]);
        }
        foreach ($cars as $car){
            Car::create([
                'model_id'=>$car['model_id'],
                'customer_id'=>$car['customer_id'],
                'state_number'=>$car['state_number']
            ]);
        }
        foreach ($posts as $post){
            Post::create([
                'name'=>$post['name'],
                'salary'=>$post['salary']
            ]);
        }
        foreach ($workers as $worker){
            Worker::create([
                'post_id'=>$worker['post_id'],
                'first_name'=>$worker['first_name'],
                'last_name'=>$worker['last_name'],
                'father_name'=>$worker['father_name'],
                'phone'=>$worker['phone']
            ]);
        }
        foreach ($works as $work){
            Work::create([
                'name'=>$work['name'],
                'price'=>$work['price']
            ]);
        }
        foreach ($workHasPosts as $workHasPost){
            WorkHasPost::create([
                'post_id'=>$workHasPost['post_id'],
                'work_id'=>$workHasPost['work_id']
            ]);
        }
        foreach ($materials as $material){
            Material::create([
                'name'=>$material['name'],
                'price'=>$material['price'],
                'count'=>$material['count'],
            ]);
        }
        $user =User::create([
            'name' => 'ivanovII',
            'email' => 'ivanovII@mail.com',
            'password' => bcrypt('qweqwe123')
        ]);
        $role = Role::create(['name' => 'cashier']);
        $role->syncPermissions($roleHasPermissions);
        $user->assignRole([$role->id]);
    }
}
