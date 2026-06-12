@extends('layouts.app')

@section('title', 'Gift-XP | Warm & Cozy Experiential Gifting')

@section('content')
<!-- Fonts & Premium Icons -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Syne:wght@700;800&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">

<div class="premium-home-wrapper homey-active">
    <!-- Cozy Warm Ambient Lighting Glows -->
    <div class="ambient-glow glow-peach"></div>
    <div class="ambient-glow glow-sage"></div>
    <div class="ambient-glow glow-gold"></div>

    <!-- Hero Section -->
    <section class="hero-section position-relative d-flex align-items-center overflow-hidden">
        <div class="container position-relative" style="z-index: 10;">
            <div class="row min-vh-85 align-items-center g-5 py-5 hero-row">
                <div class="col-lg-6 hero-text-col text-center text-lg-start">
                    <span class="badge-premium mb-4 d-inline-flex align-items-center gap-2 px-3 py-2 rounded-pill animate-float">
                        <i class="bi bi-heart-fill text-danger animate-pulse"></i>
                        <span>Thoughtful Digital Vouchers, Handwrapped With Care</span>
                    </span>
                    <h1 class="hero-title mb-4 text-warm-espresso">
                        <span class="word-reveal">Share Warmth,</span> <br>
                        <span class="word-reveal text-gradient-terracotta">Unwrap Cozy</span> <br>
                        <span class="word-reveal text-gradient-gold">Moments.</span>
                    </h1>
                    <p class="hero-desc mb-5 mx-auto mx-lg-0 text-warm-slate">
                        Welcome to a gentler way of showing appreciation. Gift-XP connects you with hand-selected local boutiques, artisanal cafes, and relaxing sanctuaries. Share digital vouchers that feel like custom-folded letters.
                    </p>
                    <div class="hero-ctas d-flex flex-wrap justify-content-center justify-content-lg-start gap-3">
                        <a href="#cities-section" class="btn-premium px-5 py-3 rounded-pill text-decoration-none shadow-glow">
                            <span>Explore Local Portals</span>
                            <i class="bi bi-chevron-right ms-2"></i>
                        </a>
                    </div>
                </div>
                <div class="col-lg-6 hero-canvas-col position-relative">
                    <!-- WebGL Canvas Context for 3D Gift Box -->
                    <div id="canvas-container" class="w-100 position-relative">
                        <div class="canvas-fallback d-none flex-column align-items-center justify-content-center text-center p-4">
                            <div class="fallback-orb animate-pulse mb-3"></div>
                            <h3 class="h5 fw-bold text-warm-espresso mb-2">A Beautiful Gift</h3>
                            <p class="text-muted small">Hand-wrapped interactive 3D gift-box</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="scroll-indicator position-absolute start-50 translate-middle-x mb-4 bottom-0 d-none d-md-flex flex-column align-items-center" style="z-index: 10;">
            <span class="text-muted small tracking-widest mb-2 text-uppercase">Scroll to unwrap</span>
            <div class="scroll-mouse">
                <div class="scroll-wheel"></div>
            </div>
        </div>
    </section>

    <!-- City Selector Section (Cozy Portals) -->
    <section id="cities-section" class="cities-section py-5 position-relative">
        <div class="container py-5">
            <div class="row justify-content-center text-center mb-5">
                <div class="col-lg-8">
                    <span class="section-badge text-uppercase tracking-widest text-terracotta">Pick Your Haven</span>
                    <h2 class="section-title mt-2 mb-3 text-warm-espresso">Where are we celebrating today?</h2>
                    <p class="text-muted lead">Select a city to discover hand-curated experiences. From organic bakeries to local wellness sanctuaries, pick a portal and send joy instantly.</p>
                </div>
            </div>

            <div class="row justify-content-center g-4 mt-2">
                @php 
                    $cities = \App\Models\City::where('is_active', true)->get();
                    $gradients = [
                        'manila' => 'linear-gradient(135deg, #E76F51 0%, #E9C46A 100%)',
                        'cebu' => 'linear-gradient(135deg, #F4A261 0%, #A88FBB 100%)',
                        'davao' => 'linear-gradient(135deg, #8A9A86 0%, #457B9D 100%)'
                    ];
                    $citySlogans = [
                        'manila' => 'Artisanal roasters & cozy coffee corners',
                        'cebu' => 'Handcrafted bakeries & beachside retreats',
                        'davao' => 'Organic wellness & calm botanical gardens'
                    ];
                    $shadowColors = [
                        'manila' => 'rgba(231, 111, 81, 0.12)',
                        'cebu' => 'rgba(244, 162, 97, 0.12)',
                        'davao' => 'rgba(138, 154, 134, 0.12)'
                    ];
                    
                    // Cozy randomized names
                    $names = ['Mom', 'Sofia', 'David', 'Sarah', 'Alex', 'Love', 'Dad', 'Emma', 'Daniel', 'Chloe', 'James', 'Grace'];
                @endphp

                @foreach($cities as $index => $city)
                    @php 
                        $slug = strtolower($city->slug);
                        $grad = $gradients[$slug] ?? 'linear-gradient(135deg, #E76F51 0%, #457B9D 100%)';
                        $slogan = $citySlogans[$slug] ?? 'Curated local family boutiques';
                        $shadowCol = $shadowColors[$slug] ?? 'rgba(0,0,0,0.03)';
                        
                        // Pick a random name stably mapped to the card index
                        $giftToName = $names[$index % count($names)];
                        
                        // Pick box specific terracotta, sage, peach, and ribbon colors
                        $boxBodyFill = $slug === 'manila' ? '#E76F51' : ($slug === 'cebu' ? '#F4A261' : ($slug === 'davao' ? '#8A9A86' : '#FAF6F0'));
                        $boxStrokeFill = $slug === 'manila' ? '#CD5C5C' : ($slug === 'cebu' ? '#E76F51' : ($slug === 'davao' ? '#5A6F50' : '#E2E8F0'));
                        $boxStripeFill = $slug === 'manila' ? '#E9C46A' : ($slug === 'cebu' ? '#A88FBB' : ($slug === 'davao' ? '#E9C46A' : '#E76F51'));
                        $boxLidFill = $slug === 'manila' ? '#F4A261' : ($slug === 'cebu' ? '#E9C46A' : ($slug === 'davao' ? '#FAF6F0' : '#8A9A86'));
                        $ribbonStroke = $slug === 'manila' ? '#D94E34' : ($slug === 'cebu' ? '#8A9A86' : ($slug === 'davao' ? '#E76F51' : '#E9C46A'));
                        $glowColor = $slug === 'manila' ? '#E9C46A' : ($slug === 'cebu' ? '#FFB6C1' : ($slug === 'davao' ? '#80CBC4' : '#E76F51'));
                    @endphp
                    <div class="col-md-6 col-lg-4 city-card-col">
                        <a href="{{ route('city.home', ['city_slug' => $city->slug]) }}" class="text-decoration-none city-portal-link">
                            <div class="city-portal-card border-0 h-100 position-relative" data-tilt style="--shadow-color: {{$shadowCol}};">
                                <!-- Interactive Glare Effect Overlay -->
                                <div class="card-glare"></div>
                                <div class="card-portal-glow" style="background: {{$grad}};"></div>
                                
                                <div class="card-body-content p-5 d-flex flex-column h-100 justify-content-between position-relative" style="z-index: 5;">
                                    <div class="portal-header d-flex align-items-center justify-content-between mb-4">
                                        <div class="portal-badge rounded-pill px-3 py-1 text-white text-uppercase" style="background: {{$grad}}; font-size: 0.7rem; font-weight: 700; letter-spacing: 0.1em;">
                                            Open
                                        </div>
                                        <div class="portal-icon text-muted">
                                            <i class="bi bi-mailbox2 fs-4"></i>
                                        </div>
                                    </div>
                                    
                                    <!-- Interactive 3D Gift Box Canvas Container -->
                                    <div class="city-3d-container mx-auto my-3" data-city-slug="{{ $slug }}" data-gift-to="To:{{ $giftToName }}" style="width: 100%; height: 220px; position: relative; z-index: 10;">
                                        <!-- Custom Vector Floating Tag -->
                                        <div class="gift-html-tag border border-light-subtle shadow-sm bg-white rounded px-2 py-1 position-absolute" style="top: 35px; right: 20%; z-index: 12; transform: rotate(15deg); transition: transform 0.4s ease; transform-origin: left center; pointer-events: none;">
                                            <span class="d-flex align-items-center gap-1 small text-dark fw-bold" style="font-family: 'Space Grotesk', sans-serif; font-size: 0.72rem; line-height: 1;">
                                                <i class="bi bi-circle-fill text-danger" style="font-size: 0.35rem;"></i>
                                                <span>To: {{ $giftToName }}</span>
                                            </span>
                                        </div>
                                        <div class="canvas-fallback d-none flex-column align-items-center justify-content-center text-center p-4">
                                            <i class="bi bi-gift-fill text-primary animate-pulse" style="font-size: 4rem;"></i>
                                        </div>
                                    </div>
                                    
                                    <div class="portal-middle my-4">
                                        <h3 class="portal-city-name text-warm-espresso fw-extrabold mb-2">{{ $city->name }}</h3>
                                        <p class="portal-city-desc text-muted mb-0 small">{{ $slogan }}</p>
                                    </div>

                                    <div class="portal-footer pt-4 border-top border-light-subtle d-flex align-items-center justify-content-between">
                                        <span class="text-terracotta small fw-bold">Step Inside</span>
                                        <div class="arrow-circle rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi bi-chevron-right text-dark fs-6"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Bento Secondary Section (Partner Intro / FAQ / Install) -->
    <section class="secondary-grids py-5 position-relative">
        <div class="container py-5">
            <div class="row g-4 justify-content-center">
                <div class="col-md-4 secondary-col">
                    <div class="glass-module-card p-5 h-100 d-flex flex-column justify-content-between position-relative overflow-hidden">
                        <div class="module-glow"></div>
                        <div class="mb-4">
                            <div class="module-icon bg-opacity-10 bg-primary text-terracotta rounded-circle mb-4 d-flex align-items-center justify-content-center" style="width: 64px; height: 64px; background-color: #FFF0EE;">
                                <i class="bi bi-house-heart fs-3"></i>
                            </div>
                            <h3 class="h4 text-warm-espresso fw-bold mb-3">Join as a Partner</h3>
                            <p class="text-muted small">Are you a curated local trade? Share your craft with thoughtful gifters in your city. Manage redemptions through our cozy partner dashboard.</p>
                        </div>
                        <div>
                            <a href="{{ route('page.partner-intro') }}" class="btn-module text-decoration-none">
                                <span>Merchant Haven</span>
                                <i class="bi bi-chevron-right"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 secondary-col">
                    <div class="glass-module-card p-5 h-100 d-flex flex-column justify-content-between position-relative overflow-hidden">
                        <div class="module-glow"></div>
                        <div class="mb-4">
                            <div class="module-icon bg-opacity-10 bg-info text-info rounded-circle mb-4 d-flex align-items-center justify-content-center" style="width: 64px; height: 64px; background-color: rgba(108, 165, 216, 0.08);">
                                <i class="bi bi-envelope-open-heart fs-3 text-primary"></i>
                            </div>
                            <h3 class="h4 text-warm-espresso fw-bold mb-3">Curious Questions</h3>
                            <p class="text-muted small">Have questions about card expiry dates, personalized unwrap letters, or how local merchant ledger settlements work? We have answers.</p>
                        </div>
                        <div>
                            <a href="#" class="btn-module text-decoration-none">
                                <span>Browse Our FAQs</span>
                                <i class="bi bi-chevron-right"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 secondary-col">
                    <div class="glass-module-card p-5 h-100 d-flex flex-column justify-content-between position-relative overflow-hidden">
                        <div class="module-glow"></div>
                        <div class="mb-4">
                            <div class="module-icon bg-opacity-10 bg-warning text-warning rounded-circle mb-4 d-flex align-items-center justify-content-center" style="width: 64px; height: 64px; background-color: rgba(255, 215, 0, 0.08);">
                                <i class="bi bi-phone fs-3 text-warning"></i>
                            </div>
                            <h3 class="h4 text-warm-espresso fw-bold mb-3">Install App</h3>
                            <p class="text-muted small">Add our responsive web app directly to your home screen. Experience Gift-XP like a cozy, native pocket notebook on your device.</p>
                        </div>
                        <div>
                            <a href="#" class="btn-module text-decoration-none">
                                <span>Install Cozy App</span>
                                <i class="bi bi-chevron-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Raw Stylesheet Override (Homey & Cozy Redesign) -->
