<?php
$branding = $branding ?? ['logo_path' => '/resources/img/logo.svg'];
$slot = '<div class="text-center">'
    . '<h1 class="h4 fw-semibold mb-3">Acceso denegado</h1>'
    . '<p class="text-muted">No cuentas con permisos suficientes para ingresar a este módulo.</p>'
    . '</div>';
include __DIR__ . '/../layouts/base.php';
