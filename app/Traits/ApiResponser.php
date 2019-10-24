<?php

namespace App\Traits;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

trait ApiResponser
{
    private function succesResponse($data, $code) 
    {
        return response()->json($data, $code);
    }

    protected function errorResponse($message, $code) 
    {
        return response()->json(['error' => $message], $code);
    }

    protected function showAll(Collection $collection, $code = 200)
    {
        return $this->succesResponse(['data' => $collection], $code);
    }

    protected function showOne(Model $instance, $code = 200)
    {
        return $this->succesResponse(['data' => $instance], $code);
    }
    
    protected function showMessage($message, $code = 200)
    {
        return $this->succesResponse(['data' => $message], $code);
    }

    protected function sortResponse(Collection $collection, $collectionClass)
    {
        //Recibo un ResourceCollection, lo ordeno por el parametro sort_by,
        //se ordena por los indices transformados en el método publico de los Collection.
        if(request()->has('sort_by')){

            $att = request()->sort_by;
            $collection = $collection->sortBy($collectionClass::originalAttribute($att))->values();
        }

        return $collection;
    }

    protected function filterResponse(Collection $collection, $collectionClass)
    {
        foreach (request()->query() as $query => $value) {

            $attribute = $collectionClass::originalAttribute($query);


            if (isset($attribute, $query)) {
                $collection = $collection->where($attribute, $value);
            }
        }

        return $collection;
    }
    

    protected function resourceCollection(Collection $collection)
    {
        if ($collection->isEmpty()) {
            return $collection;
        }

        $collectionClass = $collection->first()->collectionClass;

        $collection = $this->sortResponse($collection, $collectionClass);
        $collection = $this->filterResponse($collection, $collectionClass);

        return new $collectionClass($collection);

    }

}