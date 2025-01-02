<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

use Illuminate\Validation\ValidationException;
use App\Models\Forum;
use App\Models\User;
use App\Models\ForumComment;
use App\Models\Tag;
use App\Models\ReportedPost;
use App\Builders\BuilderInterface;
use App\Providers\XSLTTransformation;

class ForumController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Simulate logging in as a specific user (for development purposes)
        if (!Auth::check()) {
            // If the user is not already logged in
            $user = User::find(1); // Replace with the user ID you want to log in
            Auth::login($user);
        }

        // Load posts along with tags, likes, comments, and users
        $posts = Forum::with(['likes', 'comments', 'user', 'user.followers', 'tags'])->get()->reverse()->values();

        return view('forum.forumHome', [
            'posts' => $posts,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, BuilderInterface $builder)
    {
        try {
            // Validate the form input
            $validatedData = $request->validate([
                'title' => 'required|string|max:255',
                'content' => 'nullable|string',
                'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
                'tag' => 'nullable|string',
            ]);


            // Build the post using the builder
            $builder->addTitle($validatedData['title'])
                ->addContent($validatedData['content'])
                ->savePost();

            $builder->addImages($request->file('images'));
            $builder->addTags($validatedData['tag']);

            return redirect()->route('forum.index')->with('success', 'Post created successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to create post.');
        }
    }


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // Fetch the forum post and eager load the 'likes' relationship
        $post = Forum::with(['likes', 'comments', 'user', 'user.followers', 'tags'])->findOrFail($id);

        // Count the number of users who liked the post
        $postLikes = $post->likes->count();

        // Check if the current user has liked the post
        $currentUserId = Auth::id(); // Replace with Auth::id() when implementing authentication
        $userHasLiked = $post->likes->contains($currentUserId);

        // Fetch parent comments (where 'parent' is NULL) and eager load 'children' and 'likes'
        $comments = ForumComment::with(['likes', 'children'])
            ->where('forum_id', $id)
            ->whereNull('parent') // Only get parent comments
            ->get();

        // For each parent comment, check if the current user has liked it and get likes count for each
        foreach ($comments as $comment) {
            $comment->likes_count = $comment->likes->count();
            $comment->userHasLiked = $comment->likes->contains($currentUserId);

            // For each child comment (reply), check likes as well
            foreach ($comment->children as $childComment) {
                $childComment->likes_count = $childComment->likes->count();
                $childComment->userHasLiked = $childComment->likes->contains($currentUserId);
            }
        }

        return view('forum.forumShow', [
            'post' => $post,
            'postLikes' => $postLikes,
            'userHasLiked' => $userHasLiked,
            'comments' => $comments,
        ]);
    }

    // Get all posts from following list
    public function following()
    {
        // Get the current logged-in user ID (use Auth::id() when authentication is implemented)
        $currentUserId = Auth::id(); // Replace with Auth::id()

        // Get the IDs of users that the current user is following
        $followingUserIds = DB::table('follows')
            ->where('follower_id', $currentUserId)
            ->pluck('followee_id');

        // Fetch all forum posts where the user_id is in the list of followed users
        $posts = Forum::whereIn('user_id', $followingUserIds)->with(['likes', 'comments', 'user', 'user.followers', 'tags'])->get()->reverse()->values();


        return view('forum.forumFollowing', ['posts' => $posts,]);
    }

    // Get own posts
    public function mine()
    {
        // Get the current logged-in user ID (use Auth::id() when authentication is implemented)
        $currentUserId = Auth::id(); // Replace with Auth::id()

        // Fetch all forum posts where the user_id is in the list of followed users
        $posts = Forum::where('user_id', $currentUserId)->with([
            'likes',
            'comments',
            'user',
            'user.followers',
            'tags'
        ])->get()->reverse()->values();


        return view('forum.myForums', [
            'posts' => $posts,
        ]);
    }


    // Add this method to filter posts by a tag
    public function postsByTag($tag_name)
    {
        // Get the tag by name
        $tag = Tag::where('tag_name', $tag_name)->firstOrFail();

        // Get posts that have this tag
        $posts = $tag->forums()->with(['likes', 'comments', 'user', 'user.followers', 'tags'])->get()->reverse()->values();

        return view('forum.forumTag', [
            'tag_name' => $tag_name,
            'posts' => $posts,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $post = Forum::with(['tags'])->findOrFail($id);

        // Define the path for the images
        $imageDir = public_path("uploads/forum_{$id}");

        // Check if the directory exists and retrieve the images
        $existingImages = [];
        if (is_dir($imageDir)) {
            $images = File::files($imageDir); // Assuming you're using Laravel's File facade
            foreach ($images as $image) {
                $existingImages[] = asset("uploads/forum_{$id}/" . $image->getFilename());
            }
        }

        return view('forum.forumEdit', [
            'post' => $post,
            'existingImages' => $existingImages
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //dd($request);
        try {
            // Validate the form input
            $validatedData = $request->validate([
                'title' => 'required|string|max:255',
                'content' => 'nullable|string',
                'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
                'deleted_files' => 'nullable|string', // Optional deleted files
                'tag' => 'nullable|string',
            ]);

            // Retrieve the post to update
            $post = Forum::findOrFail($id);
            $post->title = $validatedData['title'];
            $post->content = $validatedData['content'];
            $post->save(); // Save the updated post

            // Handle image deletion
            // Example of the deleted files you are receiving as URLs
            $deletedFiles = json_decode($request->input('deleted_files'), true);

            foreach ($deletedFiles as $fileUrl) {
                // Convert the URL to the actual file path in the 'public' directory
                $filePath = str_replace(url('/'), '', $fileUrl); // Removes the 'http://127.0.0.1:8000' part

                $fullPath = public_path($filePath); // This gives you the full file path in the public directory

                if (File::exists($fullPath)) {
                    // Delete the file
                    File::delete($fullPath);
                    echo "Deleted: " . $fullPath; // Optional for debugging
                } else {
                    echo "File not found: " . $fullPath; // Optional for debugging
                }
            }

            // Step 2: Handle the image uploads
            if ($request->hasFile('images')) {
                $uploadPath = public_path("uploads/forum_{$id}"); // Path based on the forum ID

                // Create the directory if it doesn't exist
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0777, true); // Create folder with permissions
                }

                foreach ($request->file('images') as $image) {
                    $imageName = time() . '-' . $image->getClientOriginalName(); // Unique image name
                    $image->move($uploadPath, $imageName); // Move the image to the folder
                }
            }

            // Step 3: Handle the tags
            $tags = $validatedData['tag'];

            if ($tags) {
                // Step 3.1: Split the tags by space and limit to 3 tags
                $tagsArray = explode(' ', $tags);
                $tagsArray = array_slice($tagsArray, 0, 3); // Ensure only max 3 tags are processed

                // Step 3.2: Remove existing tags for the forum post
                DB::table('forum_tag')->where('forum_id', $id)->delete();

                foreach ($tagsArray as $tag) {
                    $tag = strtolower(trim($tag)); // Normalize the tag (lowercase and trimmed)
                    if ($tag) {
                        // Step 3.3: Check if the tag already exists in the 'tags' table
                        $tagRecord = Tag::firstOrCreate(['tag_name' => $tag]);

                        // Step 3.4: Insert into the pivot table 'forum_tag'
                        DB::table('forum_tag')->insert([
                            'forum_id' => $id,
                            'tag_id' => $tagRecord->tag_id,
                        ]);
                    }
                }
            }

            // Step 4: Redirect or return a response
            return redirect()->route('forum.show', ['id' => $id])->with('success', 'Updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update post.');
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            // Step 1: Find the forum post by ID
            $forumPost = Forum::findOrFail($id); // Assuming you have a Forum model

            if ($forumPost->user_id != Auth::id()) {
                return redirect()->back()->with('error', 'Delete failed');
            }

            // Step 2: Delete the associated images and directory
            $uploadPath = public_path("uploads/forum_{$id}");

            if (File::exists($uploadPath)) {
                // Delete all files in the directory
                File::deleteDirectory($uploadPath);
            }

            // Step 3: Delete the post record from the database
            $forumPost->delete();
            // Step 4: Redirect or return a response
            return redirect()->back()->with('success', 'Post deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong. Try again later!');
        }
    }

    public function report(Request $request)
    {
        try {
            $request->validate([
                'forum_id' => 'required|exists:forums,forum_id',
                'reason' => 'required|string|max:500',
            ]);

            $existingReport = ReportedPost::where('forum_id', $request->forum_id)
                ->where('user_id', Auth::id())
                ->first();

            if ($existingReport) {
                $existingReport->reason = $request->reason;
                $existingReport->save();
            } else {
                $newReport = new ReportedPost();
                $newReport->forum_id = $request->forum_id;
                $newReport->user_id = Auth::id();
                $newReport->reason = $request->reason;
                $newReport->save();
            }

            return redirect()->back()->with('success', 'Report submitted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong. Try again later!');
        }
    }

    // Generate Top Posts XML
    public function generateTopPostsXML()
    {
        // Path to the generated XML file
        $xmlFilePath = public_path('xml/posts.xml');

        // Path to the XSL file you created
        $xslFilePath = resource_path('views/xsl/top_posts.xsl');

        // Get the top posts based on likes and comments
        $posts = Forum::withCount(['likes', 'comments'])
            ->orderByDesc('likes_count')
            ->orderByDesc('comments_count')
            ->limit(10)
            ->get();

        // Create an XML structure
        $xml = new \SimpleXMLElement('<posts/>');

        foreach ($posts as $post) {
            $postElement = $xml->addChild('post');
            $postElement->addChild('title', htmlspecialchars($post->title));
            $postElement->addChild('likes', $post->likes_count);
            $postElement->addChild('comments', $post->comments_count);
        }

        $xml->asXML($xmlFilePath);

        // Perform the XSL transformation using the XSLTTransformation provider
        $xslt = new XSLTTransformation($xmlFilePath, $xslFilePath);
        $transformedXml = $xslt->getTransformedXml();

        // Return the transformed HTML as the response
        return response($transformedXml);
    }

    // Generate Most Used Tags XML
    public function generateMostUsedTagsXML()
    {
        // Path to the generated XML file
        $xmlFilePath = public_path('xml/tags.xml');

        // Path to the XSL file you created
        $xslFilePath = resource_path('views/xsl/most_used_tags.xsl');

        // Get the most used tags based on usage in posts
        $tags = Tag::select('tags.tag_name', DB::raw('COUNT(forum_tag.forum_id) as count'))
            ->join('forum_tag', 'tags.tag_id', '=', 'forum_tag.tag_id')
            ->groupBy('tags.tag_name')
            ->orderByDesc('count')
            ->limit(10)
            ->get();

        // Create an XML structure
        $xml = new \SimpleXMLElement('<tags/>');

        foreach ($tags as $tag) {
            $tagElement = $xml->addChild('tag');
            $tagElement->addChild('name', htmlspecialchars($tag->tag_name));
            $tagElement->addChild('count', $tag->count);
        }

        $xml->asXML($xmlFilePath);

        // Perform the XSL transformation using the XSLTTransformation provider
        $xslt = new XSLTTransformation($xmlFilePath, $xslFilePath);
        $transformedXml = $xslt->getTransformedXml();

        // Return the transformed HTML as the response
        return response($transformedXml);
    }

    public function reportPage()
    {
        return view('forum.forumReport');
    }
}
