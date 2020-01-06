<?php
 
namespace App\Http\Exceptions;
 
use Exception;
 
class OrderException extends Exception
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
     * @var string
     */
    protected $details;
    /**
     * BadRequestException constructor function
     *
     * @param integer $code
     * @param string $message
     * @param string  $details
     */
    public function __construct($message, $code, $details='')
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
        return response()->json(array_filter([
            "error" => $this->message,
            "details" => $this->details
        ]), $this->code);
    }
}