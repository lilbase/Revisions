<?php

namespace Lilbase\Revisions\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Revision extends Model
{
    use HasFactory;

    public function editable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getDiffAttribute() : string
    {
        $diff = array_diff($this->before, $this->after);
        $diff_text = '';
        foreach ($diff as $key => $value) {
            if (strlen($diff_text) > 0) $diff_text = PHP_EOL;
            $diff_text .= 'FIELD: ' . strtoupper($key) . PHP_EOL;
            $diff_text .= PHP_EOL . '     Before: ' . $this->before[$key] . PHP_EOL;
            $diff_text .= PHP_EOL . '     After: ' . $this->after[$key] . PHP_EOL;
        }
        return $diff_text;
    }

}