<style>
    /* CSS Variables & Cozy Warm Globals */
    :root {
        --bg-light-premium: #FAF6F0; /* Soft warm linen cream */
        --accent-coral: #E76F51;    /* Warm Terracotta Coral */
        --accent-peach: #F4A261;    /* Soft Peach */
        --accent-sage: #8A9A86;     /* Peaceful Sage Green */
        --accent-gold: #E9C46A;     /* Candlelight Warm Gold */
        --text-dark-espresso: #3D3430; /* Cozy Warm Roasted Coffee */
        --text-muted-slate: #64748B;
        --glass-bg: #FFFFFF;
        --glass-border: #E2E8F0;
        --glow-shadow: 0 15px 40px rgba(231, 111, 81, 0.06);
    }

    /* Target standard body context when homepage is active */
    body.welcome-active {
        background-color: var(--bg-light-premium) !important;
        color: var(--text-dark-espresso) !important;
        overflow-x: hidden;
    }
    body.welcome-active main {
        padding-top: 0 !important;
        padding-bottom: 0 !important;
    }
    body.welcome-active .navbar {
        background: rgba(250, 246, 240, 0.8) !important;
        backdrop-filter: blur(25px) saturate(180%);
        -webkit-backdrop-filter: blur(25px) saturate(180%);
        border-bottom: 1px solid rgba(61, 52, 48, 0.04) !important;
        box-shadow: 0 4px 30px rgba(61, 52, 48, 0.01) !important;
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    }
    body.welcome-active .navbar .navbar-brand {
        color: var(--accent-coral) !important;
        font-family: 'Space Grotesk', sans-serif;
        font-weight: 700;
        letter-spacing: -0.02em;
        background: linear-gradient(135deg, var(--accent-coral), var(--accent-sage));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    body.welcome-active .navbar .nav-link {
        color: var(--text-dark-espresso) !important;
        font-weight: 600;
        opacity: 0.85;
    }
    body.welcome-active .navbar .nav-link:hover {
        opacity: 1;
        color: var(--accent-coral) !important;
    }
    body.welcome-active footer {
        background-color: #FAF6F0 !important;
        border-top: 1px solid rgba(61, 52, 48, 0.06) !important;
    }
    body.welcome-active footer h4 a, body.welcome-active footer p {
        color: var(--text-dark-espresso) !important;
    }
    body.welcome-active footer .text-muted {
        color: #7A6F69 !important;
    }

    /* Premium Home Wrapper layout & styling */
    .premium-home-wrapper {
        background-color: var(--bg-light-premium);
        color: var(--text-dark-espresso);
        font-family: 'Plus Jakarta Sans', sans-serif;
        position: relative;
        overflow: hidden;
        width: 100%;
    }

    /* Warm Organic Ambient Glows */
    .ambient-glow {
        position: absolute;
        border-radius: 50%;
        filter: blur(140px);
        opacity: 0.15;
        z-index: 1;
        pointer-events: none;
        mix-blend-mode: multiply;
    }
    .glow-peach {
        width: 45vw;
        height: 45vw;
        background: radial-gradient(circle, var(--accent-peach) 0%, transparent 70%);
        top: -10%;
        right: -5%;
    }
    .glow-sage {
        width: 50vw;
        height: 50vw;
        background: radial-gradient(circle, var(--accent-sage) 0%, transparent 70%);
        top: 30%;
        left: -15%;
    }
    .glow-gold {
        width: 40vw;
        height: 40vw;
        background: radial-gradient(circle, var(--accent-gold) 0%, transparent 70%);
        bottom: 5%;
        right: -10%;
    }

    /* Typography Overrides */
    .text-warm-espresso {
        color: var(--text-dark-espresso);
    }
    .text-warm-slate {
        color: #645F5B;
    }
    .hero-title {
        font-family: 'Syne', sans-serif;
        font-size: clamp(2.4rem, 5.5vw, 4rem);
        font-weight: 800;
        line-height: 1.1;
        letter-spacing: -0.03em;
    }
    .text-gradient-terracotta {
        background: linear-gradient(135deg, var(--accent-coral), var(--accent-peach));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    .text-gradient-gold {
        background: linear-gradient(135deg, var(--accent-gold), #D4AF37);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    .hero-desc {
        line-height: 1.7;
        font-weight: 400;
        max-width: 520px;
    }
    .extra-small {
        font-size: 0.65rem;
        letter-spacing: 0.12em;
    }
    .uppercase {
        text-transform: uppercase;
    }

    /* Buttons & Badges styling */
    .badge-premium {
        background-color: #FFF0EE;
        border: 1.5px solid var(--accent-coral);
        color: var(--accent-coral);
        font-weight: 700;
        font-size: 0.85rem;
        letter-spacing: -0.01em;
        box-shadow: 0 4px 15px rgba(231, 111, 81, 0.04);
    }
    .section-badge {
        font-family: 'Space Grotesk', sans-serif;
        font-size: 0.85rem;
        font-weight: 700;
        letter-spacing: 0.2em;
    }
    .section-title {
        font-family: 'Syne', sans-serif;
        font-size: clamp(2rem, 4vw, 2.8rem);
        font-weight: 800;
        letter-spacing: -0.02em;
    }
    .btn-premium {
        background-color: var(--accent-coral);
        color: #FFFFFF !important;
        font-weight: 700;
        font-family: 'Space Grotesk', sans-serif;
        letter-spacing: -0.01em;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        border: 2px solid var(--accent-coral);
    }
    .btn-premium:hover {
        background-color: #d1563f;
        border-color: #d1563f;
        transform: translateY(-3px) scale(1.02);
        box-shadow: 0 10px 25px rgba(231, 111, 81, 0.2);
    }
    .btn-outline-premium {
        background-color: #FFFFFF;
        border: 2px solid var(--accent-sage);
        color: var(--accent-sage) !important;
        font-weight: 700;
        font-family: 'Space Grotesk', sans-serif;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .btn-outline-premium:hover {
        background-color: var(--accent-sage);
        color: #FFFFFF !important;
        border-color: var(--accent-sage);
        transform: translateY(-2px);
    }

    /* Scroll Mouse Indicator */
    .scroll-mouse {
        width: 24px;
        height: 40px;
        border: 2px solid rgba(61, 52, 48, 0.2);
        border-radius: 12px;
        position: relative;
    }
    .scroll-wheel {
        width: 4px;
        height: 8px;
        background: var(--accent-coral);
        border-radius: 2px;
        position: absolute;
        top: 6px;
        left: 50%;
        transform: translateX(-50%);
        animation: mouse-scroll 1.6s infinite ease-in-out;
    }
    @keyframes mouse-scroll {
        0% { top: 6px; opacity: 1; }
        50% { top: 16px; opacity: 0; }
        100% { top: 6px; opacity: 1; }
    }

    /* Three.js Canvas Container & Mobile Layout */
    #canvas-container {
        height: 60vh;
        min-height: 380px;
        z-index: 5;
    }
    .canvas-fallback {
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.01);
        border: 1px solid rgba(0, 0, 0, 0.04);
        border-radius: 30px;
    }
    .fallback-orb {
        width: 120px;
        height: 120px;
        background: linear-gradient(135deg, var(--accent-peach), var(--accent-gold));
        border-radius: 50%;
        filter: blur(20px);
    }

    /* City Portals styling */
    .city-portal-link {
        perspective: 1000px;
        display: block;
    }
    .city-portal-card {
        background-color: #FFFFFF;
        border: 1.5px solid #E2E8F0 !important;
        border-radius: 28px;
        overflow: hidden;
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        transform-style: preserve-3d;
        backface-visibility: hidden;
        box-shadow: 0 4px 20px rgba(61, 52, 48, 0.015);
    }
    .card-body-content {
        transform: translateZ(50px);
    }
    .card-glare {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: radial-gradient(circle at var(--mouse-x, 50%) var(--mouse-y, 50%), rgba(255, 255, 255, 0.5) 0%, transparent 60%);
        pointer-events: none;
        z-index: 3;
        mix-blend-mode: overlay;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    .city-portal-card:hover .card-glare {
        opacity: 1;
    }
    .card-portal-glow {
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        opacity: 0;
        filter: blur(60px);
        transition: opacity 0.5s ease;
        z-index: 1;
        pointer-events: none;
        mix-blend-mode: multiply;
    }
    .city-portal-card:hover {
        border-color: var(--accent-sage) !important;
        box-shadow: 0 20px 40px var(--shadow-color, rgba(138, 154, 134, 0.08));
    }
    .city-portal-card:hover .card-portal-glow {
        opacity: 0.1;
    }
    .portal-city-name {
        font-family: 'Space Grotesk', sans-serif;
        font-size: 1.8rem;
        letter-spacing: -0.02em;
    }
    .arrow-circle {
        width: 44px;
        height: 44px;
        background-color: #F4EFEA;
        border: 1.5px solid #E2E8F0;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .city-portal-card:hover .arrow-circle {
        background: var(--accent-coral);
        border-color: transparent;
        transform: scale(1.1);
    }
    .city-portal-card:hover .arrow-circle i {
        color: #FFFFFF !important;
    }

    /* City 3D Gifting Canvas & Hybrid Tag Styles */
    .city-3d-container canvas {
        width: 100% !important;
        height: 100% !important;
        position: absolute;
        top: 0; left: 0;
        z-index: 5;
        transition: opacity 0.4s ease;
    }
    .gift-html-tag {
        box-shadow: 0 4px 15px rgba(61, 52, 48, 0.04) !important;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275) !important;
        transform-origin: left center !important;
    }
    .city-portal-card:hover .gift-html-tag,
    .city-portal-card.active-scroll .gift-html-tag {
        transform: translate(-6px, -4px) rotate(-10deg) scale(1.08) !important;
        border-color: var(--accent-coral) !important;
        box-shadow: 0 8px 20px rgba(231, 111, 81, 0.1) !important;
    }

    .city-portal-card:hover .arrow-circle,
    .city-portal-card.active-scroll .arrow-circle {
        background: var(--accent-coral);
        border-color: transparent;
        transform: scale(1.1);
        transition: all 0.3s ease;
    }
    .city-portal-card:hover .arrow-circle i,
    .city-portal-card.active-scroll .arrow-circle i {
        color: #FFFFFF !important;
        transition: all 0.3s ease;
    }


    /* Secondary module cards (Solid elegant cards) */
    .glass-module-card {
        background-color: #FFFFFF;
        border: 1.5px solid #E2E8F0;
        border-radius: 30px;
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        box-shadow: 0 10px 30px rgba(0,0,0,0.015);
    }
    .glass-module-card:hover {
        background-color: #FFFFFF;
        border-color: rgba(231, 111, 81, 0.2);
        transform: translateY(-4px);
        box-shadow: 0 20px 40px rgba(231, 111, 81, 0.06);
    }
    .module-glow {
        position: absolute;
        top: -80px;
        right: -80px;
        width: 160px;
        height: 160px;
        border-radius: 50%;
        filter: blur(40px);
        opacity: 0;
        background: radial-gradient(circle, var(--accent-coral) 0%, transparent 70%);
        transition: opacity 0.5s ease;
        pointer-events: none;
        mix-blend-mode: multiply;
    }
    .glass-module-card:hover .module-glow {
        opacity: 0.1;
    }
    .btn-module {
        font-family: 'Space Grotesk', sans-serif;
        font-weight: 700;
        color: var(--text-dark-espresso);
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: color 0.3s ease;
    }
    .btn-module i {
        font-size: 0.85rem;
        transition: transform 0.3s ease;
    }
    .glass-module-card:hover .btn-module {
        color: var(--accent-coral);
    }
    .glass-module-card:hover .btn-module i {
        transform: translateX(4px);
    }

    /* Floating utilities */
    .animate-float {
        animation: y-float 6s infinite ease-in-out;
    }
    @keyframes y-float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-8px); }
    }

    /* Responsive scaling optimizations - Complete Mobile-First Support */
    @media (max-width: 991.98px) {
        body.welcome-active .navbar {
            background: rgba(250, 246, 240, 0.9) !important;
        }
        #canvas-container {
            height: 35vh; /* Responsive touch height */
            min-height: 280px;
            margin-top: 1.5rem;
        }
        .hero-row {
            flex-direction: column;
            padding-top: 1.5rem !important;
            padding-bottom: 1.5rem !important;
        }
        .hero-title {
            font-size: clamp(2.2rem, 8vw, 3rem);
        }
        .bento-grid {
            grid-template-columns: 1fr;
        }
        .bento-large, .bento-standard {
            grid-column: span 12;
        }

        /* Prevent double padding and collapse excessive whitespace on mobile */
        .premium-home-wrapper section {
            padding-top: 2rem !important;
            padding-bottom: 2rem !important;
        }
        .premium-home-wrapper section .container {
            padding-top: 0 !important;
            padding-bottom: 0 !important;
        }
        body.welcome-active footer {
            margin-top: 2rem !important;
            padding-top: 2.5rem !important;
            padding-bottom: 2.5rem !important;
        }
        .glass-module-card {
            padding: 2.5rem 1.5rem !important; /* Tighten up card padding on mobile */
        }
        .city-portal-card .card-body-content {
            padding: 2rem 1.5rem !important; /* Snug portal padding on mobile */
        }
    }
    @media (max-width: 575.98px) {
        .hero-title {
            font-size: 2.2rem;
        }
        .hero-desc {
            font-size: 0.95rem;
        }
        .bento-card {
            border-radius: 24px;
            padding: 1.5rem !important;
        }
        .theme-pill-btn {
            padding: 6px 14px;
            font-size: 0.75rem;
        }
        .city-portal-card {
            border-radius: 24px;
        }
        .city-portal-card .card-body-content {
            padding: 1.75rem 1.25rem !important; /* Snug portal padding on small mobile */
        }
        .glass-module-card {
            padding: 2rem 1.25rem !important; /* Further tighten cards */
        }
    }
