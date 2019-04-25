<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
  public function run()
  {
    // truncate
    DB::table('users')->truncate();
    DB::table('articles')->truncate();
    DB::table('comments')->truncate();
    DB::table('tokens')->truncate();

    $json = json_decode(File::get('database/seeds/json/dev.json'), true);
    $this->insert($json);
  }

  protected function insert($array)
  {
    foreach ($array as $key => $records) {
      foreach ($records as $record) {
        DB::table($key)->insert($record + [
          'creator_id' => '00000000-0000-0000-0000-000000000000',
          'created_at' => new Carbon(),
          'updater_id' => '00000000-0000-0000-0000-000000000000',
          'updated_at' => new Carbon(),
        ]);
      }
    }
  }
}
