<?php
$this->db->where("template_name",'welcome_email_template');
$html =  $this->db->get("mailer")->row()->template_html;
$html = str_replace('{$name}', $name , $html);
echo $html;