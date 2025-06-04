@extends('front.layouts.master')

@section('seo_title', $post->seo_title)
@section('seo_meta_description', $post->seo_meta_description)

{{-- Métadonnées Open Graph pour le partage sur les réseaux sociaux --}}
@section('meta_tags')
<meta property="og:title" content="{{ $post->title }}" />
<meta property="og:description" content="{{ strip_tags(Str::limit($post->description, 160)) }}" />
<meta property="og:image" content="{{ asset('uploads/'.$post->photo) }}" />
<meta property="og:url" content="{{ url()->current() }}" />
<meta property="og:type" content="article" />
<meta property="og:site_name" content="{{ env('APP_NAME') }}" />

{{-- Twitter Card --}}
<meta name="twitter:card" content="summary_large_image" />
<meta name="twitter:title" content="{{ $post->title }}" />
<meta name="twitter:description" content="{{ strip_tags(Str::limit($post->description, 160)) }}" />
<meta name="twitter:image" content="{{ asset('uploads/'.$post->photo) }}" />

{{-- WhatsApp spécifique --}}
<meta property="og:image:width" content="1200" />
<meta property="og:image:height" content="630" />
<meta property="og:image:type" content="image/jpeg" />
@endsection

@section('content')

{{-- CSS pour les boutons de partage --}}
<style>
.social-share-buttons {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin: 20px 0;
}

