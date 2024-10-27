<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Repositories\ArticleRepository;

/**
 * @OA\Tag(name="Articles", description="Operations on articles")
 */

/**
 * @OA\SecurityScheme(
 *     securityScheme="bearer",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     description="Enter your Bearer token in the format **Bearer {token}**"
 * )
 */

class ArticleController extends Controller
{
    protected $articleRepository;

    public function __construct(ArticleRepository $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    /**
     * @OA\Get(
     *     path="/api/articles",
     *     tags={"Articles"},
     *     summary="List all articles with optional filters",
     *     description="Retrieve a paginated list of articles. You can filter articles by keyword, category, source, and published date.",
     *     security={{ "bearer": {} }},
     *     @OA\Parameter(
     *         name="keyword",
     *         in="query",
     *         description="Search for articles by title or description.",
     *         required=false,
     *         @OA\Schema(type="string", example="technology")
     *     ),
     *     @OA\Parameter(
     *         name="category",
     *         in="query",
     *         description="Filter articles by category.",
     *         required=false,
     *         @OA\Schema(type="string", example="Tech")
     *     ),
     *     @OA\Parameter(
     *         name="source",
     *         in="query",
     *         description="Filter articles by source.",
     *         required=false,
     *         @OA\Schema(type="string", example="CNN")
     *     ),
     *     @OA\Parameter(
     *         name="date",
     *         in="query",
     *         description="Filter articles by published date (format: YYYY-MM-DD).",
     *         required=false,
     *         @OA\Schema(type="string", format="date", example="2024-10-26")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="A paginated list of articles successfully retrieved.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="current_page", type="integer", example=1),
     *             @OA\Property(property="total", type="integer", example=100),
     *             @OA\Property(property="per_page", type="integer", example=10),
     *             @OA\Property(property="last_page", type="integer", example=10),
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="title", type="string", example="Understanding Technology Trends"),
     *                     @OA\Property(property="description", type="string", example="This article explores the latest technology trends."),
     *                     @OA\Property(property="category", type="string", example="Technology"),
     *                     @OA\Property(property="source", type="string", example="TechCrunch"),
     *                     @OA\Property(property="published_at", type="string", format="date-time", example="2024-10-26T00:00:00Z")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized access due to invalid or missing token.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="Unauthorized")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="Internal Server Error")
     *         )
     *     ),
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
     * )
     */

    public function index(Request $request)
    {
        $articles = $this->articleRepository->index($request);
        return response()->json($articles);
    }


    /**
     * @OA\Get(
     *     path="/api/articles/{id}",
     *     tags={"Articles"},
     *     summary="Get a specific article",
     *     security={{ "bearer": {} }},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Details of the article"),
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
     *     @OA\Response(response=404, description="Article not found"),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function show($id)
    {
        $article = $this->articleRepository->show($id);
        return response()->json($article);
    }

    /**
     * @OA\Get(
     *     path="/api/articles/personalized",
     *     tags={"Articles"},
     *     summary="Get personalized articles for the user",
     *     security={{ "bearer": {} }},
     *     @OA\Response(response=200, description="List of personalized articles"),
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
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */

    public function personalizedFeed(Request $request)
    {
        $articles = $this->articleRepository->personalizedFeed($request->user()->preferences()->get());
        return response()->json($articles);
    }
}
