<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;

class Company extends Model
{
    use HasFactory;

    protected $table = 'companies';

    protected $fillable = ['uuid', 'name', 'email', 'website', 'logo', 'password'];

    protected $hidden = ['password'];

    protected $dates = ['deleted_at'];

    public function jobs()
    {
        return $this->hasMany(Job::class);
    }

    public static function boot()
    {
        parent::boot();
        Static::creating(function($model) {
            $model->uuid = Uuid::generate()->string;
        });
    }

    public static function findByUuid(string $uuid): Company
    {
        return self::where('uuid', $uuid)->get()->first();
    }

    public static function findRandom()
    {
        return self::inRandomOrder()->get()->first();
    }


}
