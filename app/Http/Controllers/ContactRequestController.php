<?php

namespace App\Http\Controllers;

use App\Models\ContactRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactRequestController extends Controller
{
    function confirmation(null|int $id = null, string|null $token = null)
    {
        if (!request()->hasValidSignature()) {
            abort(401);
        }

        $formRequest = ContactRequest::withoutGlobalScopes()->whereId($id)->first();

        $formRequest->update(['verified_at' => now()]);

        Mail::to($formRequest->email)->send(new \App\Mail\ContactRequestConfirmedMail());
        Mail::to(\App\Livewire\ContactForm::RECIPIENT)->send(new \App\Mail\ContactRequestSolvedMail($formRequest));

        return view('confirmed');
    }
}
