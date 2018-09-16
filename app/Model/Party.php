<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

/**
 * Class Party
 * @package App\Model
 */
class Party extends Model
{
    /**
     *  Party - Song (many to many relationship)
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function songs()
    {
        return $this->belongsToMany('App\Model\Song', 'party_song', 'party_id', 'song_id')->withPivot('user_id');
    }

    /**
     * Party - User (many to many relationship)
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany('App\Model\User', 'party_user', 'party_id', 'user_id');
    }

    public function isJoined(Request $request)
    {

        $user = $request->user('api');
         //$user = auth()->user();
        if($this->users()->where('user_id', $user->id)->first()){
            return true;
        }else{
            return false;
        }
    }
}
