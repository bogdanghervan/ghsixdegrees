<?php

namespace App\Http\Controllers;

use Everyman\Neo4j\Path;
use Illuminate\Http\Request;
use App\Services\Paths as PathsService;

class PathsController extends Controller
{
    /**
     * Displays path between two users.
     * GET /paths/{user1}/{user2}
     *
     * @param  Request $request
     * @param  string $user1
     * @param  string $user2
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $user1, $user2)
    {
        $pathsService = new PathsService();

        $validator = $pathsService->makeValidator(['user1' => $user1, 'user2' => $user2]);
        $validator->validate();

        $path = $pathsService->findPath($user1, $user2);
    }
}
