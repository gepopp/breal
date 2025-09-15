<?php

namespace App\Filament\Resources\ContactRequestResource\Pages;

use App\Filament\Resources\ContactRequestResource;
use App\Livewire\ContactForm;
use Filament\Actions;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Mail;

class EditContactRequest extends EditRecord
{
    protected static string $resource = ContactRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\Action::make('Send Email')
                ->icon('heroicon-o-envelope')
                ->form([
                    TextInput::make('email')
                        ->label('Email')
                        ->required()
                        ->email()
                        ->default(ContactForm::RECIPIENT)
                ])
                ->action(function ($record, array $data) {
                    Mail::to($data['email'])->send(new \App\Mail\NewContactRequestAdminMail($record));
                })
        ];
    }
}
