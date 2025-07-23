<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Backup extends Model
{   
    protected $fillable = [
        'db_connection_id',  // ✅ on autorise la clé étrangère
        'file_path',
        'status',
    ];
    
    public function database()
{
    return $this->belongsTo(DatabaseConnection::class, 'db_connection_id');
}

}
