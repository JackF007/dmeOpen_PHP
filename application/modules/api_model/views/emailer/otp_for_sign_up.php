<?php
$this->db->where("template_name",'send_otp_signup_email_template');
$html =  $this->db->get("mailer")->row()->template_html;
$html = str_replace('{$otp}', $otp , $html);
echo $html;