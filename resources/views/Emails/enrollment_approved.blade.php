<p>Dear {{ $enrollment->first_name }},</p>
<p>Your enrollment for {{ $enrollment->program->name ?? 'No Program' }} has been <strong>approved</strong>.</p>
<p>Please check your student portal for further details.</p>
<p><strong>Username:</strong> {{ $username }}</p>
<p><strong>Password:</strong> {{ $password }}</p>
<p>Thank you for choosing Bohol Northern Star College!</p>