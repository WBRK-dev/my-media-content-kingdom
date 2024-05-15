<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Thumbnail;

class ThumbnailController extends Controller
{
    /**
     * METHOD: GET
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * METHOD: GET
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * METHOD: POST
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * METHOD: GET
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $id = $request->input("id");
        return Thumbnail::findOrFail($id)->data;
    }

    /**
     * METHOD: GET
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * METHOD: POST
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * METHOD: POST
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
