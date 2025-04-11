<?php

namespace App\Handlers;

use Illuminate\Foundation\Exceptions\Handler;
use \Throwable;
use Illuminate\Http\JsonResponse;

class ApiHandler extends Handler
{
    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $e
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function render($request, Throwable $e)
    {
        return $this->shouldReturnJson($request, $e) ?
            $this->prepareJsonResponse($request, $e) :
            $this->prepareResponse($request, $e);
    }

    /**
     * Prepare a JSON response for the given exception.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $e
     * @return \Illuminate\Http\JsonResponse
     */
    protected function prepareJsonResponse($request, Throwable $e)
    {
        $data = $this->convertExceptionToArray($e);

        return new JsonResponse(
            $this->convertExceptionToArray($e),
            $data['status'] ?? 500,
            $this->isHttpException($e) ? $e->getHeaders() : [],
            JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
        );
    }

    /**
     * Convert the given exception to an array.
     *
     * @param  \Throwable  $e
     * @return array
     */
    protected function convertExceptionToArray(Throwable $e)
    {
        $response = [
            'code' => $this->isHttpException($e) ? $e->getCode() : 500,
            'message' => $e->getMessage() ?? 'Internal Server Error',
        ];

        if (env('APP_DEBUG')) {
            $response ['file'] = $e->getFile();
            $response ['line'] = $e->getLine();
            $response ['trace'] = $e->getTrace();
        }

        return $response;
    }
}
