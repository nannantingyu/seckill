<?php
namespace App\Exceptions;
use Exception;
use Throwable;

class GeneralException extends Exception
{
    public $message;
    protected $donotFlash = [

    ];

    public function __construct(string $message = "", int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function report()
    {
        //
    }

    public function render($request)
    {
        return redirect()
            ->back()
            ->withInput($request->except($this->donotFlash))
            ->withFlashDanger($this->message);
    }
}