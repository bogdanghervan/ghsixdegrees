<?php

namespace App;

use Vinelab\NeoEloquent\Eloquent\Model;

/**
 * Repository model.
 *
 * @package App
 */
class Repository extends Model
{
    /**
     * The node label.
     *
     * @var string|array
     */
    protected $label = 'Repository';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['githubId', 'fullName'];

    /**
     * Defines an incoming relationship between Repository and User.
     *
     * @return \Vinelab\NeoEloquent\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany('App\User', 'CONTRIBUTED_TO');
    }
}
