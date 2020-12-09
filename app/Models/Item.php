<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use SoftDeletes;
    protected $guarded = [];

    public function get_parent($item_id) {
        /*** get parent for this item **/
        return $this->where('id', $item_id)->first();
    }

    public function get_children($item_id) {
        /** select * where item_id == passed id **/
        return $this->where('item_id', $item_id)->get();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user() {
        return $this->belongsTo(User::class);
    }
}
