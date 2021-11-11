<?php

namespace Lilbase\Revisions;

use Lilbase\Revisions\Models\Revision;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Auth;

trait HasRevisions
{

    public function revisions(): MorphMany
    {
        return $this->morphMany(Revision::class, 'editable');
    }

    protected static function booted()
    {
        static::updating(function ($model) {
            Revisions::create('updating', $model);
        });

        static::created(function ($model) {
            Revisions::create('created', $model);
        });

        static::deleting(function ($model) {
            Revisions::create('created', $model);
        });
    }

}
