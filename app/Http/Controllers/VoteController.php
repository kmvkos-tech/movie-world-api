<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\Vote;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VoteController extends Controller
{


    /**
     * Creates a vote
     *
     * @param Request $request
     * @param int $movieId
     * @return JsonResponse
     */
    public function vote(Request $request, int $movieId) : JsonResponse
    {
        $request->validate(['vote_type' => 'required|in:like,hate']);

        $movie = Movie::findOrFail($movieId);

        if ($movie->user_id == auth()->id()) {
            return response()->json(['allowed' => false,'message' => 'Cannot vote on your own movie'], 403);
        }

       //if the vote_type is the same as the previous vote. call the retractVote method
        $previousVote = Vote::where('movie_id', $movieId)->where('user_id', auth()->id())->first();
        if ($previousVote && $previousVote->vote_type == $request->vote_type)
        {
             $this->retractVote($movieId);
             $movie = Movie::withCount(['likes', 'hates'])->find($movieId);
             return response()->json(['allowed' => true,'movie' => $movie , 'message' => 'Vote retracted successfully.'], 201);
        }
         Vote::updateOrCreate(
            ['movie_id' => $movieId, 'user_id' => auth()->id()],
            ['vote_type' => $request->vote_type]
        );

        $movie = Movie::withCount(['likes', 'hates'])->find($movieId);

        return response()->json(['allowed' => true, 'movie' => $movie ,  'message' => 'Vote registered successfully.'], 201);
    }



    /**
     * Retract a vote
     *
     * @param int $movieId
     * @return bool
     */
    private function retractVote(int $movieId) : bool
    {
        $vote = Vote::where('movie_id', $movieId)->where('user_id', auth()->id())->firstOrFail();
        $vote->delete();

        return true;
    }
}
