<x-mail::message>
Hi Dear,
Thanks for signing up with us! Before we get started, we need to verify your email address.
<x-mail::button :url="'http://localhost:3000/login'">
Verify Your Email
</x-mail::button>
<!-- [Click here to verify your email]({{ env('FRONTEND_APP_URL') }}) -->
Thanks,<br>
{{ config('app.name') }}-IT Startups & Digital Services 
</x-mail::message>

