<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    public function apiIndex()
    {
        $banners = Banner::with('user', 'category')->all();
        return response()->json($banners);
    }

    public function apiStore(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'category_id' => 'required',
            'description' => 'required|min:10'
        ]);

        $path = "https://via.placeholder.com/720x480";

        if ($request->hasFile('thumbnail')) {
            $path =  $request->file('thumbnail')->store('thumbnail');
        }

        $banner = new Banner();
        $banner->user_id = $request->user()->id;
        $banner->category_id = $request->input('category_id');
        $banner->thumbnail = $path;
        $banner->title = $request->input('title');
        $banner->description = $request->input('description');
        $banner->save();
        return response()->json($banner);
    }


    public function apibanner($id)
    {
        $banner = Banner::with('user', 'category')->find($id);
        return response()->json($banner);
    }

    public function apiUpdate(Request $request, $id)
    {
        $request->validate([
            'title' => 'required',
            'category_id' => 'required',
            'description' => 'required|min:10'
        ]);

        $banner = Banner::with('user', 'category')->find($id);

        $path = $banner->path;

        if ($request->hasFile('thumbnail')) {
            $path =  $request->file('thumbnail')->store('thumbnail');
        }
        $banner->user_id = $request->user()->id;
        $banner->category_id = $request->input('category_id');
        $banner->thumbnail = $path;
        $banner->title = $request->input('title');
        $banner->description = $request->input('description');
        $banner->save();
        return response()->json($banner);
    }

    public function apiDestroy($id)
    {
        $banner = Banner::find($id);
        $banner->delete();
        return response()->json(['message' => 'Delete banner successfuly']);
    }
}
