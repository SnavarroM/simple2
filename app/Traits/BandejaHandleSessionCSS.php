<?php

namespace App\Traits;

use Illuminate\Http\Request;

trait BandejaHandleSessionCSS
{
    /*
    |--------------------------------------------------------------------------
    | BandejaHandleSessionCSS Trait
    |--------------------------------------------------------------------------
    |
    | Trait para manejar la clase css de 'active' en el menú lateral de categorias
    | de la vista del usuario frontend, dado que una vez seleccionado un tramite
    | o sección que saliera del contecto de las url
    |
    */

    function isCategoryBandejaActive(Request $request, $categoryName) {
        if ($request->session()->exists('bandeja_active')) {
            $request->session()->put('bandeja_active', $categoryName);
        }
    }

    function setupBandejaCategory(Request $request) {
        if (!$request->session()->has('bandeja_active')) {
            $request->session()->put('bandeja_active', null);
        }
    }

    function deleteBandejaCategory(Request $request) {
        $request->session()->forget('bandeja_active');
    }
}

