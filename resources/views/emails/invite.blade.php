<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>You are Invited</title>
</head>

<body style="font-family: Arial, sans-serif; background:#f5f5f5; padding:20px;">

    <div style="max-width:600px; margin:auto; background:#ffffff; padding:20px; border-radius:10px;">

        <h2 style="color:#333;">Lakewood Shomrim LPR System</h2>

        <p style="font-size:16px; color:#555;">
            You have been invited to join the Lakewood Shomrim LPR Dashboard.
        </p>

        <p style="font-size:16px; color:#555;">
            Click the button below to complete your registration:
        </p>

        <div style="margin:25px 0;">
            <a href="{{ route('invite.accept', $invite->token) }}"
                style="background:#2563eb; color:white; padding:14px 24px; text-decoration:none; border-radius:8px; font-size:16px;">
                Accept Invitation
            </a>
        </div>

        <p style="font-size:14px; color:#777;">
            If you did not expect this email, you can safely ignore it.
        </p>

    </div>

</body>

</html>