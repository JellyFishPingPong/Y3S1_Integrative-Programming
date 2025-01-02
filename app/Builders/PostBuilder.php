<?php

namespace App\Builders;

use App\Models\Forum;
use App\Models\Tag;
use Illuminate\Support\Facades\Auth;

class PostBuilder implements BuilderInterface
{
    protected $post;

    public function __construct()
    {
        $this->post = new Forum();
        $this->post->user_id = Auth::id(); // Set the authenticated user as the post owner
    }

    public function addTitle(string $title): self
    {
        $this->post->title = $title;
        return $this;
    }

    public function addContent(?string $content): self
    {
        $this->post->content = $content ?? '';
        return $this;
    }

    public function addImages(?array $images): self
    {
        if ($images) {
            $forumId = $this->post->forum_id; // Handle case where forum_id might not be set

            $uploadPath = public_path("uploads/forum_{$forumId}");

            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            foreach ($images as $image) {
                $imageName = time() . '-' . $image->getClientOriginalName();
                $image->move($uploadPath, $imageName);
            }
        }
        return $this;
    }

    public function addTags(?string $tags): self
    {
        if ($tags) {
            $tagsArray = explode(' ', $tags);
            $tagsArray = array_slice($tagsArray, 0, 3); // Limit to 3 tags

            foreach ($tagsArray as $tag) {
                $tag = strtolower(trim($tag));
                if ($tag) {
                    $tagRecord = Tag::firstOrCreate(['tag_name' => $tag]);
                    $this->post->tags()->attach($tagRecord->tag_id);
                }
            }
        }
        return $this;
    }

    public function savePost(): self
    {
        // Save the post to generate the forum_id
        $this->post->save();
        return $this;
    }

    public function getPost(): Forum
    {
        return $this->post;
    }
}
