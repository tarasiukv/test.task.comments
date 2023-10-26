<?php

  namespace App\Http\Controllers\Api;

  use App\Http\Controllers\Controller;
  use App\Http\Requests\CommentRequest;
  use App\Http\Resources\CommentResource;
  use App\Models\Comment;
  use App\Models\User;
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
        'descendantsAndSelf',
      ])->whereNull('comment_id')
        ->paginate($per_page);

      return CommentResource::collection($model);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CommentRequest $request)
    {
      $data = $request->validated();

      $user = User::where('email', $data['email'])->first();
      if (!$user) {
        $user = User::create($data);
      }
      $data['user_id'] = $user->id;

      $comment = Comment::create($data);

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