</style>

@endsection

@push('scripts')
<!-- GSAP & Plugins (ScrollTrigger) via cloudflare CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>
<!-- Three.js Engine for WebGL 3D -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // 1. Set homepage visual context to enable nav overrides
    document.body.classList.add('welcome-active');

    // 2. Initialize Three.js Interactive 3D scene (Optimized for cozy light mode & mobile)
    initThreeJS();

    // 3. Setup GSAP Entrance & Scroll animations
    initGSAPAnimations();

    // 4. Setup Interactive 3D tilt micro-interactions (Disabled on mobile for touch smoothness)
    if (window.innerWidth > 991.98) {
        initTiltInteraction();
    }

    // 5. Setup mobile scrolling unwrapping observer
    initMobileScrollUnwrap();

    // 6. Setup City 3D Gift Box Multi-Canvas rendering
    initCity3DBoxes();
});

/* ==========================================================================
   THREE.JS: Interactive 3D Gift Box Wrapping with Satin Ribbon
   ========================================================================== */
function initThreeJS() {
    const container = document.getElementById('canvas-container');
    if (!container) return;

    const width = container.clientWidth;
    const height = container.clientHeight;

    // WebGL support validation
    let isWebGLSupported = (() => {
        try {
            const canvas = document.createElement('canvas');
            return !!(window.WebGLRenderingContext && (canvas.getContext('webgl') || canvas.getContext('experimental-webgl')));
        } catch (e) {
            return false;
        }
    })();

    if (!isWebGLSupported) {
        const fallback = container.querySelector('.canvas-fallback');
        if (fallback) fallback.classList.remove('d-none');
        return;
    }

    // 3D Engine Essentials
    const scene = new THREE.Scene();
    
    // Soft ambient camera
    const camera = new THREE.PerspectiveCamera(40, width / height, 0.1, 100);
    camera.position.z = 12;

    const renderer = new THREE.WebGLRenderer({ alpha: true, antialias: true });
    renderer.setSize(width, height);
    renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
    container.appendChild(renderer.domElement);

    // Warm Celebratory Light Ecosystem
    const ambientLight = new THREE.AmbientLight(0xffffff, 0.85); // Warm background light
    scene.add(ambientLight);

    const warmLight = new THREE.PointLight(0xFFE4E1, 3.5, 30); // Warm rose light
    warmLight.position.set(10, 10, 10);
    scene.add(warmLight);

    const goldLight = new THREE.PointLight(0xE9C46A, 2.5, 20); // Golden soft candle glow
    goldLight.position.set(-10, -10, 8);
    scene.add(goldLight);

    // Group to hold the entire Gift Box assembly
    const giftBoxGroup = new THREE.Group();

    // Materials
    const cardboardMaterial = new THREE.MeshPhongMaterial({
        color: 0xE76F51, // Terracotta Coral box body
        shininess: 15,
        flatShading: true
    });
    
    const lidMaterial = new THREE.MeshPhongMaterial({
        color: 0xF4A261, // Soft peach/sand lid
        shininess: 15,
        flatShading: true
    });

    const satinRibbonMaterial = new THREE.MeshPhongMaterial({
        color: 0xE9C46A, // Shiny candle gold ribbon
        shininess: 95,
        specular: 0xffffff
    });

    // 1. Box Body (Cube primitive)
    const boxGeometry = new THREE.BoxGeometry(2.4, 2.4, 2.4);
    const boxMesh = new THREE.Mesh(boxGeometry, cardboardMaterial);
    giftBoxGroup.add(boxMesh);

    // 2. Box Lid (Slightly larger but flat cube primitive)
    const lidGeometry = new THREE.BoxGeometry(2.55, 0.5, 2.55);
    const lidMesh = new THREE.Mesh(lidGeometry, lidMaterial);
    lidMesh.position.y = 1.35; // Positioned directly on top
    giftBoxGroup.add(lidMesh);

    // 3. Satin Ribbon wrapping (Cross ribbons)
    // Vertical ribbon wrapping over the body and lid
    const ribbon1Geo = new THREE.BoxGeometry(0.38, 2.45, 2.45);
    const ribbon1 = new THREE.Mesh(ribbon1Geo, satinRibbonMaterial);
    giftBoxGroup.add(ribbon1);

    const ribbon2Geo = new THREE.BoxGeometry(2.45, 2.45, 0.38);
    const ribbon2 = new THREE.Mesh(ribbon2Geo, satinRibbonMaterial);
    giftBoxGroup.add(ribbon2);

    // Lid ribbons (Wrapping over the lid part)
    const lidRibbon1Geo = new THREE.BoxGeometry(0.4, 0.55, 2.6);
    const lidRibbon1 = new THREE.Mesh(lidRibbon1Geo, satinRibbonMaterial);
    lidRibbon1.position.y = 1.35;
    giftBoxGroup.add(lidRibbon1);

    const lidRibbon2Geo = new THREE.BoxGeometry(2.6, 0.55, 0.4);
    const lidRibbon2 = new THREE.Mesh(lidRibbon2Geo, satinRibbonMaterial);
    lidRibbon2.position.y = 1.35;
    giftBoxGroup.add(lidRibbon2);

    // 4. Bow Loops on top (Torus primitives squashed/rotated)
    const bowLoopGeo = new THREE.TorusGeometry(0.45, 0.1, 8, 24, Math.PI * 1.55);
    
    const bowLoop1 = new THREE.Mesh(bowLoopGeo, satinRibbonMaterial);
    bowLoop1.position.set(-0.25, 1.8, 0);
    bowLoop1.rotation.set(0, 0, Math.PI * -0.25);
    giftBoxGroup.add(bowLoop1);

    const bowLoop2 = new THREE.Mesh(bowLoopGeo, satinRibbonMaterial);
    bowLoop2.position.set(0.25, 1.8, 0);
    bowLoop2.rotation.set(0, 0, Math.PI * 1.25);
    giftBoxGroup.add(bowLoop2);

    // Bow Center knot (Sphere primitive)
    const knotGeo = new THREE.SphereGeometry(0.2, 12, 12);
    const knotMesh = new THREE.Mesh(knotGeo, satinRibbonMaterial);
    knotMesh.position.set(0, 1.6, 0);
    giftBoxGroup.add(knotMesh);

    scene.add(giftBoxGroup);

    // Cozy Floating Particles/Sparkles around the gift (Holiday Fairy Lights)
    const particleCount = window.innerWidth < 768 ? 40 : 80;
    const particlesGeometry = new THREE.BufferGeometry();
    const positions = new Float32Array(particleCount * 3);
    const colors = new Float32Array(particleCount * 3);

    const goldColor = new THREE.Color('#E9C46A');
    const peachColor = new THREE.Color('#F4A261');

    for (let i = 0; i < particleCount; i++) {
        // Floating cloud geometry
        const r = 3.5 + Math.random() * 2.5;
        const theta = Math.random() * Math.PI * 2;
        const phi = Math.acos((Math.random() * 2) - 1);

        positions[i * 3] = r * Math.sin(phi) * Math.cos(theta);
        positions[i * 3 + 1] = r * Math.sin(phi) * Math.sin(theta);
        positions[i * 3 + 2] = r * Math.cos(phi);

        // Soft warm fairy lights colors
        const particleColor = Math.random() > 0.5 ? goldColor : peachColor;
        colors[i * 3] = particleColor.r;
        colors[i * 3 + 1] = particleColor.g;
        colors[i * 3 + 2] = particleColor.b;
    }

    particlesGeometry.setAttribute('position', new THREE.BufferAttribute(positions, 3));
    particlesGeometry.setAttribute('color', new THREE.BufferAttribute(colors, 3));

    // Fairy light particles shader
    const vertexShader = `
        attribute vec3 color;
        varying vec3 vColor;
        void main() {
            vColor = color;
            vec4 mvPosition = modelViewMatrix * vec4(position, 1.0);
            gl_PointSize = (35.0 / -mvPosition.z) * (1.8 + sin(position.y * 1.5));
            gl_Position = projectionMatrix * mvPosition;
        }
    `;

    const fragmentShader = `
        varying vec3 vColor;
        void main() {
            vec2 pt = gl_PointCoord - vec2(0.5);
            if (dot(pt, pt) > 0.25) discard;
            gl_FragColor = vec4(vColor, 0.8);
        }
    `;

    const particlesMaterial = new THREE.ShaderMaterial({
        vertexShader: vertexShader,
        fragmentShader: fragmentShader,
        transparent: true,
        depthWrite: false,
        blending: THREE.AdditiveBlending
    });

    const fairyLights = new THREE.Points(particlesGeometry, particlesMaterial);
    scene.add(fairyLights);

    // Mouse Tracking Parallax setup
    let mouse = { x: 0, y: 0, targetX: 0, targetY: 0 };
    
    window.addEventListener('mousemove', (e) => {
        // Normalize mouse coordinates from -1 to 1
        mouse.targetX = (e.clientX / window.innerWidth) * 2 - 1;
        mouse.targetY = -(e.clientY / window.innerHeight) * 2 + 1;
    });

    // Touch support for mobile devices
    window.addEventListener('touchmove', (e) => {
        if (e.touches.length > 0) {
            mouse.targetX = (e.touches[0].clientX / window.innerWidth) * 2 - 1;
            mouse.targetY = -(e.touches[0].clientY / window.innerHeight) * 2 + 1;
        }
    });

    // Render loop timer
    const clock = new THREE.Clock();

    function animate() {
        requestAnimationFrame(animate);

        const elapsedTime = clock.getElapsedTime();

        // 1. Elastic interpolation for smooth cursor follow (Mouse Parallax)
        mouse.x += (mouse.targetX - mouse.x) * 0.08;
        mouse.y += (mouse.targetY - mouse.y) * 0.08;

        // Apply mouse offsets to the Gift Box assembly
        giftBoxGroup.rotation.y = elapsedTime * 0.25 + mouse.x * 0.4;
        giftBoxGroup.rotation.x = Math.sin(elapsedTime * 0.15) * 0.15 + mouse.y * 0.4;
        giftBoxGroup.position.y = Math.sin(elapsedTime * 0.8) * 0.12; // Cozy bobbing up and down

        // Gently spin fairy lights
        fairyLights.rotation.y = elapsedTime * 0.03;

        renderer.render(scene, camera);
    }

    animate();

    // Handle responsive container scaling
    window.addEventListener('resize', () => {
        const newWidth = container.clientWidth;
        const newHeight = container.clientHeight;

        camera.aspect = newWidth / newHeight;
        camera.updateProjectionMatrix();

        renderer.setSize(newWidth, newHeight);
    });
}

