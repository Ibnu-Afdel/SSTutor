<?php

/*
 * This file is part of the Chapa Laravel package.
 *
 * Kidus Yared - @kidus363 <kidusy@chapa.co>
 *
 * 
 */
return [


    /**
     * Secret Key: Your Chapa secretKey. Sign up on https://dashboard.chapa.co/ to get one from your settings page
     *
     */
    'secret_key' => env('CHAPA_SECRET_KEY'),
    'base_url' => env('CHAPA_BASE_URL', 'https://api.chapa.co/v1'),



];