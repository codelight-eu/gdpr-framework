<?php

namespace Codelight\GDPR\Admin;

class AdminNotice
{
    protected $template;

    protected $data;

    /**
     * todo: replace with a proper factory pattern via gdpr()?
     *
     * AdminNotice constructor.
     */
    public function __construct()
    {
        if (did_action('admin_notices')) {
            trigger_error('AdminNotice class called incorrectly - admin_notices action has already ran!', E_USER_ERROR);
        }

        add_action('admin_notices', [$this, 'render'], 9999);
    }

    public function add($template, $data = [])
    {
        $this->template = $template;
        $this->data = $data;
    }

    public function render()
    {
        if (!$this->template) {
            trigger_error('Template not set for admin notice!', E_USER_ERROR);
        }

        echo gdpr('view')->render('admin/notices/header');
        echo gdpr('view')->render($this->template, $this->data);
        echo gdpr('view')->render('admin/notices/footer');
    }
}
