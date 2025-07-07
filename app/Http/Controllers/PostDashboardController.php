<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostDashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
				$posts = Post::latest()->where('author_id', Auth::user()->id);

				if (request('keywords')) {
						$posts->where('title', 'like', '%' . request('keywords') . '%');
				}
        return view('dashboard.index', ['posts' => $posts->paginate(7)]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

				$request->validate([
						'title' => 'required|min:4|max:255|unique:posts,title',
						'category_id' => 'required',
						'body' => 'required',
				], [
						'title.required' => 'Judul harus diisi',
						'title.min' => 'Judul minimal 4 karakter',
						'title.max' => 'Judul maksimal 255 karakter',
						'title.unique' => 'Judul sudah ada, silakan gunakan judul lain',
						'category_id.required' => 'Kategori harus dipilih',
						'body.required' => 'Isi konten harus diisi',
				]);

				Post::create([
						'title' => $request->title,
						'author_id' => Auth::user()->id,
						'category_id' => $request->category_id,
						'slug' => Str::slug($request->title),
						'body' => $request->body,
				]);
        return redirect('/dashboard')->with(['success' => 'Postingan berhasil dibuat!']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
		{
				return view('dashboard.show', ['post' => $post]);
		}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        return view('dashboard.edit', ['post' => $post]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        // Validasi input
				$request->validate([
						'title' => 'required|min:4|max:255|unique:posts,title,' . $post->id,
						'category_id' => 'required',
						'body' => 'required',
				], [
						'title.required' => 'Judul harus diisi',
						'title.min' => 'Judul minimal 4 karakter',
						'title.max' => 'Judul maksimal 255 karakter',
						'title.unique' => 'Judul sudah ada, silakan gunakan judul lain',
						'category_id.required' => 'Kategori harus dipilih',
						'body.required' => 'Isi konten harus diisi',
				]);

				// Update postingan
				$post->update([
						'title' => $request->title,
						'author_id' => Auth::user()->id,
						'category_id' => $request->category_id,
						'slug' => Str::slug($request->title),
						'body' => $request->body,
				]);

				// Redirect ke halaman dashboard dengan pesan sukses
				return redirect('/dashboard')->with(['success' => 'Postingan berhasil diperbarui!']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
		{
				$post->delete();
				return redirect('/dashboard')->with(['success' => 'Postingan berhasil dihapus!']);
		}
}