/* ==========================================================================
   GSAP: Gentle Unwrapping Text Reveals & Scroll Animations (ScrollTrigger Engine)
   ========================================================================== */
function initGSAPAnimations() {
    if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') return;

    // Register GSAP ScrollTrigger plugin
    gsap.registerPlugin(ScrollTrigger);

    // --- HERO ENTRANCE INTRO TIMELINE ---
    const tl = gsap.timeline({ defaults: { ease: 'power3.out' } });

    // Hide initially to prevent FOUC (Flash of Unstyled Content)
    gsap.set('.word-reveal, .hero-desc, .hero-ctas, .badge-premium', { visibility: 'visible' });

    tl.from('.badge-premium', {
        y: -20,
        opacity: 0,
        duration: 1.4
    })
    .from('.word-reveal', {
        y: 40,
        opacity: 0,
        duration: 1.5,
        stagger: 0.12
    }, '-=1.0')
    .from('.hero-desc', {
        y: 15,
        opacity: 0,
        duration: 1.2
    }, '-=1.1')
    .from('.hero-ctas', {
        y: 15,
        opacity: 0,
        duration: 1.2
    }, '-=1.0')
    .from('#canvas-container canvas', {
        scale: 0.9,
        opacity: 0,
        duration: 2.2,
        ease: 'power2.out'
    }, '-=1.4');

    // --- SCROLLTRIGGER: PORTAL CARDS ENTRANCE ---
    gsap.from('.city-card-col', {
        scrollTrigger: {
            trigger: '#cities-section',
            start: 'top 75%',
            toggleActions: 'play none none none'
        },
        y: 50,
        opacity: 0,
        duration: 1.3,
        stagger: 0.15,
        ease: 'power2.out'
    });

    // --- SCROLLTRIGGER: SECONDARY UTILITY CARDS ---
    gsap.from('.secondary-col', {
        scrollTrigger: {
            trigger: '.secondary-grids',
            start: 'top 80%',
            toggleActions: 'play none none none'
        },
        y: 30,
        opacity: 0,
        duration: 1.1,
        stagger: 0.15,
        ease: 'power2.out'
    });
}

