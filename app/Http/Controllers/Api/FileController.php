<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\CommentFileResource;
use App\Models\CommentFile;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class FileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $model = CommentFile::with('comment');
        return CommentFileResource::collection($model->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     */
    public function show(CommentFile $picture)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CommentFile $picture)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CommentFile $picture)
    {
        //
    }
}