.share-btn {
    display: inline-flex;
    align-items: center;
    padding: 8px 15px;
    border-radius: 5px;
    text-decoration: none;
    color: white;
    font-size: 14px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.share-btn:hover {
    transform: translateY(-2px);
    color: white;
    text-decoration: none;
}

.share-btn i {
    margin-right: 8px;
    font-size: 16px;
}

.share-whatsapp { background-color: #25D366; }
.share-whatsapp:hover { background-color: #1da851; }

.share-facebook { background-color: #1877F2; }
.share-facebook:hover { background-color: #166fe5; }

.share-twitter { background-color: #1DA1F2; }
.share-twitter:hover { background-color: #1a91da; }

.share-linkedin { background-color: #0A66C2; }
.share-linkedin:hover { background-color: #004182; }

.share-telegram { background-color: #0088cc; }
.share-telegram:hover { background-color: #006699; }

.share-copy { background-color: #6c757d; }
.share-copy:hover { background-color: #5a6268; }

.share-email { background-color: #dc3545; }
.share-email:hover { background-color: #c82333; }

@media (max-width: 768px) {
    .social-share-buttons {
        justify-content: center;
    }
    .share-btn {
        font-size: 12px;
        padding: 6px 12px;
    }
}
</style>

<section class="page-title" style="background-image: url({{ asset('uploads/'.$global_setting->banner) }});">
    <div class="auto-container">
        <div class="title-outer">
            <h1 class="title">{{ $post->title }}</h1>
            <ul class="page-breadcrumb">
                <li><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
                <li><a href="{{ route('blog') }}">{{ $global_other_page_items->page_blog_title }}</a></li>
                <li>{{ $post->title }}</li>
            </ul>
        </div>
    </div>
</section>

<section class="blog-details">
    <div class="container">
        <div class="row">
            <div class="col-xl-8 col-lg-7">
                <div class="blog-details__left">
                    <div class="blog-details__img">
                        @if($post->photo)
                        <img src="{{ asset('uploads/'.$post->photo) }}" alt="{{ $post->title }}">
                        @endif
                        <div class="blog-details__date">
                            <span class="day">
                                {{ $post->created_at->format('d') }}
                            </span>
                            <span class="month">
                                {{ $post->created_at->format('M') }}
                            </span>
                        </div>
                    </div>
                    <div class="blog-details__content">
                        <ul class="list-unstyled blog-details__meta">
                            <li><a href="javascript:void;"><i class="fas fa-user-circle"></i> {{ __('Admin') }}</a></li>
                        </ul>
                        <p class="blog-details__text-2">
                            {!! clean($post->description) !!}
                        </p>
                    </div>

                    {{-- Section de partage social --}}
                    <div class="blog-details__share">
                        <h5 style="margin-bottom: 15px; color: #333;">
                            <i class="fas fa-share-alt" style="margin-right: 10px;"></i>
                            {{ __('Partager cet article') }}
                        </h5>
                        <div class="social-share-buttons">
                            {{-- WhatsApp --}}
                            <a href="javascript:void(0)" 
                               onclick="shareOnWhatsApp()" 
                               class="share-btn share-whatsapp">
                                <i class="fab fa-whatsapp"></i>
                                WhatsApp
                            </a>

                            {{-- Facebook --}}
                            <a href="javascript:void(0)" 
                               onclick="shareOnFacebook()" 
                               class="share-btn share-facebook">
                                <i class="fab fa-facebook-f"></i>
                                Facebook
                            </a>

                            {{-- Twitter --}}
                            <a href="javascript:void(0)" 
                               onclick="shareOnTwitter()" 
                               class="share-btn share-twitter">
                                <i class="fab fa-twitter"></i>
                                Twitter
                            </a>

                            {{-- LinkedIn --}}
                            <a href="javascript:void(0)" 
                               onclick="shareOnLinkedIn()" 
                               class="share-btn share-linkedin">
                                <i class="fab fa-linkedin-in"></i>
                                LinkedIn
                            </a>

                            {{-- Telegram --}}
                            <a href="javascript:void(0)" 
                               onclick="shareOnTelegram()" 
                               class="share-btn share-telegram">
                                <i class="fab fa-telegram"></i>
                                Telegram
                            </a>

                            {{-- Email --}}
                            <a href="javascript:void(0)" 
                               onclick="shareByEmail()" 
                               class="share-btn share-email">
                                <i class="fas fa-envelope"></i>
                                Email
                            </a>

                            {{-- Copier le lien --}}
                            <a href="javascript:void(0)" 
                               onclick="copyLink()" 
                               class="share-btn share-copy">
                                <i class="fas fa-copy"></i>
                                Copier
                            </a>
                        </div>
                    </div>

                    <div class="blog-details__bottom">
                        @if(count($post_tags) != 0)
                        <p class="blog-details__tags"> <span>{{ __('Tags') }}</span> 
                            @for($i=0;$i<count($post_tags);$i++)
                            <a href="{{ route('tag',$post_tags[$i]) }}">{{ $post_tags[$i] }}</a>
                            @endfor
                        </p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-lg-5">
                <div class="sidebar">
                    <div class="sidebar__single sidebar__search">
                        <form action="{{ route('search') }}" class="sidebar__search-form" method="get">
                            <input name="search_text" type="search" placeholder="Search here" required>
                            <button type="submit"><i class="lnr-icon-search"></i></button>
                        </form>
                    </div>
                    <div class="sidebar__single sidebar__post">
                        <h3 class="sidebar__title">{{ __('Latest Posts') }}</h3>
                        <ul class="sidebar__post-list list-unstyled">
                            @foreach($latest_posts as $item)
                            <li>
                                <div class="sidebar__post-image"> 
                                    @if($item->photo)
                                    <img src="{{ asset('uploads/'.$item->photo) }}" alt="{{ $item->title }}"> 
                                    @endif
                                </div>
                                <div class="sidebar__post-content">
                                    <h3> <span class="sidebar__post-content-meta"><i
                                        class="fas fa-user-circle"></i>{{ __('Admin') }}</span> <a href="{{ route('post',$item->slug) }}">{{ $item->title }}</a>
                                    </h3>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="sidebar__single sidebar__category">
                        <h3 class="sidebar__title mb-20">{{ __('Categories') }}</h3>
                        <ul class="sidebar__category-list list-unstyled">
                            @foreach($post_categories as $item)
                            <li>
                                <a href="{{ route('category', $item->slug) }}">{{ $item->name }}<span
                                class="icon-right-arrow"></span></a>
                            </li>
                            @endforeach
                        </ul>
                    </div>

                    @if(count($tags) != 0)
                    <div class="sidebar__single sidebar__tags">
                        <h3 class="sidebar__title">{{ __('Tags') }}</h3>
                        <div class="sidebar__tags-list">
                            @for($i=0;$i<count($tags);$i++)
                            <a href="{{ route('tag', $tags[$i]) }}">{{ $tags[$i] }}</a>
                            @endfor
                        </div>
                    </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</section>

{{-- JavaScript pour les fonctions de partage --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Variables pour le partage
    const postTitle = @json($post->title);
    const postUrl = @json(url()->current());
    const postDescription = @json(strip_tags(Str::limit($post->description, 160)));
    const postImage = @json(asset('uploads/'.$post->photo));

    // WhatsApp
    window.shareOnWhatsApp = function() {
        const text = `${postTitle}\n\n${postDescription}\n\n${postUrl}`;
        const whatsappUrl = `https://wa.me/?text=${encodeURIComponent(text)}`;
        window.open(whatsappUrl, '_blank');
    }

    // Facebook
    window.shareOnFacebook = function() {
        const facebookUrl = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(postUrl)}`;
        window.open(facebookUrl, '_blank', 'width=600,height=400');
    }

    // Twitter
    window.shareOnTwitter = function() {
        const text = `${postTitle} - ${postDescription}`;
        const twitterUrl = `https://twitter.com/intent/tweet?text=${encodeURIComponent(text)}&url=${encodeURIComponent(postUrl)}`;
        window.open(twitterUrl, '_blank', 'width=600,height=400');
    }

    // LinkedIn
    window.shareOnLinkedIn = function() {
        const linkedinUrl = `https://www.linkedin.com/sharing/share-offsite/?url=${encodeURIComponent(postUrl)}`;
        window.open(linkedinUrl, '_blank', 'width=600,height=400');
    }

    // Telegram
    window.shareOnTelegram = function() {
        const text = `${postTitle}\n\n${postDescription}`;
        const telegramUrl = `https://t.me/share/url?url=${encodeURIComponent(postUrl)}&text=${encodeURIComponent(text)}`;
        window.open(telegramUrl, '_blank');
    }

    // Email
    window.shareByEmail = function() {
        const subject = encodeURIComponent(`Découvrez cet article: ${postTitle}`);
        const body = encodeURIComponent(`Bonjour,\n\nJe partage avec vous cet article intéressant:\n\n${postTitle}\n\n${postDescription}\n\nLire l'article complet: ${postUrl}\n\nCordialement`);
        const emailUrl = `mailto:?subject=${subject}&body=${body}`;
        window.location.href = emailUrl;
    }

    // Copier le lien
    window.copyLink = function() {
        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(postUrl).then(function() {
                showCopyMessage('Lien copié dans le presse-papier!');
            }).catch(function() {
                fallbackCopyLink();
            });
        } else {
            fallbackCopyLink();
        }
    }

    // Fonction de fallback pour copier le lien
    function fallbackCopyLink() {
        const textArea = document.createElement('textarea');
        textArea.value = postUrl;
        textArea.style.position = 'fixed';
        textArea.style.opacity = '0';
        document.body.appendChild(textArea);
        textArea.select();
        try {
            document.execCommand('copy');
            showCopyMessage('Lien copié dans le presse-papier!');
        } catch (err) {
            showCopyMessage('Impossible de copier automatiquement. Copiez ce lien: ' + postUrl);
        }
        document.body.removeChild(textArea);
    }

    // Afficher un message de confirmation
    function showCopyMessage(message) {
        // Créer un toast/notification
        const toast = document.createElement('div');
        toast.innerHTML = message;
        toast.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: #28a745;
            color: white;
            padding: 15px 20px;
            border-radius: 5px;
            z-index: 9999;
            font-size: 14px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        `;
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.remove();
        }, 3000);
    }
});
</script>

@endsection