/* ==========================================================================
   INTERACTION: Custom 3D Tilt Card and Light Reflection Tracker
   ========================================================================== */
function initTiltInteraction() {
    const cards = document.querySelectorAll('[data-tilt]');
    if (!cards.length) return;

    cards.forEach((card) => {
        const glare = card.querySelector('.card-glare');
        
        card.addEventListener('mousemove', (e) => {
            const rect = card.getBoundingClientRect();
            
            // Mouse coordinates relative to card element
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;

            // Map mouse coordinates to percentages
            const percentX = x / rect.width;
            const percentY = y / rect.height;

            // Set custom CSS coordinates properties for reflection glare tracking
            card.style.setProperty('--mouse-x', `${percentX * 100}%`);
            card.style.setProperty('--mouse-y', `${percentY * 100}%`);

            // Compute exact 3D tilt metrics (Max 10 degrees angle for a softer, organic feel)
            const rotateX = (0.5 - percentY) * 10;
            const rotateY = (percentX - 0.5) * 10;

            // Apply smooth card rotation transforms
            gsap.to(card, {
                rotationX: rotateX,
                rotationY: rotateY,
                scale: 1.015,
                duration: 0.25,
                ease: 'power2.out',
                transformPerspective: 1000
            });
        });

        card.addEventListener('mouseleave', () => {
            // Restore card to default horizontal coordinate state
            gsap.to(card, {
                rotationX: 0,
                rotationY: 0,
                scale: 1,
                duration: 0.5,
                ease: 'power3.out'
            });
        });
    });
}

