<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $t) {
            $t->boolean('is_admin')->default(false)->after('password');
        });

        Schema::create('categories', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('slug')->unique();
            $t->text('description')->nullable();
            $t->unsignedSmallInteger('sort')->default(0);
            $t->timestamps();
        });

        Schema::create('authors', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('slug')->unique();
            $t->string('role')->nullable();
            $t->text('bio')->nullable();
            $t->string('photo')->nullable();
            $t->string('twitter')->nullable();
            $t->string('linkedin')->nullable();
            $t->string('website')->nullable();
            $t->timestamps();
        });

        Schema::create('articles', function (Blueprint $t) {
            $t->id();
            $t->string('title');
            $t->string('slug')->unique();
            $t->text('excerpt')->nullable();
            $t->longText('body')->nullable();
            $t->string('kind')->default('news'); // news | insight | guide
            $t->string('image')->nullable();
            $t->string('image_alt')->nullable();
            $t->string('image_caption')->nullable();
            $t->foreignId('author_id')->nullable()->constrained()->nullOnDelete();
            $t->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $t->string('country')->nullable();
            $t->string('status')->default('draft'); // draft | published | scheduled
            $t->timestamp('published_at')->nullable();
            $t->boolean('is_featured')->default(false);
            $t->boolean('is_breaking')->default(false);
            $t->boolean('is_sponsored')->default(false);
            $t->boolean('is_press_release')->default(false);
            $t->string('sponsor_name')->nullable();
            $t->string('seo_title')->nullable();
            $t->string('meta_description')->nullable();
            $t->string('og_image')->nullable();
            $t->text('sources')->nullable();
            $t->unsignedSmallInteger('reading_time')->default(3);
            $t->boolean('is_demo')->default(false);
            $t->timestamps();
            $t->index(['status', 'published_at']);
        });

        Schema::create('tags', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('slug')->unique();
            $t->timestamps();
        });

        Schema::create('article_tag', function (Blueprint $t) {
            $t->foreignId('article_id')->constrained()->cascadeOnDelete();
            $t->foreignId('tag_id')->constrained()->cascadeOnDelete();
            $t->primary(['article_id', 'tag_id']);
        });

        Schema::create('advertisements', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('advertiser');
            $t->string('placement');
            $t->string('banner')->nullable();
            $t->string('headline')->nullable();
            $t->string('url');
            $t->date('starts_at')->nullable();
            $t->date('ends_at')->nullable();
            $t->boolean('active')->default(true);
            $t->unsignedInteger('clicks')->default(0);
            $t->timestamps();
        });

        Schema::create('newsletter_subscribers', function (Blueprint $t) {
            $t->id();
            $t->string('email')->unique();
            $t->string('source')->nullable();
            $t->timestamps();
        });

        Schema::create('contact_submissions', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('email');
            $t->string('topic')->nullable();
            $t->text('message');
            $t->timestamps();
        });

        Schema::create('tip_submissions', function (Blueprint $t) {
            $t->id();
            $t->string('name')->nullable();
            $t->string('email')->nullable();
            $t->string('subject');
            $t->text('details');
            $t->string('country')->nullable();
            $t->timestamps();
        });

        Schema::create('site_settings', function (Blueprint $t) {
            $t->id();
            $t->string('key')->unique();
            $t->text('value')->nullable();
            $t->timestamps();
        });
    }

    public function down(): void
    {
        foreach (['site_settings','tip_submissions','contact_submissions','newsletter_subscribers','advertisements','article_tag','tags','articles','authors','categories'] as $tbl) {
            Schema::dropIfExists($tbl);
        }
        Schema::table('users', fn (Blueprint $t) => $t->dropColumn('is_admin'));
    }
};
