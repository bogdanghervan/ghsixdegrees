<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Paths as PathsService;

class PathsController extends Controller
{
    /**
     * Displays path between two users.
     * GET /paths/{user1}/{user2}
     *
     * @param  Request $request
     * @param  string $startUserId
     * @param  string $endUserId
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $startUserId, $endUserId)
    {
        $pathsService = new PathsService();

        // Validate request
        $validator = $pathsService->makeValidator([
            'startUser' => $startUserId,
            'endUser' => $endUserId
        ]);
        $validator->validate();

        // Ensure that start and end nodes exist
        $startUser = $pathsService->findUser($startUserId);
        if (!$startUser) {
            return $this->errorInvalidParameter('startUser',
                sprintf('User %s not found', $startUserId));
        }
        $endUser = $pathsService->findUser($endUserId);
        if (!$endUser) {
            return $this->errorInvalidParameter('endUser',
                sprintf('User %s not found', $endUserId));
        }

        // Search for the shortest distance between given users,
        // and return distance = 0 if given users are one and the same
        $segments = [];
        if ($startUser != $endUser) {
            $segments = $pathsService->findPath($startUser, $endUser);
        }

        return $this->respondWithArray([
            'length' => count($segments),
            'segments' => $segments
        ]);
    }
}
