@extends('front.layouts.master')

@section('seo_title', $global_other_page_items->page_blog_seo_title)
@section('seo_meta_description', $global_other_page_items->page_blog_seo_meta_description)

@section('content')

<style>
    .share-dropdown {
        position: relative;
        display: inline-block;
    }

    .share-toggle {
        background: #388E3C;
        color: white;
        border: none;
        padding: 8px 15px;
        border-radius: 4px;
        cursor: pointer;
        font-size: 12px;
        transition: all 0.3s ease;
    }

    .share-toggle:hover {
        background: #2e7d32;
        color: white;
    }

    .share-options {
        display: none;
        position: absolute;
        top: 100%;
        left: 0;
        background: white;
        border: 1px solid #ddd;
        border-radius: 4px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        z-index: 1000;
        min-width: 150px;
        margin-top: 5px;
    }

    .share-options.show {
        display: block;
    }

    .share-option {
        display: block;
        padding: 8px 12px;
        text-decoration: none;
        color: #333;
        font-size: 12px;
        border-bottom: 1px solid #eee;
        transition: background 0.3s ease;
    }

    .share-option:last-child {
        border-bottom: none;
    }

    .share-option:hover {
        background: #f8f9fa;
        text-decoration: none;
        color: #333;
    }

    .share-option i {
        margin-right: 8px;
        width: 15px;
    }

    .share-whatsapp i {
        color: #25D366;
    }

    .share-facebook i {
        color: #1877F2;
    }

    .share-twitter i {
        color: #1DA1F2;
    }

    .share-linkedin i {
        color: #0A66C2;
    }
</style>

<section class="page-title" style="background-image: url({{ asset('uploads/'.$global_setting->banner) }});">
    <div class="auto-container">
        <div class="title-outer">
            <h1 class="title">{{ $global_other_page_items->page_blog_title }}</h1>
            <ul class="page-breadcrumb">
                <li><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
                <li>{{ $global_other_page_items->page_blog_title }}</li>
            </ul>
        </div>
    </div>
</section>

<section class="news-section">
    <div class="auto-container">
        <div class="row">
            @foreach($posts as $item)
            <div class="news-block col-lg-4 col-md-6 col-sm-12 wow fadeInUp">
                <div class="inner-box">
                    <div class="image-box">
                        <figure class="image">
                            <a href="{{ route('post',$item->slug) }}">
                                @if($item->photo)
                                <img src="{{ asset('uploads/'.$item->photo) }}" alt="{{ $item->title }}">
                                @else
                                <img src="{{ asset('uploads/default-post.jpg') }}" alt="{{ $item->title }}">
                                @endif
                            </a>
                        </figure>
                    </div>
                    <div class="content-box">
                        <span class="date">
                            {{ $item->created_at->format('d M, Y') }}
                        </span>
                        <ul class="post-info">
                            <li><i class="fa fa-user-circle"></i> {{ __('by Admin') }}</li>
                        </ul>
                        <h4 class="title"><a href="{{ route('post',$item->slug) }}">{{ $item->title }}</a></h4>

                        {{-- Boutons d'action --}}
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 15px;">
                            <a href="{{ route('post',$item->slug) }}" class="read-more">
                                {{ __('Read More') }}
                                <i class="fa @if(session('sess_lang_direction') == 'Right to Left (RTL)') fa-long-arrow-alt-left @else fa-long-arrow-alt-right @endif"></i>
                            </a>

                            {{-- Menu de partage compact --}}
                            <div class="share-dropdown">
                                <button type="button" class="share-toggle" onclick="toggleShare(this)">
                                    <i class="fas fa-share-alt"></i> Partager
                                </button>
                                <div class="share-options">
                                    <a href="javascript:void(0)"
                                        onclick="quickShare('whatsapp', '{{ $item->title }}', '{{ route('post', $item->slug) }}', '{{ strip_tags(Str::limit($item->description ?? '', 100)) }}')"
                                        class="share-option share-whatsapp">
                                        <i class="fab fa-whatsapp"></i>
                                        WhatsApp
                                    </a>
                                    <a href="javascript:void(0)"
                                        onclick="quickShare('facebook', '{{ $item->title }}', '{{ route('post', $item->slug) }}', '')"
                                        class="share-option share-facebook">
                                        <i class="fab fa-facebook-f"></i>
                                        Facebook
                                    </a>
                                    <a href="javascript:void(0)"
                                        onclick="quickShare('twitter', '{{ $item->title }}', '{{ route('post', $item->slug) }}', '{{ strip_tags(Str::limit($item->description ?? '', 100)) }}')"
                                        class="share-option share-twitter">
                                        <i class="fab fa-twitter"></i>
                                        Twitter
                                    </a>
                                    <a href="javascript:void(0)"
                                        onclick="quickShare('linkedin', '{{ $item->title }}', '{{ route('post', $item->slug) }}', '')"
                                        class="share-option share-linkedin">
                                        <i class="fab fa-linkedin-in"></i>
                                        LinkedIn
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
            <div class="col-md-12">
                {{ $posts->links() }}
            </div>
        </div>
    </div>
</section>

<script>
    // Fonction pour basculer l'affichage du menu de partage
    function toggleShare(button) {
        const shareOptions = button.nextElementSibling;
        const isVisible = shareOptions.classList.contains('show');

        // Fermer tous les autres menus ouverts
        document.querySelectorAll('.share-options.show').forEach(menu => {
            menu.classList.remove('show');
        });

        // Basculer le menu actuel
        if (!isVisible) {
            shareOptions.classList.add('show');
        }
    }

    // Fonction de partage rapide
    function quickShare(platform, title, url, description) {
        switch (platform) {
            case 'whatsapp':
                const whatsappText = `${title}\n\n${description}\n\n${url}`;
                const whatsappUrl = `https://wa.me/?text=${encodeURIComponent(whatsappText)}`;
                window.open(whatsappUrl, '_blank');
                break;

            case 'facebook':
                const facebookUrl = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(url)}`;
                window.open(facebookUrl, '_blank', 'width=600,height=400');
                break;

            case 'twitter':
                const twitterText = `${title} - ${description}`;
                const twitterUrl = `https://twitter.com/intent/tweet?text=${encodeURIComponent(twitterText)}&url=${encodeURIComponent(url)}`;
                window.open(twitterUrl, '_blank', 'width=600,height=400');
                break;

            case 'linkedin':
                const linkedinUrl = `https://www.linkedin.com/sharing/share-offsite/?url=${encodeURIComponent(url)}`;
                window.open(linkedinUrl, '_blank', 'width=600,height=400');
                break;
        }

        // Fermer le menu après le partage
        document.querySelectorAll('.share-options.show').forEach(menu => {
            menu.classList.remove('show');
        });
    }

    // Fermer les menus de partage en cliquant ailleurs
    document.addEventListener('click', function(event) {
        if (!event.target.closest('.share-dropdown')) {
            document.querySelectorAll('.share-options.show').forEach(menu => {
                menu.classList.remove('show');
            });
        }
    });
</script>

@endsection