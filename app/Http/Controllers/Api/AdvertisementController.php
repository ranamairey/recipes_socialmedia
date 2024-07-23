<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Advertisement;
use App\Http\Controllers\Controller;
use App\Http\Resources\AdvertisementResource;
use App\Http\Resources\AdvertisementCollection;

use App\Mail\CustomEmail;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;

class AdvertisementController extends Controller
{

    public function index(Request $request): AdvertisementCollection
    {
        $this->authorize('view-any', Advertisement::class);

        $search = $request->get('search', '');

        $advertisements = Advertisement::search($search)
            ->latest()
            ->paginate();

        return new AdvertisementCollection($advertisements);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            // 'company' => 'required|string|max:255',
            // 'name' => 'required|string|max:255',
            'description' => 'required|string',
            'image_or_video' => 'required|file',
            //'type' => 'required|string|in:image,video',
        ]);
        if ($request->hasFile('image_or_video')) {
            $file = $request->file('image_or_video');
            $path = $file->store('images/advertisement');
        
            // الحصول على المسار الكامل للملف المحفوظ
            $storedPath = str_replace(storage_path('app'), 'C:\Users\scc-asus\Desktop\Project2\yam\storage\app', storage_path('app/' . $path));
        
            // تعيين المسار المحفوظ في المتغير المناسب
            $validatedData['image_or_video'] = $storedPath;
        }
        $advertisement = Advertisement::create([
            'description' => $validatedData['description'],
            'image_or_video' => $validatedData['image_or_video'],
            'user_id' => auth()->user()->id,
        ]);

        return response()->json(['message' => 'Advertisement created successfully', 'advertisement' => $advertisement], 201, [], JSON_UNESCAPED_UNICODE);
    }

    public function sendEmails($id)
    {

        set_time_limit(400);
        $advertisement = Advertisement::find($id);
        if ($advertisement) {
            // $numberOfUsers = 2;
            // $users = User::inRandomOrder()->take($numberOfUsers)->get();
            //$users = User::all();
            $user = User::find(4);
            if ($user) {
            //foreach ($users as $user) {
                $filePath = $advertisement->image_or_video;
                $file = Storage::get($filePath);
                $fileName = basename($filePath);
                $mimeType = Storage::mimeType($filePath);
                Mail::to($user->email)->send(new CustomEmail($advertisement, $file, $fileName, $mimeType));
            }
            return response()->json(['message' => 'Advertisement sent to all users successfully', 'advertisement' => $advertisement], 201, [], JSON_UNESCAPED_UNICODE);
        }
        return response()->json(['message' => 'Advertisement not found'], 400, [], JSON_UNESCAPED_UNICODE);
    }

    public function show($id)
    {
        $advertisement = Advertisement::find($id);
        if ($advertisement) {
            return response()->json(['advertisement' => $advertisement], 200);
        } else {
            return response()->json(['message' => 'Advertisement not found'], 404);
        }
    }
    public function update(Request $request, $id)
    {
        $advertisement = Advertisement::find($id);
        if ($advertisement) {
            $validatedData = $request->validate([
                //'name' => 'required|string|max:255',
                'description' => 'string',
                'image_or_video' => 'file',
                //'type' => 'string|in:image,video',
            ]);
            if ($request->hasFile('image_or_video')) {
                if ($advertisement->image_or_video) {
                    $fullPath= $advertisement->image_or_video;
                    $shortPath = str_replace("C:\\Users\\scc-asus\\Desktop\\Project2\\yam\\storage\\app/", "", $fullPath);
                    Storage::delete($shortPath);
                }
                $file = $request->file('image_or_video');
                $path = $file->store('images/advertisement');
            
                // الحصول على المسار الكامل للملف المحفوظ
                $storedPath = str_replace(storage_path('app'), 'C:\Users\scc-asus\Desktop\Project2\yam\storage\app', storage_path('app/' . $path));
            
                // تعيين المسار المحفوظ في المتغير المناسب
                $validatedData['image_or_video'] = $storedPath;
            }
            $advertisement->update($validatedData);
            return response()->json(['message' => 'Advertisement updated successfully', 'advertisement' => $advertisement], 200);
        } else {
            return response()->json(['message' => 'Advertisement not found'], 404);
        }
    }
    public function destroy($id)
    {
        $advertisement = Advertisement::find($id);
        if ($advertisement) {
            $fullPath= $advertisement->image_or_video;
            $shortPath = str_replace("C:\\Users\\scc-asus\\Desktop\\Project2\\yam\\storage\\app/", "", $fullPath);
            Storage::delete($shortPath);
            $advertisement->delete();
            return response()->json(['message' => 'Advertisement deleted successfully'], 200);
        } else {
            return response()->json(['message' => 'Advertisement not found'], 404);
        }
    }
}
