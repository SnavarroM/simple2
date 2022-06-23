<?php

namespace App\Exceptions;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;

class UserBlockedException extends Exception
{
    private $user;

    private $msg;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct($msg, $user)
    {
        $this->msg = $msg;
        $this->user = $user;
    }

    /**
     * Report or log an exception.
     *
     * @return void
     */
    public function report()
    {
        \Log::debug('[USER DISABLED]: '.$this->msg, [
            'user_type' => $this->user->user_type,
            'email' => $this->user->email
        ]);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  $request
     */
    public function render(Request $request)
    {
        Auth::guard()->logout();

        $request->session()->invalidate();

        return response()->view('errors.user_blocked', [
            'exception' => $this
        ]);
    }
}
