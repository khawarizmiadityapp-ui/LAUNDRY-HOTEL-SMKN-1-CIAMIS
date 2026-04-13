<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Kode OTP</title>
</head>
<body style="margin:0; padding:0; background-color:#f4f4f7; font-family:Arial, sans-serif;">

    <table width="100%" cellpadding="0" cellspacing="0" style="padding:20px;">
        <tr>
            <td align="center">

                <!-- CARD -->
                <table width="500" cellpadding="0" cellspacing="0" style="background:#ffffff; border-radius:10px; padding:30px; box-shadow:0 5px 15px rgba(0,0,0,0.1);">
                    
                    <!-- HEADER -->
                    <tr>
                        <td align="center">
                            <h2 style="margin:0; color:#4F46E5;">🔐 Reset Password</h2>
                        </td>
                    </tr>

                    <!-- BODY -->
                    <tr>
                        <td align="center" style="padding:20px 0;">
                            <p style="font-size:16px; color:#333;">
                                Gunakan kode OTP berikut untuk melanjutkan proses reset password:
                            </p>

                            <!-- OTP BOX -->
                            <div style="margin:20px 0;">
                                <span style="
                                    display:inline-block;
                                    font-size:32px;
                                    letter-spacing:8px;
                                    font-weight:bold;
                                    color:#111;
                                    background:#f9fafb;
                                    padding:15px 25px;
                                    border-radius:8px;
                                    border:1px solid #e5e7eb;
                                ">
                                    {{ $otp }}
                                </span>
                            </div>

                            <p style="color:#666;">
                                Kode ini berlaku selama <b>5 menit</b>.
                            </p>
                        </td>
                    </tr>

                    <!-- FOOTER -->
                    <tr>
                        <td style="border-top:1px solid #eee; padding-top:15px; text-align:center;">
                            <small style="color:#999;">
                                Jangan bagikan kode ini kepada siapa pun.<br>
                                Jika kamu tidak meminta reset password, abaikan email ini.
                            </small>
                        </td>
                    </tr>

                </table>

                <!-- COPYRIGHT -->
                <p style="font-size:12px; color:#aaa; margin-top:20px;">
                    © {{ date('Y') }} Aplikasi Kamu. All rights reserved.
                </p>

            </td>
        </tr>
    </table>

</body>
</html>