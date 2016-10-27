<?php

namespace App\Services;

use DB;
use Validator;
use RuntimeException;
use App\User;

/**
 * Paths service layer.
 *
 * @author  Bogdan Ghervan <bogdan.ghervan@gmail.com>
 * @package App\Services
 */
class Paths
{
    /**
     * Assembles and returns validator.
     *
     * @param array $input
     * @return Validator
     */
    public function makeValidator(array $input)
    {
        $validator = Validator::make($input, [
            'startUser' => 'required|alpha_dash',
            'endUser' => 'required|alpha_dash'
        ]);

        return $validator;
    }

    /**
     * Finds the shortest path between two given users.
     *
     * @param User $startUser
     * @param User $endUser
     * @return array
     */
    public function findPath(User $startUser, User $endUser)
    {
        if ($startUser == $endUser) {
            return [];
        }

        /* @var $graph \Everyman\Neo4j\Client */
        $graph = app('neo4j');

        // Find path (using low-level Neo4j driver)
        $startNode = $graph->getNode($startUser->id);
        $endNode = $graph->getNode($endUser->id);
        if (!$startNode || !$endNode) {
            throw new RuntimeException(sprintf(
                'Either start node %s or end node %s could not be found',
                $startUser->id, $endUser->id
            ));
        }

        // This will return a path formed by an even number of nodes of
        // mixed type.
        // e.g. [USER] -> [REPOSITORY] <- [...] -> [REPOSITORY] <- [USER]
        $path = $startNode->findPathsTo($endNode)
            ->setMaxDepth(10)
            ->getSinglePath();

        // Prepare a more compact response
        $nodes = $path->getNodes();
        $segments = [];
        for ($i = 0, $count = count($nodes); $i < $count - 1; $i += 2) {
            $segment = [
                'repository' => $nodes[$i + 1]->fullName,
                'startUser' => $nodes[$i]->login,
                'endUser' => $nodes[$i + 2]->login,
            ];
            $segments[] = $segment;
        }

        return $segments;
    }

    /**
     * Looks up user given either its GitHub ID or login handle,
     * for convenience.
     *
     * @param int|string $githubIdOrLogin
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function findUser($githubIdOrLogin)
    {
        $query = User::query();

        if (is_numeric($githubIdOrLogin)) {
            $query->where('githubId', (int) $githubIdOrLogin);
        } else {
            $query->where('login', $githubIdOrLogin);
        }

        return $query->first();
    }
}
