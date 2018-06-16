<?php

namespace Soved\Laravel\Magic\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Soved\Laravel\Magic\Auth\Links\LinkBroker;

trait SendsMagicLinkEmails
{
    /**
     * Send a magic link to the given user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function sendMagicLinkEmail(Request $request)
    {
        $this->validateEmail($request);

        $response = $this->broker()->sendMagicLink(
            $request->only('email')
        );

        // To-do: send user not found notification:

        return $this->sendMagicLinkResponse($response);
    }

    /**
     * Validate the email for the given request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function validateEmail(Request $request)
    {
        $this->validate($request, ['email' => 'required|email']);
    }

    /**
     * Get the response for a successful magic link.
     *
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendMagicLinkResponse(string $response)
    {
        // To-do: create translation files:

        return back()->with('status', __($response));
    }

    /**
     * Get the broker to be used during magic authentication.
     *
     * @return \Soved\Laravel\Magic\Auth\Links\LinkBroker
     */
    public function broker()
    {
        return new LinkBroker(
            Auth::getProvider()
        );
    }
}