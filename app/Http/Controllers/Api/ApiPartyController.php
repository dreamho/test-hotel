<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 12.6.18.
 * Time: 09.28
 */

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\EditParty;
use App\Http\Requests\SaveParty;
use App\Model\Song;
use Illuminate\Http\JsonResponse;
use App\Model\Party;
use App\Model\User;
use App\Http\Resources\Party as PartyResource;
use Illuminate\Support\Facades\DB;

/**
 * Class ApiPartyController
 * @package App\Http\Controllers\Api
 */
class ApiPartyController extends Controller
{
    /**
     * Saving new Party
     *
     * @param SaveParty $request
     * @return PartyResource|JsonResponse
     */
    public function saveParty(SaveParty $request)
    {
        $previous_party = Party::latest()->first();
        $previous_party_song = $previous_party ? $previous_party->songs()->latest()->first() : null;

        try {
            $imageName = time() . '.' . $request->image->getClientOriginalExtension();
            $request->image->move(public_path('/images'), $imageName);
            $party = new Party();
            $party->name = $request->name;
            $party->description = $request->description;
            $party->date = $request->date;
            $party->tags = $request->tags;
            $party->capacity = $request->capacity;
            $party->length = $request->length;
            $party->image = $imageName;
            $party->user_id = $request->user()->id;
            $party->save();

            $songs = Song::inRandomOrder()->get();
            $total = $party->length * 60;
            $duration = 0;
            $array = [];
            if (isset($previous_party_song)) {
                $start = ($songs[0]->track == $previous_party_song->track) ? 1 : 0;
            } else {
                $start = 0;
            }
            $song_list = $this->createPlaylist($start, $songs, $duration, $total, $array);

            foreach ($song_list as $song) {
                $party->songs()->attach($song->id);
            }
            return new PartyResource($party);
        } catch (\Exception $exception) {
            return new JsonResponse("Something went wrong", 400);
        }
    }

    /**
     * Get list of parties based on optional parameter date
     *
     * @param $date
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getParties($date = null)
    {
        if (!isset($date)) {
            return PartyResource::collection(Party::all());
        }
        $current_date = date('Y-m-d');
        $parties = Party::where('date', '>=', $current_date)->orderBy('date', 'asc')->get();
        return PartyResource::collection($parties);
    }

    /**
     * Creating list of songs for the party
     *
     * @param int $start
     * @param Song $songs
     * @param float $duration
     * @param float $total
     * @param $array
     * @param string $last_song
     * @return array
     */
    public function createPlaylist($start, $songs, $duration, $total, $array, $last_song = "")
    {
        for ($i = $start; $i < count($songs); $i++) {
            if (($last_song != "") && ($i == 0) && ($last_song->track == $songs[$i]->track)) {
                continue;
            }
            if ($duration <= $total) {
                $duration += $songs[$i]->length;
                if ($duration > $total) {
                    break;
                }
                $array[] = $songs[$i];
            }
        }
        if ($duration < $total) {
            $last_song = $array[count($array) - 1];
            return $this->createPlaylist($start = 0, $songs, $duration, $total, $array, $last_song);
        }
        return $array;
    }

    /**
     * Update party description, tags and image
     *
     * @param int $id
     * @param EditParty $request
     * @return PartyResource|JsonResponse
     */
    public function updateParty($id, EditParty $request)
    {
        try {
            $party = Party::find($id);
            $party->description = $request->description;
            $party->tags = $request->tags;
            if (isset($request->image)) {
                if (file_exists('images/' . $party->image)) {
                    unlink('images/' . $party->image);
                }
                $imageName = time() . '.' . $request->image->getClientOriginalExtension();
                $request->image->move(public_path('/images'), $imageName);
                $party->image = $imageName;
            }
            $party->user_id = $request->user()->id;
            $party->save();
            return new PartyResource($party);
        } catch (\Exception $exception) {
            return new JsonResponse("Something went wrong", 400);
        }
    }

    /**
     * Deleting a party by id and deleting all belonged songs in pivot table party_song
     *
     * @param int $id
     * @return JsonResponse
     */
    public function deleteParty($id)
    {
        try {
            $party = Party::find($id);
            $party->songs()->detach();
            $party->delete();
            return new JsonResponse($id);
        } catch (\Exception $exception) {
            return new JsonResponse("Something went wrong", 400);
        }
    }

    /**
     * Joining logged user to the chosen party
     *
     * @param int $id
     * @return PartyResource
     */
    public function joinParty($id)
    {
        $party = Party::find($id);
        $user = auth()->user();
        $party->users()->attach($user->id);
        return new PartyResource($party);
    }

    /**
     * Starting a party and joining users to the predefined party playlist
     *
     * @param int $id
     * @return PartyResource|JsonResponse
     */
    public function startParty($id)
    {
        $party = Party::find($id);
        $songs = $party->songs;
        $users = $party->users;
        $band = User::find(5);
        foreach ($party->songs as $song) {
            $party->songs()->updateExistingPivot($song->id, ['user_id' => $band->id]);
        }
        try {
            for ($i=0; $i < count($users); $i++) {
                $performed_songs = $this->getArrayOfIds($users[$i]->songs);
                for ($j=0; $j < count($songs); $j++) {
                    if ($songs[$j]->id == null) {
                        continue;
                    }
                    if (in_array($songs[$j]->id, $performed_songs)) {
                        continue;
                    }
                    DB::table('party_song')
                        ->where('party_id', $party->id)
                        ->where('song_id', $songs[$j]->id)
                        ->limit(1)
                        ->update(['user_id' => $users[$i]->id]);
                    $songs[$j]->id = null;
                    break;
                }
            }
            $party->started = true;
            $party->save();
            return new PartyResource($party);
        } catch (\Exception $exception) {
            return new JsonResponse("Something went wrong", 400);
        }
    }

    /**
     * Getting id's from song objects array and creating a new array
     *
     * @param Song $songs
     * @return array
     */
    public function getArrayOfIds($songs)
    {
        $array = [];
        foreach ($songs as $song) {
            $array[] = $song->id;
        }
        return $array;
    }
}
