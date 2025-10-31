<?php
namespace App\Controllers;

class Controller
{
    protected array $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    protected function view(string $template, array $data = []): void
    {
        $branding = array_merge($this->config['branding'] ?? [], $data['branding'] ?? []);
        $content = view($template, array_merge($data, [
            'branding' => $branding,
        ]));
        render('layouts/app', array_merge($data, [
            'slot' => $content,
            'branding' => $branding,
        ]));
    }
}