/* ==========================================================================
   MOBILE INTERACTION: Scroll-into-Middle Unwrapping Observer
   ========================================================================== */
function initMobileScrollUnwrap() {
    const cards = document.querySelectorAll('.city-portal-card');
    if (!cards.length) return;

    let scrollTimeout;

    // Listen to scroll events on window
    window.addEventListener('scroll', () => {
        // Only execute scroll-stop animations on mobile/tablet viewports
        if (window.innerWidth > 991.98) return;

        // Clear active timeout and ensure boxes stay closed while scrolling
        clearTimeout(scrollTimeout);
        
        cards.forEach((card) => {
            card.classList.remove('active-scroll');
            
            // Close 3D gift box on mobile scroll
            const container3d = card.querySelector('.city-3d-container');
            if (container3d && container3d.closeBox) {
                container3d.closeBox();
            }
        });

        // Set timeout to detect when scrolling has fully stopped
        scrollTimeout = setTimeout(() => {
            const viewportCenter = window.innerHeight / 2;
            const triggerMargin = window.innerHeight * 0.18; // Middle 36% of screen

            cards.forEach((card) => {
                const rect = card.getBoundingClientRect();
                const cardCenter = rect.top + rect.height / 2;

                // If the card center lies within our middle viewport band, open it
                if (Math.abs(cardCenter - viewportCenter) < triggerMargin) {
                    card.classList.add('active-scroll');
                    
                    // Open 3D gift box on mobile scroll stop
                    const container3d = card.querySelector('.city-3d-container');
                    if (container3d && container3d.openBox) {
                        container3d.openBox();
                    }
                }
            });
        }, 180); // 180ms of scroll stillness triggers the unwrap
    });
}

/* ==========================================================================
   CITY 3D: Multi-Canvas Gift Box Renderer & GSAP Unwrap Orchestrator
   ========================================================================== */
