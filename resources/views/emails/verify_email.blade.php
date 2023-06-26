@component('mail::message')
    <h1>Verify Your Email Address</h1><p>You can use the following code to verify your account:
    </p> @component('mail::panel'){{ $code }}
    @endcomponent <p>The allowed duration of the code is one hour from the time the message was sent</p>@endcomponent
