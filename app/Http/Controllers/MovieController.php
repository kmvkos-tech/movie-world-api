<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MovieController extends Controller
{

    /**
     * Display a listing of the Movies.
     * use the sort query parameter to sort the movies by likes, hates or publication_date
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request) : JsonResponse
    {

        $query = Movie::with(['user'])->withCount(['likes', 'hates']);

        if ($request->has('sort')) {
            $sort = $request->get('sort');
            if (in_array($sort, ['likes', 'hates', 'publication_date'])) {
                $query->withCount(['likes', 'hates'])->orderBy($sort == 'publication_date' ? 'publication_date' : "{$sort}_count", 'desc');
            }
        }

        $movies = $query->get();

        //if the user is authenticated, add property is_allowed_to_vote to the movie true or false
        if (auth('sanctum')->check()) {
            $user = auth('sanctum')->user();
            $userId = $user->id;

            foreach ($movies as $movie) {
                $movie->is_creator = $movie->user_id == $userId;
                $vote = $movie->votes()->where('user_id', $userId)->first();
                if ($vote) {
                    $movie->voted = $vote->vote_type;
                } else {
                    $movie->voted = null;
                }
            }
        }

        return response()->json($movies );
    }



    /**
     * Store a newly created Movie in db.
     * movie schema: title, description, publication_date
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request) : JsonResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'publication_date' => 'required|date',
        ]);

        $movie = Movie::create([
            'title' => $request->title,
            'description' => $request->description,
            'publication_date' => $request->publication_date,
            'user_id' => auth()->id(),
        ]);

        return response()->json($movie, 201);
    }



    /**
     * Display all Movies of a User.
     *
     * @param int $userId
     * @return JsonResponse
     */
    public function filterByUser(int $userId) : JsonResponse
    {
        $movies = Movie::where('user_id', $userId)->with('user')->withCount(['likes', 'hates'])->get();

        //if the user is authenticated, add property is_allowed_to_vote to the movie true or false
        if (auth('sanctum')->check()) {
            $user = auth('sanctum')->user();
            $userId = $user->id;

            foreach ($movies as $movie) {
                $movie->is_creator = $movie->user_id == $userId;
                $vote = $movie->votes()->where('user_id', $userId)->first();
                if ($vote) {
                    $movie->voted = $vote->vote_type;
                } else {
                    $movie->voted = null;
                }
            }
        }

        return response()->json($movies);
    }
}
