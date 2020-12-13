<?php

namespace Database\Seeders;

use App\Models\Grade;
use App\Models\GradeFocusOn;
use Illuminate\Database\Seeder;

class GradesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $grade1 = Grade::create([
            'chinese_number' => '一',
            'english_number' => '1',
            'text_color' => 'black',
            'tshirt_color' => 'white',
            'info' => 'INFO TO BE ADDED BY SOMEONE'
        ]);

        array_map(function ($point) use ($grade1) {
            $grade1->learningPoints()->save(GradeFocusOn::create([
                'point' => $point,
                'grade_id' => $grade1->id,
            ]));
        }, [
            'Movement',
            'Don\'t be fast in Chain Punch, Practice the Form'
        ]);


        Grade::create([
            'chinese_number' => '二',
            'english_number' => '2',
            'text_color' => 'black',
            'tshirt_color' => 'white',
            'info' => 'INFO TO BE ADDED BY MOHAMED'
        ]);

        Grade::create([
            'chinese_number' => '三',
            'english_number' => '3',
            'text_color' => 'black',
            'tshirt_color' => 'white',
            'info' => 'INFO TO BE ADDED BY MOHAMED'
        ]);

        Grade::create([
            'chinese_number' => '四',
            'english_number' => '4',
            'text_color' => 'black',
            'tshirt_color' => 'white',
            'info' => 'INFO TO BE ADDED BY MOHAMED'
        ]);

        Grade::create([
            'chinese_number' => '五',
            'english_number' => '5',
            'text_color' => 'black',
            'tshirt_color' => 'gray-300',
            'info' => 'INFO TO BE ADDED BY MOHAMED'
        ]);

        Grade::create([
            'chinese_number' => '6',
            'english_number' => '六',
            'text_color' => 'black',
            'tshirt_color' => 'gray-300',
            'info' => 'INFO TO BE ADDED BY MOHAMED'
        ]);

        Grade::create([
            'chinese_number' => '七',
            'english_number' => '7',
            'text_color' => 'black',
            'tshirt_color' => 'gray-300',
            'info' => 'INFO TO BE ADDED BY MOHAMED'
        ]);

        $grade8 = Grade::create([
            'chinese_number' => '八',
            'english_number' => '8',
            'text_color' => 'black',
            'tshirt_color' => 'gray-300',
            'info' => 'This Grade sometimes takes from 1 to 2 months to finish'
        ]);

        array_map(function ($point) use ($grade8) {
            $grade8->learningPoints()->save(GradeFocusOn::create([
                'point' => $point,
                'grade_id' => $grade8->id,
            ]));
        }, [
            'Move from one leg to the other',
            'Switch legs quickly',
            'Shifting',
            'Chain Punch quickly',
            'Elbows Form',
            'Stretching your legs'
        ]);

        Grade::create([
            'chinese_number' => '九',
            'english_number' => '9',
            'text_color' => 'white',
            'tshirt_color' => 'blue-900',
            'info' => 'INFO TO BE ADDED BY MOHAMED'
        ]);

        Grade::create([
            'chinese_number' => '十',
            'english_number' => '10',
            'text_color' => 'white',
            'tshirt_color' => 'blue-900',
            'info' => 'INFO TO BE ADDED BY MOHAMED'
        ]);

        Grade::create([
            'chinese_number' => '十一',
            'english_number' => '11',
            'text_color' => 'white',
            'tshirt_color' => 'blue-900',
            'info' => 'INFO TO BE ADDED BY MOHAMED'
        ]);

        Grade::create([
            'chinese_number' => '十二',
            'english_number' => '12',
            'text_color' => 'white',
            'tshirt_color' => 'blue-900',
            'info' => 'INFO TO BE ADDED BY MOHAMED'
        ]);
    }
}
