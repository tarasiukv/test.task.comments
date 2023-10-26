<?php

  namespace App\Http\Controllers\Api;

  use App\Http\Controllers\Controller;
  use App\Http\Requests\CommentRequest;
  use App\Http\Resources\CommentResource;
  use App\Models\Comment;
  use Illuminate\Http\Request;
  use Symfony\Component\HttpFoundation\Response;

  class CommentController extends Controller
  {
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
      $per_page = 20;

      $model = Comment::with([
        'user',
      ]);

      return CommentResource::collection($model->paginate($per_page));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CommentRequest $request)
    {
      $comment = Comment::create($request->validated());

      return new CommentResource($comment);
    }

    /**
     * Display the specified resource.
     */
    public function show(Comment $comment)
    {
      $comment->load('user');

      return new CommentResource($comment);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CommentRequest $request, Comment $comment)
    {
      $comment->update($request->validated());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {
      $comment->delete();

      return response(null, Response::HTTP_NO_CONTENT);
    }
  }
