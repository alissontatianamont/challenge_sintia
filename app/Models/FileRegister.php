<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FileRegister extends Model
{
    protected $table = 'files_register';
    protected $fillable = ['file_name', 'file_type', 'file_size', 'file_user_id', 'file_google_id'];

    public const CREATED_AT = 'file_created';
    public const UPDATED_AT = null;

    protected $casts = [
        'file_created' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'file_user_id');
    }
}
