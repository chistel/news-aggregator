<?php

declare(strict_types=1);

namespace App\Jobs\Articles;

use App\Models\Article;
use App\Services\NewsAggregator\NewsAggregatorService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PullArticlesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        /** @var NewsAggregatorService $aggregator */
        $aggregator = app(NewsAggregatorService::class);
        $articles = $aggregator->fetchArticles();

        foreach ($articles as $article) {
            Article::updateOrCreate(
                ['url' => $article['url']],
                [
                    'title' => $article['title'],
                    'description' => $article['description'] ?? null,
                    'source' => $article['source'] ?? null,
                    'published_at' => $article['published_at'] ?? null,
                    'category' => $article['category'] ?? null,
                    'author' => $article['author'] ?? null,
                    'image_url' => $article['image_url'] ?? null,
                    'provider' => $article['provider'] ?? null,
                    //'content' => $article['content'] ?? null,
                ]
            );
        }

    }
}
