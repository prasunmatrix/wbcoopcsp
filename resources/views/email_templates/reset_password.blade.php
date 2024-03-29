<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
    <meta charset="utf-8"> <!-- utf-8 works for most cases -->
    <meta name="viewport" content="width=device-width"> <!-- Forcing initial-scale shouldn't be necessary -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge"> <!-- Use the latest (edge) version of IE rendering engine -->
    <meta name="x-apple-disable-message-reformatting">  <!-- Disable auto-scale in iOS 10 Mail entirely -->
    <title>Aludecor</title> <!-- The title tag shows in email notifications, like Android 4.4. -->
</head>

<body width="100%" style="margin: 0; padding: 0 !important; mso-line-height-rule: exactly; background-color: #f1f1f1;">
    <div style="width: 100%; background-color: #f1f1f1;">
        <div style="max-width: 600px; margin: 0 auto;background-color:#ffffff;font-family:Arial, Helvetica, sans-serif;">
            <!-- BEGIN BODY -->
            <table align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: auto;background-color:#ffffff;font-family:Arial, Helvetica, sans-serif;">
                <tr>
                    <td valign="middle" align="center" class="hero bg_white" style="padding:0; text-align:center">
                        <img src="https://www.demoyourprojects.com/aludecor/images/admin/logo.jpg" alt="" style="width: 200px; max-width: 600px; height: auto; margin: auto; display: block;">
                    </td>
                </tr><!-- end tr -->
                        <tr>
                <td valign="middle" class="hero bg_white" style="padding:0 25px;">
                    <table width="100%" style="margin: auto;">
                        <tr>
                            <td>
                                <div class="text" style="padding:0; text-align: center;">
                                    <h1 style="font-family:Arial, Helvetica, sans-serif;font-size:22px;line-height:32px; color:green;margin: 0 auto 20px;padding: 0;">{{ $app_config['appname'] }} Reset Password</h1>
                                    <h2 style="font-family:Arial, Helvetica, sans-serif;font-size:18px;line-height:20px; color:rgb(2, 2, 77);margin:auto 10px;padding: 0;text-align: left;">Hello {{ $user->full_name }}</h2>
                                    <p style="font-family:Arial, Helvetica, sans-serif;font-size:16px;line-height:18px; color:rgb(2, 2, 77);margin:0 auto 0px;padding: 0; text-align: left;">
                                        Your OTP for reset password is added below: 
                                    </p>
                                </div>
                            </td>
                        </tr>
                    </table>
                    <table width="100%" style="margin:15px auto 35px; border: 1px solid #e6e6e6;border-collapse: collapse;">
                        <thead>
                          <tr>
                            <th style="font-family:Arial, Helvetica, sans-serif;font-size:16px;line-height:18px; color:rgb(2, 2, 77);margin:0 auto 0px;padding:10px 5px; text-align: center;border: 1px solid #e6e6e6; background-color:#f6f6f6;">Username</th>
                            <th style="font-family:Arial, Helvetica, sans-serif;font-size:16px;line-height:18px; color:rgb(2, 2, 77);margin:0 auto 0px;padding:10px 5px; text-align: center;border: 1px solid #e6e6e6;background-color:#f6f6f6;">Email</th>
                            <th style="font-family:Arial, Helvetica, sans-serif;font-size:16px;line-height:18px; color:rgb(2, 2, 77);margin:0 auto 0px;padding:10px 5px; text-align: center;border: 1px solid #e6e6e6;background-color:#f6f6f6;">OTP</th>
                          </tr>
                        </thead>
                        <tbody>
                            <tr>
                              <td style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:18px; color:#000;margin:0 auto 0px;padding:10px 5px; text-align: center;border: 1px solid #e6e6e6;">
                                  @if(isset($user->full_name))
                                      {{ $user->full_name }}
                                  @else
                                      No Name found
                                  @endif
                              </td>
                              <td style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:18px; color:#000;margin:0 auto 0px;padding:10px 5px; text-align: center;border: 1px solid #e6e6e6;">
                                  @if(isset($user->email))
                                      {{ $user->email }}
                                  @else
                                      No Email found
                                  @endif
                              </td>
                              <td style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:18px; color:#000;margin:0 auto 0px;padding:10px 5px; text-align: center;border: 1px solid #e6e6e6;">
                                  @if(isset($app_config['OTP']))
                                      {{ $app_config['OTP'] }}
                                  @else
                                      No OTP found
                                  @endif   
                              </td>
                            </tr>
                          </tbody>
                    </table>
                </td>
                </tr><!-- end tr -->
            <!-- 1 Column Text + Button : END -->
            </table>
            <table align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: auto;background-color:green;font-family:Arial, Helvetica, sans-serif;">
                <tr>
                    <td style="text-align: center;padding:0 25px;">
                        <p style="font-family:Arial, Helvetica, sans-serif;font-size:16px;line-height:18px; color:#fff;margin:0 auto 0px;padding: 15px 0; text-align: center;">Copyright © 2020 {{ $app_config['appname'] }} Admin. All rights reserved.</p>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>