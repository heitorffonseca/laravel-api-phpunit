<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;

class Job extends Model
{
    use HasFactory;

    protected $table = 'jobs';

    protected $fillable = ['uuid', 'title', 'description', 'local', 'remote', 'type', 'company_id'];

    protected $dates = ['deleted_at'];

    function company() {
        return $this->belongsTo(Company::class);
    }

    public static function boot()
    {
        parent::boot();
        Static::creating(function($model) {
            $model->uuid = Uuid::generate()->string;
        });
    }

    public static function findByUuid(string $uuid)
    {
        return self::where('uuid', $uuid)->get()->first();
    }

    public static function findRandom()
    {
        return self::inRandomOrder()->get()->first();
    }

    public function getCurrentCompany()
    {
        return $this->company()->first();
    }
}
