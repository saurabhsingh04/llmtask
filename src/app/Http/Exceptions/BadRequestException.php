<?php
 
namespace App\Http\Exceptions;
 
use Exception;
 
class BadRequestException extends Exception
{
    /**
     * Exception code
     *
     * @var integer
     */
    protected $code;
    /**
     * Exception Message
     *
     * @var string
     */
    protected $message;
    /**
     * Exception details
     *
     * @var array
     */
    protected $details;
    /**
     * BadRequestException constructor function
     *
     * @param integer $code
     * @param string $message
     * @param array  $details
     */
    public function __construct($message, $code, $details= [])
    {
        $this->code = $code;
        $this->message = $message;
        $this->details = $details;
    }
    /**
     * Render the exception
     *
     * @return JsonResponse
     */
    public function render()
    {
        return response()->json([
            "error" => $this->message,
            "details" => $this->details
        ], $this->code);
    }
}