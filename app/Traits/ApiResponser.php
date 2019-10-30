<?php

namespace App\Traits;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\LengthAwarePaginator;

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

    protected function paginate(Collection $collection)
    {

        $rules = [
            'per_page' => 'integer | min:2 | max:50'
        ];

        Validator::validate(request()->all(), $rules);
        //pagina actual
        $page = LengthAwarePaginator::resolveCurrentPage();

        $perPage = 15;

        if (request()->has('per_page')) {
            $perPage = (int) request()->per_page;
        }

        $results = $collection->slice(($page - 1) * $perPage, $perPage)->values();

        $paginated = new LengthAwarePaginator($results, $collection->count(), $perPage, $page, [
            'path' => LengthAwarePaginator::resolveCurrentPath()
        ]);

        $paginated->appends(request()->all());

        return $paginated;
    }

    protected function cacheResponse($data)
    {
        $url = request()->url();
        $queryParams = request()->query();

        ksort($queryParams);

        $queryString = http_build_query($queryParams);

        $fullUrl = "{$url}?{$queryString}";

        return Cache::remember($fullUrl, 15, function () use($data){
            return $data;
        });
    }
    

    protected function resourceCollection(Collection $collection)
    {
        if ($collection->isEmpty()) {
            return $collection;
        }

        $collectionClass = $collection->first()->collectionClass;

        $collection = $this->sortResponse($collection, $collectionClass);
        $collection = $this->filterResponse($collection, $collectionClass);
        $collection = $this->paginate($collection);
        $collection = $this->cacheResponse($collection);

        return new $collectionClass($collection);

    }

}