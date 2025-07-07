<x-layout :title="$title">

  <main class="pt-8 pb-16 lg:pt-16 lg:pb-24 bg-white dark:bg-gray-900 antialiased">
    <div class="flex justify-between px-4 mx-auto max-w-screen-xl ">
      <article
        class="mx-auto w-full max-w-4xl format format-sm sm:format-base lg:format-lg format-blue dark:format-invert">

        <a href="/posts" class="font-medium text-blue-500 hover:underline text-xs">&laquo; Back to all posts.</a>

        <header class="my-4 lg:mb-6 not-format">
          <address class="flex items-center mb-6 not-italic">
            <div class="inline-flex items-center mr-3 text-sm text-gray-900 dark:text-white">
              <img class="mr-4 w-16 h-16 rounded-full"
                src="{{ $post->author->avatar ? asset('storage/' . $post->author->avatar) : asset('img/avatar_default.png') }}"
                alt="{{ $post->author->name }}" />
              <div>
                <a href="/posts?author={{ $post->author->username }}" rel="author"
                  class="text-xl font-bold text-gray-900 dark:text-white">{{ $post->author->name }}</a>
                <a href="/posts?category={{ $post->category->slug }}" class="block">
                  <span
                    class="{{ $post->category->color }} text-gray-500 text-xs font-medium inline-flex items-center px-2.5 py-0.5 rounded dark:bg-primary-200 dark:text-primary-800">
                    <svg class="mr-1 w-3 h-3" fill="currentColor" viewBox="0 0 20 20"
                      xmlns="http://www.w3.org/2000/svg">
                      <path
                        d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z">
                      </path>
                    </svg>
                    {{ $post->category->name }}
                  </span>
                </a>
                <p class="text-base text-gray-500 dark:text-gray-400">{{ $post->created_at->diffForHumans() }}</p>
              </div>
            </div>
          </address>
          <h1 class="mb-4 text-3xl font-extrabold leading-tight text-gray-900 lg:mb-6 lg:text-4xl dark:text-white">
            {{ $post->title }}</h1>
        </header>

        <div>{!! $post->body !!}</div>

      </article>
    </div>
  </main>

</x-layout>
