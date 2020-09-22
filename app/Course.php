<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $table = 'courses';

    protected $fillable = [
        'name', 'certificate', 'thumbnail', 'type',
        'status', 'price', 'level', 'description', 'mentor_id'
    ];

    public function mentor()
    {
        return $this->belongTo('App\Mentor');
    }

    public function chapters()
    {
        return $this->hasMany('App\Chapter')->orderBy('id', 'ASC');
    }

    public function images()
    {
        return $this->hasMany('App\ImageCourse')->orderBy('id', 'DESC');
    }
}
