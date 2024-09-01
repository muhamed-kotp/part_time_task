<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class Product extends Model implements TranslatableContract

{
    use Translatable;
    public $translatedAttributes = ['name','description'];
    protected $fillable = ['img','category_id'];

    protected $hidden = ['translations'];


    public function category ()
    {
        return $this->belongsTo('App\Models\Category');
    }

}
