<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class projects extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'title_en',
        'service_id',
        'main_image',
        'daraing_date',
        'description',
        'description_en',

    ];
    protected $timestamp = true;

    public function services(){
        return $this->belongsTo(Services::class,'service_id');

    }
}
