<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PostCategory;
use App\Models\Post;
use Illuminate\Support\Str;

class AdminPostController extends Controller
{
    public function index()
    {
        $posts = Post::with('rPostCategory')->orderBy('id','desc')->get();
        return view('admin.post.index',compact('posts'));
    }

    public function create()
    {
        $post_categories = PostCategory::orderBy('name', 'asc')->get();
        return view('admin.post.create', compact('post_categories'));
    }

    public function store(Request $request)
    {
        if(env('PROJECT_MODE') == 0) {
            return redirect()->back()->with('info', env('PROJECT_NOTIFICATION'));
        }

        $obj = new Post();
        
        $request->validate([
            'title' => ['required', 'unique:posts,title'],
            'slug' => ['required', 'alpha_dash', 'unique:posts,slug'],
            'description' => 'required',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'post_category_id' => 'required|exists:post_categories,id',
        ],[
            'title.required' => __('Title is required'),
            'slug.required' => __('Slug is required'),
            'slug.alpha_dash' =>  __('Slug can contain only letters, numbers, hyphens, and underscores'),
            'slug.unique' => __('Slug must be unique'),
            'description.required' => __('Description is required'),
            'photo.image' => __('Photo must be an image'),
            'photo.mimes' => __('Photo must be jpeg, png, jpg or gif'),
            'photo.max' => __('Photo size must not exceed 2MB'),
            'post_category_id.required' => __('Category is required'),
            'post_category_id.exists' => __('Selected category does not exist'),
        ]);

        // Gestion des tags
        if($request->tags == null) {
            $tags = '';
        } else {
            $tags = implode(',', $request->tags);
        }

        // Gestion de l'upload de photo
        if($request->hasFile('photo')) {
            // Créer le dossier uploads s'il n'existe pas
            if (!file_exists(public_path('uploads'))) {
                mkdir(public_path('uploads'), 0755, true);
            }
            
            $final_name = 'post_'.time().'.'.$request->photo->extension();
            $request->photo->move(public_path('uploads'), $final_name);
            $obj->photo = $final_name;
        }
        
        $obj->post_category_id = (int)$request->post_category_id;
        $obj->title = $request->title;
        $obj->slug = strtolower($request->slug);
        $obj->description = $request->description;
        $obj->tags = $tags;
        $obj->seo_title = $request->seo_title;
        $obj->seo_meta_description = $request->seo_meta_description;
        $obj->save();

        return redirect()->route('admin_post_index')->with('success', __('Data is added successfully'));
    }

    public function edit($id)
    {
        $post = Post::find($id);
        $post_categories = PostCategory::orderBy('name', 'asc')->get();
        $post_tags = explode(',',$post->tags);
        return view('admin.post.edit', compact('post', 'post_categories', 'post_tags'));
    }

    public function update(Request $request, $id)
    {
        if(env('PROJECT_MODE') == 0) {
            return redirect()->back()->with('info', env('PROJECT_NOTIFICATION'));
        }

        $obj = Post::find($id);
        
        if (!$obj) {
            return redirect()->route('admin_post_index')->with('error', __('Post not found'));
        }
        
        $request->validate([
            'title' => ['required', 'unique:posts,title,'.$id],
            'slug' => ['required', 'alpha_dash', 'unique:posts,slug,'.$id],
            'description' => ['required'],
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'post_category_id' => 'required|exists:post_categories,id',
        ],[
            'title.required' => __('Title is required'),
            'slug.required' => __('Slug is required'),
            'slug.alpha_dash' =>  __('Slug can contain only letters, numbers, hyphens, and underscores'),
            'slug.unique' => __('Slug must be unique'),
            'description.required' => __('Description is required'),
            'photo.image' => __('Photo must be an image'),
            'photo.mimes' => __('Photo must be jpeg, png, jpg or gif'),
            'photo.max' => __('Photo size must not exceed 2MB'),
            'post_category_id.required' => __('Category is required'),
            'post_category_id.exists' => __('Selected category does not exist'),
        ]);

        // Gestion des tags
        if($request->tags == null) {
            $tags = '';
        } else {
            $tags = implode(',', $request->tags);
        }

        // Gestion de l'upload de photo UNIQUEMENT si une nouvelle photo est uploadée
        if($request->hasFile('photo')) {
            // Créer le dossier uploads s'il n'existe pas
            if (!file_exists(public_path('uploads'))) {
                mkdir(public_path('uploads'), 0755, true);
            }

            // Supprimer l'ancienne photo SEULEMENT si elle existe physiquement
            if($obj->photo != null && file_exists(public_path('uploads/'.$obj->photo))) {
                unlink(public_path('uploads/'.$obj->photo));
            }
            
            // Uploader la nouvelle photo
            $final_name = 'post_'.time().'.'.$request->photo->extension();
            $request->photo->move(public_path('uploads'), $final_name);
            $obj->photo = $final_name;
        }
        // Si pas de nouvelle photo, on garde l'ancienne (pas de changement sur $obj->photo)

        // Mise à jour des autres champs
        $obj->post_category_id = (int)$request->post_category_id;
        $obj->title = $request->title;
        $obj->slug = strtolower($request->slug);
        $obj->description = $request->description;
        $obj->tags = $tags;
        $obj->seo_title = $request->seo_title;
        $obj->seo_meta_description = $request->seo_meta_description;
        $obj->update();

        return redirect()->route('admin_post_index')->with('success', __('Data is updated successfully'));
    }

    public function destroy($id)
    {
        if(env('PROJECT_MODE') == 0) {
            return redirect()->back()->with('info', env('PROJECT_NOTIFICATION'));
        }

        $obj = Post::find($id);
        
        if (!$obj) {
            return redirect()->route('admin_post_index')->with('error', __('Post not found'));
        }
        
        // Supprimer la photo SEULEMENT si elle existe physiquement
        if($obj->photo != null && file_exists(public_path('uploads/'.$obj->photo))) {
            unlink(public_path('uploads/'.$obj->photo));
        }
        
        $obj->delete();

        return redirect()->route('admin_post_index')->with('success', __('Data is deleted successfully'));
    }
}