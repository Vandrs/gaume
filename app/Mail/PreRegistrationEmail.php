<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\PreRegistration;
use Carbon\Carbon;
use Lang;
use Mail;

class PreRegistrationEmail extends Mailable
{
    use Queueable, SerializesModels;

    private $preRegistration;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(PreRegistration $preRegistration)
    {
        $this->preRegistration = $preRegistration;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->preRegistration->fresh();
        $mailData = [
            'name' => $this->preRegistration->name,
            'link' => route('teacher.registration', ['code' => $this->preRegistration->code])
        ];

        return $this->to($this->preRegistration->email, $this->preRegistration->name)
                    ->subject(Lang::get('email.pre_registration_mail.subject'))
                    ->view('email.pre_registration_mail', $mailData);
    }

    public function handle()
    {
        Mail::send($this);
        $this->preRegistration->update(['mailed_at' => Carbon::now()]);
    }
}
