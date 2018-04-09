<?php


namespace Codelight\GDPR\Admin;


class AdminError extends AdminNotice
{
    public function render()
    {
        if (!$this->template) {
            trigger_error('Template not set for admin notice!', E_USER_ERROR);
        }

        echo gdpr('view')->render($this->template, $this->data);
    }
}