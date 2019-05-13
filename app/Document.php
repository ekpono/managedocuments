<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $fillable = ['documents', 'owner_id'];

    protected $casts = ['array' => 'documents'];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
}
