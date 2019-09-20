<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if ($exception instanceof ModelNotFoundException) {
            $model = class_basename($exception->getModel());
            return response()->json(['error' => "Instance of $model not found"], 404);
        }

        if ($exception instanceof NotFoundHttpException) {
            return response()->json(['error' => 'URL not found'], 404);
        }
        
        if ($exception instanceof MethodNotAllowedHttpException) {
            return response()->json(['error' => 'Method not allowed'], 405);
        }

        //Handler para controlar cualquier tipo de exception distinta a las anteriores
        if ($exception instanceof HttpException) {
            return response()->json(['error' => $exception->getMessage()], $exception->getStatusCode());
        }

        //Handler para controlar error al querer eliminar un recurso con alguna relación
        if ($exception instanceof QueryException) {
            $codigo = $exception->errorInfo[1];

            if ($codigo == 1451) {
                return response()->json(['error' => 'Cannot delete or update a parent row: a foreign key constraint fails'], 409);
            }
        }    
        
        //Si el servidor está en producción, no se muestra el error detallado.
        if (!config('app.debug')) {
            return response()->json(['error' => 'Falla inesperada'], 500); 
        }

        return parent::render($request, $exception);
    }
}
