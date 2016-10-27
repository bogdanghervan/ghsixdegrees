<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Repository;
use Vinelab\NeoEloquent\Facade\Neo4jSchema;

class ContributionsSeeder extends Seeder
{
    protected $usersData = [
        [
            'githubId' => 585130,
            'login' => 'bogdanghervan',
            'repos' => [
                ['githubId' => 7548986, 'fullName' => 'laravel/framework'],
                ['githubId' => 26374720, 'fullName' => 'cronkeep/cronkeep'],
                ['githubId' => 70386632, 'fullName' => 'bogdanghervan/partymixer'],
                ['githubId' => 702550, 'fullName' => 'zendframework/zendframework'],
            ]
        ], [
            'githubId' => 463230,
            'login' => 'taylorotwell',
            'repos' => [
                ['githubId' => 7548986, 'fullName' => 'laravel/framework'],
                ['githubId' => 24749463, 'fullName' => 'laravel/elixir'],
            ],
        ], [
            'githubId' => 1725326,
            'login' => 'forecho',
            'repos' => [
                ['githubId' => 26374720, 'fullName' => 'cronkeep/cronkeep'],
             ]
        ], [
            'githubId' => 2829600,
            'login' => 'GrahamCampbell',
            'repos' => [
                ['githubId' => 24749463, 'fullName' => 'laravel/elixir'],
            ],
        ], [
            'githubId' => 25943,
            'login' => 'weierophinney',
            'repos' => [
                ['githubId' => 702550, 'fullName' => 'zendframework/zendframework'],
                ['githubId' => 24749463, 'fullName' => 'laravel/elixir'],
            ]
        ]
    ];

    public function run()
    {
        // Empty database first
        Neo4jSchema::drop('User');
        Neo4jSchema::drop('Repository');

        $users = [];
        $repos = [];
        foreach ($this->usersData as $userData) {
            // Create user if it doesn't exist
            if (!isset($users[$userData['githubId']])) {
                $user = User::create(array_only($userData, ['githubId', 'login']));
                $users[$userData['githubId']] = $user;
            }

            foreach ($userData['repos'] as $repoData) {
                // Create repo if it doesn't exist
                if (!isset($repos[$repoData['githubId']])) {
                    $repos[$repoData['githubId']] = Repository::create($repoData);
                }

                // Save relationship
                $users[$userData['githubId']]->repositories()
                    ->save($repos[$repoData['githubId']]);
            }
        }
    }
}
