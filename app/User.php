<?php

namespace App;

use Vinelab\NeoEloquent\Eloquent\Model;

/**
 * User model.
 *
 * @package App
 */
class User extends Model
{
    /**
     * The node label.
     *
     * @var string|array
     */
    protected $label = 'User';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['githubId', 'login'];

    /**
     * Defines an outgoing relationship between User and Repository.
     *
     * @return \Vinelab\NeoEloquent\Eloquent\Relations\HasMany
     */
    public function repositories()
    {
        return $this->hasMany('App\Repository', 'CONTRIBUTED_TO');
    }
}
