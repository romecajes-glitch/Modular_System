<p>Dear {{ $enrollment->first_name }},</p>
<p>We regret to inform you that your enrollment for {{ $enrollment->program->name ?? 'No Program' }} has been <strong>rejected</strong>.</p>
<p>Reason: {{ $reason }}</p>
<p>If you have questions, please contact us.</p>