function initCity3DBoxes() {
    const containers = document.querySelectorAll('.city-3d-container');
    if (!containers.length) return;

    // WebGL support validation
    let isWebGLSupported = (() => {
        try {
            const canvas = document.createElement('canvas');
            return !!(window.WebGLRenderingContext && (canvas.getContext('webgl') || canvas.getContext('experimental-webgl')));
        } catch (e) {
            return false;
        }
    })();

    if (!isWebGLSupported) {
        containers.forEach(container => {
            const fallback = container.querySelector('.canvas-fallback');
            if (fallback) fallback.classList.remove('d-none');
        });
        return;
    }

    // Official brand colors of the project (Primary: Coral/Blue, Secondary: Gold/Purple)
    // Completely excludes any white, cream, or background-matching washouts
    const brandPalette = [
        0xFF6F61, // Coral (Primary Brand Color)
        0x6CA5D8, // Soft Blue (Secondary Brand Color)
        0xFFD700, // Gold/Yellow (Warning Highlight)
        0xA88FBB  // Soft Purple (Info Highlight)
    ];

    containers.forEach((container) => {
        const width = container.clientWidth;
        const height = container.clientHeight;

        // Shuffle the brand palette to get completely different, high-contrast colors per box.
        // This guarantees that the body, lid, ribbon, and particles are always different,
        // always high contrast, and perfectly represent the official site colors!
        const shuffled = [...brandPalette].sort(() => 0.5 - Math.random());
        const bodyColor = shuffled[0];
        const lidColor = shuffled[1];
        const ribbonColor = shuffled[2];
        const particleColor = shuffled[3];

        // 3D Scene core
        const scene = new THREE.Scene();
        const camera = new THREE.PerspectiveCamera(40, width / height, 0.1, 100);
        camera.position.set(0, 0, 8.2);

        const renderer = new THREE.WebGLRenderer({ alpha: true, antialias: true });
        renderer.setSize(width, height);
        renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
        container.appendChild(renderer.domElement);

        // Warm Gifting lighting
        const ambientLight = new THREE.AmbientLight(0xffffff, 0.85);
        scene.add(ambientLight);

        const warmLight = new THREE.PointLight(0xFFE4E1, 2.5, 15);
        warmLight.position.set(5, 5, 5);
        scene.add(warmLight);

        const fillLight = new THREE.PointLight(0xFFFFFF, 1.5, 15);
        fillLight.position.set(-5, -5, 5);
        scene.add(fillLight);

        // Core Gift Box assembly group
        const giftBoxGroup = new THREE.Group();

        // Materials
        const bodyMat = new THREE.MeshPhongMaterial({ color: bodyColor, shininess: 12, flatShading: true });
        const lidMat = new THREE.MeshPhongMaterial({ color: lidColor, shininess: 12, flatShading: true });
        const ribbonMat = new THREE.MeshPhongMaterial({ color: ribbonColor, shininess: 80, specular: 0xffffff });

        // 1. Box Body (Cube primitive - scaled 25% larger to fill view)
        const boxGeo = new THREE.BoxGeometry(1.85, 1.85, 1.85);
        const boxMesh = new THREE.Mesh(boxGeo, bodyMat);
        giftBoxGroup.add(boxMesh);

        // 2. Box Lid (Lid primitive - scaled up)
        const lidGeo = new THREE.BoxGeometry(1.98, 0.4, 1.98);
        const lidMesh = new THREE.Mesh(lidGeo, lidMat);
        lidMesh.position.y = 1.0;
        giftBoxGroup.add(lidMesh);

        // 3. Satin ribbons wrapping (Cross - scaled up)
        const ribbon1Geo = new THREE.BoxGeometry(0.28, 1.9, 1.9);
        const ribbon1 = new THREE.Mesh(ribbon1Geo, ribbonMat);
        giftBoxGroup.add(ribbon1);

        const ribbon2Geo = new THREE.BoxGeometry(1.9, 1.9, 0.28);
        const ribbon2 = new THREE.Mesh(ribbon2Geo, ribbonMat);
        giftBoxGroup.add(ribbon2);

        // Lid ribbon wraps
        const lidRibbon1Geo = new THREE.BoxGeometry(0.3, 0.48, 2.05);
        const lidRibbon1 = new THREE.Mesh(lidRibbon1Geo, ribbonMat);
        lidRibbon1.position.y = 1.0;
        giftBoxGroup.add(lidRibbon1);

        const lidRibbon2Geo = new THREE.BoxGeometry(2.05, 0.48, 0.3);
        const lidRibbon2 = new THREE.Mesh(lidRibbon2Geo, ribbonMat);
        lidRibbon2.position.y = 1.0;
        giftBoxGroup.add(lidRibbon2);

        // 4. Bow Loops on top (Torus primitives - scaled up)
        const bowLoopGeo = new THREE.TorusGeometry(0.32, 0.07, 8, 16, Math.PI * 1.55);
        
        const bowLoop1 = new THREE.Mesh(bowLoopGeo, ribbonMat);
        bowLoop1.position.set(-0.16, 1.3, 0);
        bowLoop1.rotation.set(0, 0, Math.PI * -0.25);
        giftBoxGroup.add(bowLoop1);

        const bowLoop2 = new THREE.Mesh(bowLoopGeo, ribbonMat);
        bowLoop2.position.set(0.16, 1.3, 0);
        bowLoop2.rotation.set(0, 0, Math.PI * 1.25);
        giftBoxGroup.add(bowLoop2);

        // Bow knot sphere
        const knotGeo = new THREE.SphereGeometry(0.15, 10, 10);
        const knotMesh = new THREE.Mesh(knotGeo, ribbonMat);
        knotMesh.position.set(0, 1.15, 0);
        giftBoxGroup.add(knotMesh);

        scene.add(giftBoxGroup);

        // 5. Interactive 3D Confetti Burst Meshes (Expanded count: 45 pieces)
        const confettiGroup = new THREE.Group();
        const confettiCount = 45;
        const confettiMeshes = [];
        const confettiColors = [0xE76F51, 0xF4A261, 0xE9C46A, 0x8A9A86, 0x9B5DE5, 0xF15BB5, 0x00F5D4, 0x00BBF9];
        const confettiGeo = new THREE.PlaneGeometry(0.09, 0.07);

        for (let i = 0; i < confettiCount; i++) {
            const confettiMat = new THREE.MeshPhongMaterial({
                color: confettiColors[Math.floor(Math.random() * confettiColors.length)],
                side: THREE.DoubleSide,
                shininess: 40
            });
            const confettiMesh = new THREE.Mesh(confettiGeo, confettiMat);
            
            // Placed tucked inside the box body initially
            confettiMesh.position.set(
                (Math.random() - 0.5) * 0.4,
                -0.3 + (Math.random() - 0.5) * 0.3,
                (Math.random() - 0.5) * 0.4
            );
            
            confettiMesh.rotation.set(
                Math.random() * Math.PI,
                Math.random() * Math.PI,
                Math.random() * Math.PI
            );

            // Store individual launch trajectories and spinning speeds (Expanded scale)
            confettiMesh.userData = {
                targetX: confettiMesh.position.x + (Math.random() - 0.5) * 2.2,
                targetY: 1.3 + Math.random() * 1.2, // Float upward and outward
                targetZ: confettiMesh.position.z + (Math.random() - 0.5) * 2.2,
                targetRotX: confettiMesh.rotation.x + Math.random() * Math.PI * 4,
                targetRotY: confettiMesh.rotation.y + Math.random() * Math.PI * 4,
                targetRotZ: confettiMesh.rotation.z + Math.random() * Math.PI * 4,
                initialX: confettiMesh.position.x,
                initialY: confettiMesh.position.y,
                initialZ: confettiMesh.position.z,
                initialRotX: confettiMesh.rotation.x,
                initialRotY: confettiMesh.rotation.y,
                initialRotZ: confettiMesh.rotation.z
            };

            // Starts scale 0 (invisible)
            confettiMesh.scale.set(0, 0, 0);

            confettiGroup.add(confettiMesh);
            confettiMeshes.push(confettiMesh);
        }
        scene.add(confettiGroup);

        // 6. Rising 3D Particles/Stars (Fairy lights rising slowly behind the confetti)
        const pCount = 15;
        const pGeometry = new THREE.BufferGeometry();
        const pPositions = new Float32Array(pCount * 3);
        const pColors = new Float32Array(pCount * 3);
        const pColor = new THREE.Color(particleColor);

        for (let i = 0; i < pCount; i++) {
            pPositions[i * 3] = (Math.random() - 0.5) * 0.4;
            pPositions[i * 3 + 1] = -0.5 + (Math.random() - 0.5) * 0.4;
            pPositions[i * 3 + 2] = (Math.random() - 0.5) * 0.4;

            pColors[i * 3] = pColor.r;
            pColors[i * 3 + 1] = pColor.g;
            pColors[i * 3 + 2] = pColor.b;
        }

        pGeometry.setAttribute('position', new THREE.BufferAttribute(pPositions, 3));
        pGeometry.setAttribute('color', new THREE.BufferAttribute(pColors, 3));

        const pMaterial = new THREE.PointsMaterial({
            size: 0.16,
            transparent: true,
            opacity: 0,
            depthWrite: false,
            blending: THREE.AdditiveBlending
        });

        const starParticles = new THREE.Points(pGeometry, pMaterial);
        scene.add(starParticles);

        // Gentle background rotation / floating bobbing timer
        const clock = new THREE.Clock();
        let mouseX = 0;
        
        container.closest('.city-portal-card').addEventListener('mousemove', (e) => {
            const rect = container.getBoundingClientRect();
            mouseX = ((e.clientX - rect.left) / rect.width) * 2 - 1;
        });

        function animate() {
            requestAnimationFrame(animate);

            const elapsed = clock.getElapsedTime();
            
            // Passive floating
            if (!container.isOpening) {
                giftBoxGroup.rotation.y = elapsed * 0.4 + mouseX * 0.25;
                giftBoxGroup.position.y = Math.sin(elapsed * 1.5) * 0.08;
            } else {
                giftBoxGroup.rotation.y += 0.005;
            }

            renderer.render(scene, camera);
        }

        animate();

        // Sizing resize sync
        window.addEventListener('resize', () => {
            const w = container.clientWidth;
            const h = container.clientHeight;
            camera.aspect = w / h;
            camera.updateProjectionMatrix();
            renderer.setSize(w, h);
        });

        // Save open/close triggers as methods on the container element itself
        container.isOpening = false;
        let lidTimeline = null;

        container.openBox = () => {
            if (container.isOpening) return;
            container.isOpening = true;

            lidTimeline = gsap.timeline();

            // 1. Softer Shaking sequence (shorter duration and lower amplitude as requested)
            lidTimeline.to(giftBoxGroup.position, { x: 0.03, duration: 0.05, yoyo: true, repeat: 3 })
                       .to(giftBoxGroup.position, { x: 0, duration: 0.04 })
                       
                       // 2. Open Lid and bow loops
                       .to(lidMesh.position, { y: 2.1, duration: 0.45, ease: "back.out(1.5)" }, "+=0.03")
                       .to(lidMesh.rotation, { x: -0.35, z: -0.25, duration: 0.45, ease: "power2.out" }, "-=0.45")
                       .to([bowLoop1.position, bowLoop2.position, knotMesh.position], { y: "+=1.25", duration: 0.45, ease: "back.out(1.5)" }, "-=0.45")
                       .to([bowLoop1.rotation, bowLoop2.rotation], { z: "+=0.15", duration: 0.45, ease: "power2.out" }, "-=0.45")
                       
                       // 3. Float inner particles up and fade in
                       .to(starParticles.position, { y: 0.8, duration: 0.5, ease: "power2.out" }, "-=0.4")
                       .to(pMaterial, { opacity: 0.8, size: 0.2, duration: 0.4, ease: "power2.out" }, "-=0.5");

            // 4. Pop out the colorful 3D confetti shapes shooting out of the box!
            confettiMeshes.forEach((confetti) => {
                lidTimeline.to(confetti.scale, { x: 1, y: 1, z: 1, duration: 0.2, ease: "power2.out" }, "-=0.6");
                lidTimeline.to(confetti.position, {
                    x: confetti.userData.targetX,
                    y: confetti.userData.targetY,
                    z: confetti.userData.targetZ,
                    duration: 0.65,
                    ease: "power2.out"
                }, "-=0.55");
                lidTimeline.to(confetti.rotation, {
                    x: confetti.userData.targetRotX,
                    y: confetti.userData.targetRotY,
                    z: confetti.userData.targetRotZ,
                    duration: 0.65,
                    ease: "power2.out"
                }, "-=0.65");
            });
        };

        container.closeBox = () => {
            if (!container.isOpening) return;
            container.isOpening = false;

            if (lidTimeline) lidTimeline.kill();

            // Reverse unboxing smoothly, tucking elements back inside the box
            gsap.to(lidMesh.position, { y: 0.85, duration: 0.4, ease: "power2.out" });
            gsap.to(lidMesh.rotation, { x: 0, z: 0, duration: 0.4, ease: "power2.out" });
            gsap.to([bowLoop1.position, bowLoop2.position, knotMesh.position], { y: (i) => i === 2 ? 0.95 : 1.1, duration: 0.4, ease: "power2.out" });
            gsap.to([bowLoop1.rotation, bowLoop2.rotation], { z: (i) => i === 0 ? Math.PI * -0.25 : Math.PI * 1.25, duration: 0.4, ease: "power2.out" });
            gsap.to(starParticles.position, { y: 0, duration: 0.4, ease: "power2.out" });
            gsap.to(pMaterial, { opacity: 0, size: 0.16, duration: 0.3, ease: "power2.out" });
            gsap.to(giftBoxGroup.position, { x: 0, duration: 0.3 });

            // Pull confetti back in
            confettiMeshes.forEach((confetti) => {
                gsap.to(confetti.scale, { x: 0, y: 0, z: 0, duration: 0.35, ease: "power2.in" });
                gsap.to(confetti.position, {
                    x: confetti.userData.initialX,
                    y: confetti.userData.initialY,
                    z: confetti.userData.initialZ,
                    duration: 0.35,
                    ease: "power2.in"
                });
                gsap.to(confetti.rotation, {
                    x: confetti.userData.initialRotX,
                    y: confetti.userData.initialRotY,
                    z: confetti.userData.initialRotZ,
                    duration: 0.35,
                    ease: "power2.in"
                });
            });
        };

        // Hook up event listeners for desktop
        const card = container.closest('.city-portal-card');
        if (card) {
            card.addEventListener('mouseenter', () => {
                if (window.innerWidth <= 991.98) return; // Skip on mobile/tablets to use scroll triggers
                container.openBox();
            });
            card.addEventListener('mouseleave', () => {
                if (window.innerWidth <= 991.98) return; // Skip on mobile/tablets to use scroll triggers
                container.closeBox();
            });
        }
    });
}
</script>
@endpush
