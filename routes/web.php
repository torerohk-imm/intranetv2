<?php
use App\Controllers\AdminController;
use App\Controllers\AnnouncementController;
use App\Controllers\AuthController;
use App\Controllers\DashboardController;
use App\Controllers\DirectoryController;
use App\Controllers\DocumentController;
use App\Controllers\EmbeddedSiteController;
use App\Controllers\EventController;
use App\Controllers\OrganigramController;
use App\Controllers\QuickLinkController;
use App\Controllers\ProfileController;
use App\Controllers\PreferenceController;

return [
    ['GET', '/', [DashboardController::class, 'index']],
    ['POST', '/dashboard/preferencias', [DashboardController::class, 'savePreferences']],

    ['GET', '/login', [AuthController::class, 'showLogin']],
    ['POST', '/login', [AuthController::class, 'login']],
    ['GET', '/logout', [AuthController::class, 'logout']],
    ['GET', '/perfil', [ProfileController::class, 'index']],
    ['GET', '/configuracion', [PreferenceController::class, 'index']],
    ['POST', '/configuracion', [PreferenceController::class, 'update']],

    ['GET', '/calendario', [EventController::class, 'index']],
    ['POST', '/calendario', [EventController::class, 'store']],
    ['POST', '/calendario/editar', [EventController::class, 'update']],
    ['POST', '/calendario/eliminar', [EventController::class, 'destroy']],

    ['GET', '/directorio', [DirectoryController::class, 'index']],
    ['POST', '/directorio', [DirectoryController::class, 'store']],
    ['POST', '/directorio/editar', [DirectoryController::class, 'update']],
    ['POST', '/directorio/eliminar', [DirectoryController::class, 'destroy']],

    ['GET', '/anuncios', [AnnouncementController::class, 'index']],
    ['POST', '/anuncios', [AnnouncementController::class, 'store']],
    ['POST', '/anuncios/editar', [AnnouncementController::class, 'update']],
    ['POST', '/anuncios/eliminar', [AnnouncementController::class, 'destroy']],

    ['GET', '/organigrama', [OrganigramController::class, 'index']],
    ['POST', '/organigrama', [OrganigramController::class, 'store']],
    ['POST', '/organigrama/editar', [OrganigramController::class, 'update']],
    ['POST', '/organigrama/eliminar', [OrganigramController::class, 'destroy']],

    ['GET', '/enlaces', [QuickLinkController::class, 'index']],
    ['POST', '/enlaces', [QuickLinkController::class, 'store']],
    ['POST', '/enlaces/eliminar', [QuickLinkController::class, 'destroy']],

    ['GET', '/sitios', [EmbeddedSiteController::class, 'index']],
    ['POST', '/sitios', [EmbeddedSiteController::class, 'store']],
    ['POST', '/sitios/eliminar', [EmbeddedSiteController::class, 'destroy']],

    ['GET', '/repositorio', [DocumentController::class, 'index']],
    ['POST', '/repositorio/carpeta', [DocumentController::class, 'storeFolder']],
    ['POST', '/repositorio/documento', [DocumentController::class, 'storeDocument']],
    ['POST', '/repositorio/documento/eliminar', [DocumentController::class, 'destroyDocument']],

    ['GET', '/administracion', [AdminController::class, 'index']],
    ['POST', '/administracion/branding', [AdminController::class, 'updateBranding']],
    ['POST', '/administracion/usuarios', [AdminController::class, 'storeUser']],
    ['POST', '/administracion/usuarios/editar', [AdminController::class, 'updateUser']],
    ['POST', '/administracion/usuarios/eliminar', [AdminController::class, 'destroyUser']],
];
