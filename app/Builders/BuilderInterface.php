<?php 

namespace App\Builders;

interface BuilderInterface
{
    public function addTitle(string $title): self;
    public function addContent(?string $content): self;
    public function addImages(?array $images): self;
    public function addTags(?string $tags): self;
    public function savePost(): self;
    public function getPost(): \App\Models\Forum; // Returns the constructed Forum post
}
