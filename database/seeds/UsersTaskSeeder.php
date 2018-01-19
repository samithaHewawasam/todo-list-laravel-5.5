<?php
namespace Database;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    private $user_id;

    public function run(int $user_id)
    {

      $this->user_id = $user_id;

      DB::transaction(function () {

        for($i = 0; $i < 10000; $i++){

          $id = DB::table('tasks')->insertGetId([
              'name' => str_random(10),
              'dueDate' => date("y-m-d")
          ]);

          DB::table('users_tasks')->insert([
              'user_id' => $this->user_id,
              'task_id' => $id
          ]);
        }
      });
    }
}
