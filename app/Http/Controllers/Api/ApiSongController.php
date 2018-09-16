<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 6.6.18.
 * Time: 14.22
 */

namespace App\Http\Controllers\Api;

use App\Http\Requests\SaveEditSong;
use App\Http\Resources\Song as SongResource;
use App\Http\Controllers\Controller;
use App\Model\Song as SongModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Class ApiSongController
 * @package App\Http\Controllers\Api
 */
class ApiSongController extends Controller
{

    /**
     * Return songs depending on status of logged user
     * If is admin returns all songs, if is regular user returns only his songs
     * @param Request $request
     * @return
     */
    public function getSongs()
    {
        $songs = SongModel::all();
        return SongResource::collection($songs);
    }

    /**
     * Save new song
     * @param SaveEditSong $request
     * @return SongResource|JsonResponse
     */
    public function saveSong(SaveEditSong $request)
    {
        try {
            $song = new SongModel;
            $song->artist = $request->artist;
            $song->track = $request->track;
            $song->link = $request->link;
            $song->length = $request->length;
            $song->save();
            return new SongResource($song);
        } catch (\Exception $exception) {
            return new JsonResponse("Something went wrong", 400);
        }
    }

    /**
     * Updating song
     * @param SaveEditSong $request
     * @return SongResource|JsonResponse
     */
    public function updateSong($id, SaveEditSong $request)
    {
        try {
            $song = SongModel::find($id);
            $song->artist = $request->artist;
            $song->track = $request->track;
            $song->link = $request->link;
            $song->length = $request->length;
            $song->save();
            return new SongResource($song);
        } catch (\Exception $exception) {
            return new JsonResponse("Something went wrong", 400);
        }
    }

    /**
     * Delete a song by id
     * @param $id
     * @return JsonResponse
     */
    public function deleteSong($id)
    {
        try {
            SongModel::destroy($id);
            return new JsonResponse($id);
        } catch (\Exception $exception) {
            return new JsonResponse("Something went wrong", 400);
        }
    }
}
