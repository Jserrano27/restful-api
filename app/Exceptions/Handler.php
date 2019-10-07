<?php

namespace App\Exceptions;

use App\Traits\ApiResponser;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\QueryException;
use Illuminate\Session\TokenMismatchException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use function Psy\debug;

class Handler extends ExceptionHandler
{
    use ApiResponser;
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
        if($exception instanceof ValidationException){
            return $this->convertValidationExceptionToResponse($exception, $request);
        } 

        if($exception instanceof ModelNotFoundException){
            $modelName=class_basename($exception->getModel());
            return $this->errorResponse("{$modelName} not found with the specified ID", 404);
        } 

        if($exception instanceof AuthenticationException){
            return $this->unauthenticated($request, $exception);
        } 

        if($exception instanceof AuthorizationException){
            return $this->errorResponse($exception->getMessage(), 403);
        } 

        if($exception instanceof NotFoundHttpException) {
            return $this->errorResponse("The specified URL cannot be found", 404);
        } 

        if($exception instanceof MethodNotAllowedHttpException){
            return $this->errorResponse("The specified method for the request is invalid", 405);
        } 

        if($exception instanceof HttpException){
            return $this->errorResponse($exception->getMessage(), $exception->getStatusCode());
        }

        if($exception instanceof QueryException){
            $errorCode = $exception->errorInfo[1];
            
            if($errorCode == 1451){
                return $this->errorResponse("Cannot remove this resource permanently. It is related " .
                "to another resource", 409);
            } 
        }

        if($exception instanceof TokenMismatchException){
            return redirect()->back()->withInput();
        }

        // For details of the exception make true the APP_DEBUG value on the .env file
        if(config('app.debug')){
            return parent::render($request, $exception);
        }

        return $this->errorResponse("Unexpected error. Please, try again later", 500);
    }

    /**
     * Create a response object from the given validation exception.
     *
     * @param  \Illuminate\Validation\ValidationException  $e
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function convertValidationExceptionToResponse(ValidationException $e, $request)
    {
        $errors = $e->validator->errors()->getMessages();

        if($this->isFrontEnd($request)){

            return $request->ajax() ? response()->json($errors, 422) : redirect()->back()->withInput()->withErrors($errors);
        }

        return $this->errorResponse($errors, 422);
    }

        /**
     * Convert an authentication exception into a response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if($this->isFrontEnd($request)){
            return redirect()->back()->withInput();
        }

        return $this->errorResponse('Anauthenticated', 401);
    }

    private function isFrontEnd($request)
    {
        return $request->acceptsHtml() && collect($request->route()->middleware())->contains('web');
    }
}
