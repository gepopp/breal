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



        if(!is_null($formRequest) && !is_null($formRequest->verified_at)){
            $formRequest->update(['verified_at' => now()]);
            $recipeint = $formRequest->email;
            $office = \App\Livewire\ContactForm::RECIPIENT;
            $mail1 = Mail::to($recipeint)->send(new \App\Mail\ContactRequestConfirmedMail());
            $mail2 = Mail::to($office)->send(new \App\Mail\ContactRequestSolvedMail($formRequest));
        }


        return view('confirmed');
    }
}
