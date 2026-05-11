<?php

namespace App\Http\Controllers\Api;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{

public function up(): void
{
    Schema::create('posts', function (Blueprint $table) {
        $table->id();
        $table->string('image');
        $table->string('title');
        $table->text('content');
        $table->timestamps();
    });
}



    /**
     * index
     */
    public function index()
    {
        $posts = Post::latest()->paginate(5);
        return new PostResource(true, 'List Data Posts', $posts);
    }

    /**
     * store
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image'     => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'title'     => 'required',
            'content'   => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $image = $request->file('image');
        $image->storeAs('public/posts', $image->hashName());

        $post = Post::create([
            'image'     => $image->hashName(),
            'title'     => $request->title,
            'content'   => $request->content,
        ]);

        return new PostResource(true, 'Data Post Berhasil Ditambahkan!', $post);
    }

    /**
     * show
     */
    public function show($id)
    {
        // Gunakan find atau fail agar jika ID salah, tidak error 500
        $post = Post::find($id);

        if (!$post) {
            return response()->json([
                'success' => false,
                'message' => 'Data Post Tidak Ditemukan!',
            ], 404);
        }

        return new PostResource(true, 'Detail Data Post!', $post);
    }

    /**
     * update
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title'     => 'required',
            'content'   => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $post = Post::find($id);

        // Proteksi jika data tidak ada
        if (!$post) {
            return response()->json([
                'success' => false,
                'message' => 'Data Post Tidak Ditemukan!',
            ], 404);
        }

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $image->storeAs('public/posts', $image->hashName());

            // Hapus gambar lama
            Storage::delete('public/posts/'.basename($post->image));

            $post->update([
                'image'     => $image->hashName(),
                'title'     => $request->title,
                'content'   => $request->content,
            ]);
        } else {
            $post->update([
                'title'     => $request->title,
                'content'   => $request->content,
            ]);
        }

        return new PostResource(true, 'Data Post Berhasil Diubah!', $post);
    }

    /**
     * destroy
     */
    public function destroy($id)
    {
        $post = Post::find($id);

        // Proteksi: Cek apakah post ada sebelum hapus gambar
        if (!$post) {
            return response()->json([
                'success' => false,
                'message' => 'Data Post Tidak Ditemukan!',
            ], 404);
        }

        // Hapus file gambar di storage
        Storage::delete('public/posts/'.basename($post->image));

        // Hapus data di database
        $post->delete();

        return new PostResource(true, 'Data Post Berhasil Dihapus!', null);
    }
}