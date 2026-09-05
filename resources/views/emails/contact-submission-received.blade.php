<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="margin:0; padding:0; background:#f4f3f1; font-family: Arial, Helvetica, sans-serif;">
  <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f4f3f1; padding:32px 16px;">
    <tr>
      <td align="center">
        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:560px; background:#ffffff; border-radius:12px; overflow:hidden; border:1px solid #e5e3df;">
          <tr>
            <td style="background:#12151e; padding:24px 32px;">
              <span style="font-family: Georgia, serif; font-size:19px; font-weight:bold; color:#f6f2ea;">Infinite Books <span style="color:#dc9a5f;">Publishing</span></span>
            </td>
          </tr>
          <tr>
            <td style="padding:32px;">
              <h1 style="margin:0 0 4px; font-size:18px; color:#12151e;">New Contact Form Submission</h1>
              <p style="margin:0 0 24px; font-size:14px; color:#6b7280;">Someone just filled out the contact form on your website.</p>

              <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="font-size:14px; color:#12151e;">
                <tr>
                  <td style="padding:8px 0; border-bottom:1px solid #e5e3df; width:110px; color:#6b7280;">Name</td>
                  <td style="padding:8px 0; border-bottom:1px solid #e5e3df;">{{ $submission->name }}</td>
                </tr>
                <tr>
                  <td style="padding:8px 0; border-bottom:1px solid #e5e3df; color:#6b7280;">Email</td>
                  <td style="padding:8px 0; border-bottom:1px solid #e5e3df;"><a href="mailto:{{ $submission->email }}" style="color:#c17f4a;">{{ $submission->email }}</a></td>
                </tr>
                <tr>
                  <td style="padding:8px 0; border-bottom:1px solid #e5e3df; color:#6b7280;">Phone</td>
                  <td style="padding:8px 0; border-bottom:1px solid #e5e3df;"><a href="tel:{{ $submission->phone }}" style="color:#c17f4a;">{{ $submission->phone }}</a></td>
                </tr>
                @if ($submission->service)
                  <tr>
                    <td style="padding:8px 0; border-bottom:1px solid #e5e3df; color:#6b7280;">Service</td>
                    <td style="padding:8px 0; border-bottom:1px solid #e5e3df;">{{ $submission->service }}</td>
                  </tr>
                @endif
              </table>

              <p style="margin:20px 0 6px; font-size:13px; color:#6b7280; text-transform:uppercase; letter-spacing:0.04em;">Message</p>
              <p style="margin:0; font-size:14px; line-height:1.6; color:#12151e; white-space:pre-line;">{{ $submission->message }}</p>
            </td>
          </tr>
          <tr>
            <td style="padding:16px 32px; background:#f4f3f1; font-size:12px; color:#9aa2b2;">
              Submitted {{ $submission->created_at->format('M j, Y \a\t g:i A') }} &middot; Reply directly to this email to respond to {{ $submission->name }}.
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</body>
</html>
