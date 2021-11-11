<?php

namespace Lilbase\Revisions;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Lilbase\Revisions\Models\Revision;

class Revisions
{
    public static function create(string $action, Model $model): int
    {
        $revision = self::prepareNew($action);

        $after = $model->getAttributes();
        $revision->after = json_encode($after);

        if ($action === 'updating')
            self::updating($model, $revision, $after);

        $model->revisions()->save($revision);
        return $revision->id;
    }

    private static function updating(Model $model, Revision $revision, array $after)
    {
        $before = self::before($model);
        $revision->before = json_encode($before);
    }

    private static function before(Model $model): array
    {
        return self::timestampsToStrings($model->getOriginal());
    }

    private static function timestampsToStrings(array $attributes): array
    {
        $attributes['created_at'] = $attributes['created_at']->toDateTimeString();
        $attributes['updated_at'] = $attributes['updated_at']->toDateTimeString();
        return $attributes;
    }

    private static function validateAction(string $action)
    {
        if (! in_array($action, ['updating', 'created', 'deleting']))
            // @todo: Throw invalidAction exception.
            die('HasRevision requires a whitelisted action.');
    }

    private static function prepareNew(string $action): Revision
    {
        self::validateAction($action);
        return self::model($action);
    }

    private static function model(string $action): Revision
    {
        $revision = new Revision();
        $revision->action = $action;
        $revision->user_id = Auth::user()->id ?? 0;
        return $revision;
    }
}
