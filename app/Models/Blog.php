<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use SoftDeletes;
    protected $table = 'posts';

    protected $guarded = [];

    protected function get_parent($id) {
        return $this->where('id', $id) ->first();
    }
}
