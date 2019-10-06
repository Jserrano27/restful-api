<?php

namespace App\Http\Middleware;

use App\Transformers\CategoryTransformer;
use Closure;
use Illuminate\Validation\ValidationException;

class TransformInput
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $transformer)
    {
        $transformedInput = [];

        foreach ($request->all() as $input => $value) {
            $transformedInput[$transformer::originalAttribute($input)] = $value;
        }

        $request->replace($transformedInput);

        $response = $next($request);

        if(isset($response->exception) && $response->exception instanceof ValidationException){

            $data = $response->getData();

            $transformedErrors = [];

            foreach ($data->error as $field => $error) {
                $transformedAttribute = $transformer::transformedAttribute($field);

                $transformedErrors[$transformedAttribute] = str_replace($field, $transformedAttribute, $error);
           }
           $data->error = $transformedErrors;

           $response->setData($data);
        }
        return $response;
    }
}
