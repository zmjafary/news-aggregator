<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

/**
 * @OA\Tag(name="Preferences", description="User preference management")
 */
class PreferenceController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/preferences/update",
     *     tags={"Preferences"},
     *     summary="Update user preferences",
     *     security={{ "bearer": {} }},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             required={"source", "category", "authors"},
     *             @OA\Property(
     *                 property="source",
     *                 type="array",
     *                 @OA\Items(type="string"),
     *                 example={"New York Times"}
     *             ),
     *             @OA\Property(
     *                 property="category",
     *                 type="array",
     *                 @OA\Items(type="string"),
     *                 example={"Politics"}
     *             ),
     *             @OA\Property(
     *                 property="authors",
     *                 type="array",
     *                 @OA\Items(type="string"),
     *                 example={"Michael Gold"}
     *             )
     *         )
     *     ),
     *     @OA\Response(response=200, description="Preferences Updated!"),
     *     @OA\Header(
     *         header="Accept",
     *         description="Specify the response format",
     *         required=true,
     *         @OA\Schema(type="string", example="application/json")
     *     ),
     *     @OA\Header(
     *         header="Content-Type",
     *         description="Content type of the request body",
     *         required=true,
     *         @OA\Schema(type="string", example="application/json")
     *     ),
     *     @OA\Response(response=400, description="Bad Request - Invalid JSON structure"),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function updatePreference(Request $request)
    {
        $rules = [];
        foreach (Article::FILTERS as $filter) {
            $rules[$filter] = 'array';
        }

        $request->validate($rules);

        foreach ($request->only(array_keys($rules)) as $key => $value) {
            $request->user()->preferences()->where('filter', $key)->update(['value' => $value]);
        }

        return response()->json([
            'message' => 'Preferences Updated!'
        ]);
    }
}
