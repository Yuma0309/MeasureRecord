<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Record; 

class RecordsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 保存する値を指定するやり方
        $param = [
            'user_id' => 1,
            'title_id' => 17,
            'date' => '2022-12-21',
            'amount' => 180.0,
            'comment' => 'ダミーデータ１',
        ];
        $records = new Record;
        $records->fill($param)->save();

        $param = [
            'user_id' => 1,
            'title_id' => 17,
            'date' => '2022-12-22',
            'amount' => 190.0,
            'comment' => 'ダミーデータ２',
        ];
        $records = new Record;
        $records->fill($param)->save();

        // Fakerを使うやり方
        Record::factory()->count(10)->create();
    }
}
