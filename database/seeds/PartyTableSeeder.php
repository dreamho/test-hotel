<?php

use Illuminate\Database\Seeder;

/**
 * Class PartyTableSeeder
 */
class PartyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $party = new \App\Model\Party();
        $party->name = 'Opening night';
        $party->description = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer maximus suscipit finibus. Interdum et malesuada fames ac ante ipsum primis in faucibus.';
        $party->date = '2018-7-20';
        $party->tags = 'party,hotel,fun';
        $party->capacity = 200;
        $party->length = 2.5;
        $party->image = '1529062033.jpg';
        $party->user_id = 2;
        $party->save();
        $party->songs()->attach(1);
        $party->songs()->attach(2);
        $party->songs()->attach(3);
        $party->songs()->attach(4);
        $party->songs()->attach(5);
        $party->songs()->attach(6);

        $party = new \App\Model\Party();
        $party->name = 'Karaoke night';
        $party->description = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer maximus suscipit finibus. Interdum et malesuada fames ac ante ipsum primis in faucibus.';
        $party->date = '2018-7-21';
        $party->tags = 'party,hotel,fun';
        $party->capacity = 200;
        $party->length = 2.5;
        $party->image = '1529061918.jpg';
        $party->user_id = 2;
        $party->save();
        $party->songs()->attach(3);
        $party->songs()->attach(5);
        $party->songs()->attach(1);
        $party->songs()->attach(6);
        $party->songs()->attach(2);
        $party->songs()->attach(4);
    }
}
