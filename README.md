# ghsixdegrees
Six degrees of separation problem on GitHub contribution graph.

## Endpoints

### Find shortest path
```
GET /paths/:startUser/:endUser
```
`startUser` and `endUser` can either be the GitHub ID or the login handle,
for convenience.

#### Sample request
```
curl -XGET 'http://ghsixdegrees.local/paths/bogdanghervan/taylorotwell'
```

A contribution path can only exists in the context of two users. Failing to
provide both users will result in a 404 error.

#### Sample response
```JSON
{
  length: 1,
  segments: [
    {
      repository: "laravel/framework",
      startUser: "bogdanghervan",
      endUser: "taylorotwell"
    }
  ]
}
```

Let's try another request with contributors farther away.
```
curl -XGET 'http://ghsixdegrees.local/paths/bogdanghervan/taylorotwell'
{
   length: 2,
   segments: [
     {
        repository: "laravel/framework",
        startUser: "bogdanghervan",
        endUser: "taylorotwell"
     },
     {
        repository: "laravel/elixir",
        startUser: "taylorotwell",
        endUser: "GrahamCampbell"
     }
   ]
}
