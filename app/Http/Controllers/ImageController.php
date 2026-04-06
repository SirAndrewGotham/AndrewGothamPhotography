<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreImageRequest;
use App\Http\Requests\UpdateImageRequest;
use App\Models\Image;

class ImageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): void
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): void
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreImageRequest $request): void
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Image $image): void
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Image $image): void
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateImageRequest $request, Image $image): void
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Image $image): void
    {
        //
    }
}
