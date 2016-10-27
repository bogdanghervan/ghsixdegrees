# ghsixdegrees
Six degrees of separation problem on GitHub contribution graph.

## Endpoints

### Find shortest path
```
GET /paths/:startUser/:endUser
```
`:startUser` and `:endUser` can either be the GitHub ID or the login handle,
for convenience.

No authentication needed.

#### Sample request
```
curl -XGET 'http://ghsixdegrees.local/paths/bogdanghervan/taylorotwell'
```

A contribution path can only exists in the context of two users. Failing to
provide both users will result in a 404 error.

#### Sample response
```
{
  "length": 1,
  "segments": [
    {
      "repository": "laravel/framework",
      "startUser": "bogdanghervan",
      "endUser": "taylorotwell"
    }
  ]
}
```

Let's try another request with contributors farther away.
```
curl -XGET 'http://ghsixdegrees.local/paths/bogdanghervan/taylorotwell'
{
   "length": 2,
   "segments": [
     {
        "repository": "laravel/framework",
        "startUser": "bogdanghervan",
        "endUser": "taylorotwell"
     },
     {
        "repository": "laravel/elixir",
        "startUser": "taylorotwell",
        "endUser": "GrahamCampbell"
     }
   ]
}
```

## Notes for evaluator

### Files of interest
* `app/Http/Controllers/PathsController.php`
  * Main endpoint.
* `app/Services/Paths.php`
  * Paths service layer. Here's where the Neo4j query for the shortest path is made from.
* `database/seeds/ContributionsSeeder.php`
  * Demo data.
* `app/Repository.php`, `app/User.php`
  * User and repository models for vertices labeled with "User" and "Repository", respectively.
* `app/Exceptions/Handler.php`
  * Refactored Lumen error handler to return JSON responses for all possible errors.
* `app/Http/RespondsWithJson.php`
  * Trait that encapsulates all possible error responses.
  * It is used by both `App/Http/Controllers/PathsController` and `App/Exceptions/Handler`
* `tests/PathsTest.php`
  * API functional tests.
