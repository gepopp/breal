<?php

namespace App\Http\Controllers;

use App\Livewire\ContactForm;
use App\Mail\ContactRequestConfirmedMail;
use App\Mail\NewContactRequestAdminMail;
use App\Models\ContactRequest;
use Illuminate\Support\Facades\Mail;

class ContactRequestController extends Controller
{
    public function confirmation(?int $id = null, ?string $token = null)
    {
        if (! request()->hasValidSignature()) {
            abort(401);
        }

        $formRequest = ContactRequest::withoutGlobalScopes()->whereId($id)->first();

        if ($formRequest->token != $token) {
            abort(401);
        }

        if (! is_null($formRequest) && is_null($formRequest->verified_at)) {

            $formRequest->update(['verified_at' => now()]);

            $recipeint = $formRequest->email;
            $office = ContactForm::RECIPIENT;

            Mail::to($recipeint)->send(new ContactRequestConfirmedMail($formRequest));
            Mail::to($office)->send(new NewContactRequestAdminMail($formRequest));

        }

        return view('confirmed');
    }

    public function solve(ContactRequest $request, ?string $token = null)
    {
        if (! request()->hasValidSignature() || $request->token !== $token) {
            abort(401);
        }

        if (is_null($request->solved_at)) {
            $request->update(['solved_at' => now()]);
        }

        return view('solved', compact('request'));
    }
}
