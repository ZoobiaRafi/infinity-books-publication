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
              <h1 style="margin:0 0 12px; font-size:18px; color:#12151e;">Thanks, {{ $submission->name }} — we got your message</h1>
              <p style="margin:0 0 20px; font-size:14px; line-height:1.6; color:#374151;">
                We've received your message and someone from our team will get back to you within one business day.
                Here's a copy of what you sent us:
              </p>

              <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f4f3f1; border-radius:8px;">
                <tr>
                  <td style="padding:16px 20px; font-size:14px; line-height:1.6; color:#12151e; white-space:pre-line;">{{ $submission->message }}</td>
                </tr>
              </table>

              <p style="margin:24px 0 0; font-size:14px; line-height:1.6; color:#374151;">
                In the meantime, feel free to reply directly to this email if you'd like to add anything.
              </p>
            </td>
          </tr>
          <tr>
            <td style="padding:16px 32px; background:#f4f3f1; font-size:12px; color:#9aa2b2;">
              Infinite Books Publishing &middot; +1 (980) 223-4655 &middot; info@infinitebookspublishing.com
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</body>
</html>
