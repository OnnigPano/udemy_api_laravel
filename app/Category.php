<?php

namespace App;

use App\Product;
use Illuminate\Database\Eloquent\Model;
use App\Http\Resources\CategoryCollection;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = ['name', 'description'];

    protected $hidden = ['pivot']; //oculta el atributo pivot de los resultados

    public $collectionClass = CategoryCollection::class;

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }
}
