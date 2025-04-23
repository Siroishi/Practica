<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class GenerateBlogPost extends Command
{

    protected $signature = 'blog:generate
   {--outputDir= : The output directory for the blog post}
   {--date-format=Y-m-d : The date format to use}';



    public function handle()
    {
        $title = $this->ask('Enter blog post title');
        $author = $this->ask('Enter author name');
        //Bonus 2 - categories separated by commas
        $category = $this->ask('Enter categories');

        //Bonus 1
        if (empty($title) || empty($author) || empty($category)) {
            $this->error('Title, author and category are required');
            return 1;
        }

        $categories = implode ('", "',array_map('trim', explode(',', $category)));


        $outputDir = $this->option('outputDir') ?: getcwd();

        $filename = Str::slug($title) . '-' . Str::slug($author) . '-' . date('YmdHi') . '.md';
        $filePath = $outputDir . '/' . $filename;


        $dateFormat = $this->option('date-format');
        $currentDate = Carbon::now()->format($dateFormat);


        $content = <<<MARKDOWN
---
title: "$title"
author: "$author"
categories: ["$categories"]
date: "$currentDate"
---

Write your blog post content here...
MARKDOWN;

        File::ensureDirectoryExists(dirname($filePath));
        File::put($filePath, $content);

        $this->info("Blog post template created successfully at: {$filePath}");

        return 0;
    }
}
