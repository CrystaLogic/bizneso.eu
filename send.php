<?php

  $name = $_POST['name'];
  $email = $_POST['email'];
  $from = 'powiadomienia@bizneso.eu';
  $subject = 'Enquire about dotSquare';
  $headers = "From: ".$from."\r\n";
  $headers .= "Reply-To: ".$from. "\r\n";
  $headers .= "MIME-Version: 1.0\r\n";
  $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

  $message = '<html><body>';
  $message .= '<table width="752px" cellpadding="0" cellspacing="0" align="center">';
  $message .= '<tbody><tr>';
  $message .= '<td>';
  $message .= '<table cellpadding="0px" cellspacing="0px">';
  $message .= '<tbody><tr>';
  $message .= '<td>';
  $message .= '<img src="http://bizneso.eu/images/mail/bizneso.jpg" style="border:none">';
  $message .= '</td>';
  $message .= '</tr>';
  $message .= '</tbody></table>';
  $message .= '</td>';
  $message .= '</tr>';
  $message .= '<tr>';
  $message .= '<td>';
  $message .= '<table style="width:752px;padding-top:12px;margin:0 auto;border:1px solid #d9d9d9;font-family:Arial;border-top:none">';
  $message .= '<tbody><tr>';
  $message .= '<td height="40" colspan="2" style="padding-left:12px;line-height:24px;font-size:24px">';
  $message .= '            Dear Admin (ąęćźżół)';
  $message .= '            </td>';
  $message .= '</tr>';
  $message .= '<tr>';
  $message .= '<td height="40" style="font-size:14px;color:#666666;line-height:24px;padding-left:12px">';
  $message .= '           You have received an Inquiry from <a href="DotSquare" target="_blank">http://nasfactor.com/themes/dotsquare/html/</a>! ';
  $message .= '</td>';
  $message .= '</tr>';
  $message .= '<tr>';
  $message .= '<td style="font-size:14px;color:#dc0000;line-height:24px;padding-left:12px" height="40">';
  $message .= '            Details are as below:';
  $message .= '            </td>';
  $message .= '</tr>';
  $message .= '<tr>';
  $message .= '<td>';
  $message .= '<table style="line-height:35px;font-size:14px;margin-left:12px;font-family:Arial,Helvetica,sans-serif">';
  $message .= '<tbody><tr>';
  $message .= '<td width="183px" style="font-weight:bold">Name:</td>';
  $message .= '<td>' . strip_tags($name) . '</td>';
  $message .= '</tr>';
  $message .= '<tr>';
  $message .= '<td width="183px" style="font-weight:bold">Email Address:</td>';
  $message .= '<td><a href="mailto:$email" target="_blank">' . strip_tags($email) . '</a></td>';
  $message .= '</tr>';
  $message .= '<tr>';
  $message .= '<td width="183px" style="font-weight:bold;display:block">Details:</td>';
  $message .= '<td style="line-height:24px"></td>';
  $message .= '</tr>';
  $message .= '<tr>';
  $message .= '<td colspan="2" width="183px" style="display:block">Regards,</td>';
  $message .= '</tr>';
  $message .= '<tr>';
  $message .= '<td colspan="2" width="183px" style="font-weight:bold;display:block">dotsquare.com</td>';
  $message .= '</tr>';
  $message .= '</tbody></table>';
  $message .= '</td>';
  $message .= '</tr>';
  $message .= '</tbody></table>';
  $message .= '</td>';
  $message .= '</tr>';
  $message .= '</tbody></table>';
  $message .= "</body></html>";
  if (filter_var($email, FILTER_VALIDATE_EMAIL))
  {
    mail($email, $subject, $message, $headers);
    echo "<div class='success'><div class='text-msg'>Dziękuję za zapis na darmowy webinar pełen marketingu i biznesu! Za moment otrzymasz e-mail z linkiem do webinaru z informacją o jego terminie.<br><br>Ilość miejsc jest ograniczona, dlatego proszę Cię bądź kilka minut wcześniej.<br><small>PS: Jeśli nie otrzymałeś/aś maila poszukaj w spamie albo skontaktuj się ze mną</small></div></div>";
  }
  else
  {
    echo "<div class='error-msg'>Błędny e-mail, spróbuj ponownie</div>";
  }

?>
