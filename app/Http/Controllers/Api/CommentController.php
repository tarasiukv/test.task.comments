<?php

namespace App\Http\Controllers\Api;

use App\Events\NewCommentAdded;

use App\Http\Controllers\Controller;
use App\Http\Requests\CommentRequest;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Services\CommentFileService;
use App\Models\CommentFile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PharIo\Version\Exception;
use Symfony\Component\HttpFoundation\Response;
use Validator;

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
            'descendants',
            'files'
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
        $files = $request->file('files');

        DB::beginTransaction();
        try {
            $user = User::where('email', $data['email'])->first();
            if (!$user) {
                $user = User::create($data);
            }
            $data['user_id'] = $user->id;

            $comment = Comment::create($data);
            if (!empty($files)) {
                $n = 0;
                foreach ($files as $file) {
                    if (!empty($file)) {
                        $file_path = CommentFileService::store($file, $comment, ++$n);
                        CommentFile::create([
                            'file_path' => $file_path,
                            'comment_id' => $comment->id,
                        ]);
                    }
                }
            }
            $comment->save();
            if ($comment) {
                NewCommentAdded::dispatch();
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Виникла помилка при збереженні коментаря.'], 500);
        }

        return new CommentResource($comment);
    }

    /**
     * Display the specified resource.
     */
    public function show(Comment $comment)
    {
        $comment->load(['user', 'files']);

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
