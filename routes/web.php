<?php

use Illuminate\Support\Facades\Route;

/*
 * This file defines the URL routes for the app.
 *
 * For the workshop, you only need the root route ("/") which shows the
 * welcome page. The other route below is an example of how to add more pages.
 *
 * EXTENSION IDEA: Add your own route!
 * Example:
 *   Route::get('/about', function () {
 *       return view('about');
 *   });
 */

// The root route — shows the welcome page.
// This is what you see when you visit your Render URL.
Route::get('/', function () {
    return view('welcome');
});

// Example API route — returns JSON data.
// Visit /api/people to see this in action.
// (Extension: modify this to return data from your own project)
Route::get('/api/people', function () {
    return response()->json([
        'message' => 'Hello from the Deploy Now Workshop API!',
        'people' => [
            'Add your name here!',
        ],
    ]);
});
