<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DatabaseConnection extends Model
{
     protected $fillable = [
        'user_id',
        'type',
        'host',
        'dbname',
        'username',
        'password',
    ];

    // Relation avec l’utilisateur qui a créé la connexion
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function backups()
{
    return $this->hasMany(Backup::class, 'db_connection_id');
}

}

