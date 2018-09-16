<?php

namespace App\Http\Controllers;

use App\Model\Song;

/**
 * Class HomeController
 * @package App\Http\Controllers
 */
class HomeController extends Controller
{
    /**
     * Displaying Home Page
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('home.index');
    }

    /**
     * Displaying Form for saving a Party
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showPartyForm()
    {
        return view('party.index');
    }

    public function test()
    {
        $songs = Song::inRandomOrder()->get();
        $total = 2.5 * 60;
        $duration = 0;
        $array = [];
        $previous_party_song = 'Natural Blues';
        $start = $songs[0]->track == $previous_party_song ? 1 : 0;
        $array = $this->createPlaylist($start, $songs, $previous_party_song, $duration, $total, $array);
        return view('home.test', ['songs' => $array['songs'], 'duration' => $array['duration']]);
    }

    public function createPlaylist($start, $songs, $previous_party_song, $duration, $total, $array)
    {
        for ($i = $start; $i < count($songs); $i++) {
            if ($duration <= $total) {
                $duration += $songs[$i]->length;
                if ($duration > $total) {
                    break;
                }
                $array[] = $songs[$i];
            }
        }
        if ($duration < $total) {
            return $this->createPlaylist($start = 0, $songs, $previous_party_song, $duration, $total, $array);
        }
        return ['songs' => $array, 'duration' => $duration];
    }
}
