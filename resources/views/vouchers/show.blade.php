@extends('layouts.app')

@section('title', 'Your Digital Gift - Gift-XP')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-7">
            
            <!-- State 1: Wrapped Gift -->
            <div id="gift-wrapped" class="text-center py-4">
                <span class="badge-premium mb-4 d-inline-flex align-items-center gap-2 px-3 py-2 rounded-pill animate-float">
                    <i class="bi bi-gift-fill text-danger"></i>
                    <span>A special moment has been prepared for you</span>
                </span>
                
                <!-- Immersive 3D Gift Box Canvas Container -->
                <div id="gift-canvas-container" class="mx-auto my-3 position-relative" style="width: 320px; height: 320px; max-width: 100%;">
                    <div class="canvas-fallback d-none flex-column align-items-center justify-content-center text-center p-4">
                        <i class="bi bi-gift-fill text-primary animate-pulse" style="font-size: 6rem;"></i>
                    </div>
                </div>
                
                <h1 class="h2 fw-bold text-warm-espresso mb-2">You've received a gift!</h1>
                <p class="text-muted fs-5 mb-5">From: <strong class="text-primary">{{ $voucher->order->gifter->name }}</strong></p>

                <div class="d-flex justify-content-center">
                    <button class="btn-premium px-5 py-3 rounded-pill text-decoration-none shadow-glow text-white d-flex align-items-center gap-2" id="unwrap-btn">
                        <i class="bi bi-stars"></i>
                        <span>Unwrap Your Gift</span>
                    </button>
                </div>
            </div>

            <!-- State 2: Unwrapped Voucher (Hidden initially) -->
            <div id="gift-unwrapped" class="d-none">
                
                <!-- Part 1: Gifter Personalized Greeting Letter -->
                @if($voucher->personal_message || $voucher->custom_photo)
                    <div id="personalization-letter" class="card border-0 shadow-sm rounded-4 p-4 p-md-5 mb-5 mx-auto bg-white border border-light-subtle" style="max-width: 540px; transform: translateY(40px); opacity: 0;">
                        <div class="letter-seal text-center mb-4">
                            <span class="rounded-circle d-inline-flex align-items-center justify-content-center text-white" style="width: 48px; height: 44px; background-color: var(--accent-coral); font-family: 'Space Grotesk', sans-serif; font-weight: 700; font-size: 1.1rem; line-height: 1;">
                                XP
                            </span>
                        </div>
                        <div class="card-body p-0 text-center">
                            @if($voucher->custom_photo)
                                <div class="mb-4 overflow-hidden rounded-4 border border-light shadow-sm">
                                    <img src="{{ Storage::url($voucher->custom_photo) }}" class="w-100 shadow-sm" style="max-height: 380px; object-fit: cover;">
                                </div>
                            @endif

                            @if($voucher->personal_message)
                                <p class="fst-italic text-warm-espresso fs-5 mb-0 px-2 line-height-relaxed">
                                    <i class="bi bi-quote text-primary fs-3 d-block mb-1"></i>
                                    "{{ $voucher->personal_message }}"
                                    <i class="bi bi-quote text-primary bi-quote-reverse fs-3 d-block mt-2"></i>
                                </p>
                            @endif
                            <div class="letter-footer border-top border-light-subtle mt-4 pt-3 text-muted small">
                                Sent with love by <strong>{{ $voucher->order->gifter->name }}</strong>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Part 2: The Core Digital Voucher Card -->
                <div id="voucher-card-main" class="card shadow border-0 overflow-hidden rounded-4 bg-white" style="transform: translateY(60px); opacity: 0;">
                    
                    <div class="py-4 text-center border-bottom border-light" style="background-color: #FFFDFB;">
                        <h1 class="h5 fw-bold text-warm-espresso mb-1">Digital Gift Voucher</h1>
                        <p class="small text-muted mb-0">Gift-XP - Share curated local joy</p>
                    </div>
                    
                    <div class="card-body p-4 p-md-5 text-center">
                        @if(!empty($voucher->product->images))
                            <div class="mb-4">
                                <div class="card border-0 shadow-sm rounded-4 overflow-hidden mx-auto" style="max-width: 400px; border: 1px solid #E2E8F0 !important;">
                                    <img src="{{ Storage::url($voucher->product->images[0]) }}" class="card-img-top" style="height: 250px; object-fit: cover;">
                                </div>
                            </div>
                        @endif

                        @if($voucher->status === 'claimed')
                            <div class="badge bg-secondary rounded-pill px-3 py-2 mb-4">REDEEMED</div>
                        @elseif($voucher->status === 'expired')
                            <div class="badge bg-danger rounded-pill px-3 py-2 mb-4">EXPIRED</div>
                        @else
                            <div class="badge bg-success rounded-pill px-3 py-2 mb-4">ACTIVE</div>
                        @endif

                        <h2 class="h3 fw-bold text-warm-espresso mb-1">{{ $voucher->product->name }}</h2>
                        <div class="h4 text-primary fw-extrabold mb-3">₱{{ number_format($voucher->price ?? $voucher->product->price, 2) }}</div>
                        <p class="text-muted mb-4">By: <strong class="text-dark">{{ $voucher->product->store->name }}</strong></p>
                        
                        @if($voucher->product->description)
                            <div class="mb-4 px-md-5">
                                <p class="text-muted small">{{ $voucher->product->description }}</p>
                            </div>
                        @endif

                        @if($voucher->status === 'claimed' || $voucher->status === 'expired')
                            <div class="my-5 mx-auto p-4 bg-light d-flex flex-column justify-content-center align-items-center rounded-4 shadow-sm border border-light" style="width: 250px; height: 250px;">
                                @if($voucher->status === 'claimed')
                                    <div class="rounded-circle d-flex align-items-center justify-content-center mb-3 bg-white shadow-sm border border-secondary" style="width: 64px; height: 64px;">
                                        <i class="bi bi-gift text-secondary fs-3"></i>
                                    </div>
                                    <h4 class="fw-bold text-secondary mb-1 small uppercase tracking-wider">Redeemed</h4>
                                    <p class="small text-muted mb-0 text-center">This voucher has been claimed.</p>
                                @else
                                    <div class="rounded-circle d-flex align-items-center justify-content-center mb-3 bg-white shadow-sm border border-danger" style="width: 64px; height: 64px;">
                                        <i class="bi bi-clock-history text-danger fs-3"></i>
                                    </div>
                                    <h4 class="fw-bold text-danger mb-1 small uppercase tracking-wider">Expired</h4>
                                    <p class="small text-muted mb-0 text-center">This voucher is no longer valid.</p>
                                @endif
                            </div>
                        @else
                            <div class="my-5 p-4 bg-white d-inline-block rounded-4 shadow-sm border border-light-subtle position-relative">
                                <div class="qr-corner-decor border-top border-start border-primary" style="position: absolute; top: -5px; left: -5px; width: 20px; height: 20px; border-width: 2px !important;"></div>
                                <div class="qr-corner-decor border-top border-end border-primary" style="position: absolute; top: -5px; right: -5px; width: 20px; height: 20px; border-width: 2px !important;"></div>
                                <div class="qr-corner-decor border-bottom border-start border-primary" style="position: absolute; bottom: -5px; left: -5px; width: 20px; height: 20px; border-width: 2px !important;"></div>
                                <div class="qr-corner-decor border-bottom border-end border-primary" style="position: absolute; bottom: -5px; right: -5px; width: 20px; height: 20px; border-width: 2px !important;"></div>
                                {!! $qrCode !!}
                            </div>
                            <p class="text-muted small mb-5 px-md-4">
                                Please present this QR code at any participating store branch to redeem your gift.
                            </p>
                        @endif

                        <div class="text-start bg-light p-4 rounded-4 border border-light">
                            <h5 class="h6 fw-bold mb-3"><i class="bi bi-geo-alt-fill text-primary me-2"></i>Participating Branches</h5>
                            
                            @php
                                $groupedBranches = $voucher->product->store->branches->groupBy(function($branch) {
                                    return $branch->city->name ?? 'Other';
                                });
                            @endphp

                            @foreach($groupedBranches as $cityName => $branches)
                                <div class="mb-4">
                                    <h6 class="fw-bold text-uppercase small text-muted border-bottom pb-1 mb-3">{{ $cityName }}</h6>
                                    <ul class="list-unstyled mb-0">
                                        @foreach($branches as $branch)
                                            <li class="mb-3 d-flex justify-content-between align-items-center">
                                                <div>
                                                    <div class="fw-bold text-warm-espresso small">{{ $branch->name }}</div>
                                                    <div class="text-muted" style="font-size: 0.75rem;">{{ $branch->address }}</div>
                                                </div>
                                                @if($branch->map_url)
                                                    <a href="{{ $branch->map_url }}" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill ms-3" title="View on Google Maps">
                                                        <i class="bi bi-geo-alt-fill"></i>
                                                    </a>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-5 text-muted small border-top border-light-subtle pt-4">
                            Valid until: {{ $voucher->expires_at ? $voucher->expires_at->format('M d, Y') : 'N/A' }}<br>
                            Voucher ID: <code>{{ $voucher->unique_token }}</code>
                        </div>
                    </div>
                </div>
                
                <div class="text-center mt-5">
                    <a href="{{ url('/') }}" class="btn btn-link text-decoration-none text-muted">Visit Gift-XP</a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* 3D Gifting fallbacks and styles */
    #gift-canvas-container canvas {
        width: 100% !important;
        height: 100% !important;
        position: absolute;
        top: 0; left: 0;
        z-index: 5;
        transition: opacity 0.8s ease;
    }
    .canvas-fallback {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        background-color: rgba(255, 111, 97, 0.05);
    }
    .line-height-relaxed {
        line-height: 1.6;
    }

    /* Solid High-Contrast Unwrap Button Styles */
    .btn-premium {
        background-color: #E76F51 !important;
        border: 2px solid #E76F51 !important;
        color: #FFFFFF !important;
        font-family: 'Space Grotesk', sans-serif;
        font-weight: 700;
        letter-spacing: -0.01em;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 15px rgba(231, 111, 81, 0.15) !important;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1) !important;
        cursor: pointer;
    }
    .btn-premium:hover {
        background-color: #d1563f !important;
        border-color: #d1563f !important;
        transform: translateY(-2px) !important;
        box-shadow: 0 8px 25px rgba(231, 111, 81, 0.25) !important;
    }
</style>
@endsection

@push('scripts')
<!-- GSAP & Plugins (ScrollTrigger) via cloudflare CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // Initialize Three.js 3D Unwrap gift Box
    initThreeJSGift();
});

/* ==========================================================================
   THREE.JS: Interactive 3D Gift Box Wrapping with Satin Ribbon
   ========================================================================== */
function initThreeJSGift() {
    const container = document.getElementById('gift-canvas-container');
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
    
    // Soft camera positioned closer for full details
    const camera = new THREE.PerspectiveCamera(40, width / height, 0.1, 100);
    camera.position.z = 10;

    const renderer = new THREE.WebGLRenderer({ alpha: true, antialias: true });
    renderer.setSize(width, height);
    renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
    container.appendChild(renderer.domElement);

    // Warm Gifting Light Sources
    const ambientLight = new THREE.AmbientLight(0xffffff, 0.8); 
    scene.add(ambientLight);

    const warmLight = new THREE.PointLight(0xFFD700, 3.0, 20); // Golden shine
    warmLight.position.set(5, 5, 8);
    scene.add(warmLight);

    const roseLight = new THREE.PointLight(0xFFB6C1, 2.5, 20); // Rose shine
    roseLight.position.set(-5, -5, 8);
    scene.add(roseLight);

    // Group to hold the entire Gift Box assembly
    const giftBoxGroup = new THREE.Group();

    // Soft warm materials
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

    // 1. Box Body
    const boxGeometry = new THREE.BoxGeometry(2.3, 2.3, 2.3);
    const boxMesh = new THREE.Mesh(boxGeometry, cardboardMaterial);
    giftBoxGroup.add(boxMesh);

    // 2. Box Lid
    const lidGeometry = new THREE.BoxGeometry(2.45, 0.5, 2.45);
    const lidMesh = new THREE.Mesh(lidGeometry, lidMaterial);
    lidMesh.position.y = 1.3;
    giftBoxGroup.add(lidMesh);

    // 3. Ribbon wrapping (Cross ribbons)
    const ribbon1Geo = new THREE.BoxGeometry(0.36, 2.35, 2.35);
    const ribbon1 = new THREE.Mesh(ribbon1Geo, satinRibbonMaterial);
    giftBoxGroup.add(ribbon1);

    const ribbon2Geo = new THREE.BoxGeometry(2.35, 2.35, 0.36);
    const ribbon2 = new THREE.Mesh(ribbon2Geo, satinRibbonMaterial);
    giftBoxGroup.add(ribbon2);

    // Lid ribbons (Wrapping over the lid part)
    const lidRibbon1Geo = new THREE.BoxGeometry(0.38, 0.55, 2.5);
    const lidRibbon1 = new THREE.Mesh(lidRibbon1Geo, satinRibbonMaterial);
    lidRibbon1.position.y = 1.3;
    giftBoxGroup.add(lidRibbon1);

    const lidRibbon2Geo = new THREE.BoxGeometry(2.5, 0.55, 0.38);
    const lidRibbon2 = new THREE.Mesh(lidRibbon2Geo, satinRibbonMaterial);
    lidRibbon2.position.y = 1.3;
    giftBoxGroup.add(lidRibbon2);

    // 4. Bow Loops on top (Torus primitives)
    const bowLoopGeo = new THREE.TorusGeometry(0.42, 0.09, 8, 24, Math.PI * 1.55);
    
    const bowLoop1 = new THREE.Mesh(bowLoopGeo, satinRibbonMaterial);
    bowLoop1.position.set(-0.22, 1.7, 0);
    bowLoop1.rotation.set(0, 0, Math.PI * -0.25);
    giftBoxGroup.add(bowLoop1);

    const bowLoop2 = new THREE.Mesh(bowLoopGeo, satinRibbonMaterial);
    bowLoop2.position.set(0.22, 1.7, 0);
    bowLoop2.rotation.set(0, 0, Math.PI * 1.25);
    giftBoxGroup.add(bowLoop2);

    // Bow Center knot (Sphere primitive)
    const knotGeo = new THREE.SphereGeometry(0.18, 12, 12);
    const knotMesh = new THREE.Mesh(knotGeo, satinRibbonMaterial);
    knotMesh.position.set(0, 1.5, 0);
    giftBoxGroup.add(knotMesh);

    scene.add(giftBoxGroup);

    // Cozy Floating Celebratory Confetti/Lights (Bursts out on click)
    const particleCount = 100;
    const particlesGeometry = new THREE.BufferGeometry();
    const positions = new Float32Array(particleCount * 3);
    const colors = new Float32Array(particleCount * 3);
    const velocities = [];

    const goldColor = new THREE.Color('#E9C46A');
    const coralColor = new THREE.Color('#E76F51');
    const sageColor = new THREE.Color('#8A9A86');

    for (let i = 0; i < particleCount; i++) {
        // Initial coordinates (hidden inside the box body)
        positions[i * 3] = (Math.random() - 0.5) * 0.5;
        positions[i * 3 + 1] = (Math.random() - 0.5) * 0.5;
        positions[i * 3 + 2] = (Math.random() - 0.5) * 0.5;

        // Custom velocity vector for individual particle trajectories (will erupt on unwrap)
        velocities.push({
            x: (Math.random() - 0.5) * 0.12,
            y: (0.1 + Math.random() * 0.18), // Shoot upward and outward
            z: (Math.random() - 0.5) * 0.12
        });

        // Soft pastel colors
        let particleColor = goldColor;
        const randVal = Math.random();
        if (randVal > 0.65) {
            particleColor = coralColor;
        } else if (randVal > 0.35) {
            particleColor = sageColor;
        }

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
            gl_PointSize = (45.0 / -mvPosition.z);
            gl_Position = projectionMatrix * mvPosition;
        }
    `;

    const fragmentShader = `
        varying vec3 vColor;
        void main() {
            vec2 pt = gl_PointCoord - vec2(0.5);
            if (dot(pt, pt) > 0.25) discard;
            gl_FragColor = vec4(vColor, 0.95);
        }
    `;

    const particlesMaterial = new THREE.ShaderMaterial({
        vertexShader: vertexShader,
        fragmentShader: fragmentShader,
        transparent: true,
        depthWrite: false,
        blending: THREE.AdditiveBlending
    });

    const unwrapConfetti = new THREE.Points(particlesGeometry, particlesMaterial);
    unwrapConfetti.visible = false; // Hidden initially until unwrapping triggers
    scene.add(unwrapConfetti);

    // Mouse coordinates Tracking (gentle hover tilt)
    let mouse = { x: 0, y: 0, targetX: 0, targetY: 0 };
    window.addEventListener('mousemove', (e) => {
        mouse.targetX = (e.clientX / window.innerWidth) * 2 - 1;
        mouse.targetY = -(e.clientY / window.innerHeight) * 2 + 1;
    });

    // Touch support for mobile
    window.addEventListener('touchmove', (e) => {
        if (e.touches.length > 0) {
            mouse.targetX = (e.touches[0].clientX / window.innerWidth) * 2 - 1;
            mouse.targetY = -(e.touches[0].clientY / window.innerHeight) * 2 + 1;
        }
    });

    // Render loop timer
    const clock = new THREE.Clock();
    let isUnwrapped = false;

    function animate() {
        requestAnimationFrame(animate);

        const elapsedTime = clock.getElapsedTime();

        if (!isUnwrapped) {
            // Elastic float follow (Mouse Parallax)
            mouse.x += (mouse.targetX - mouse.x) * 0.08;
            mouse.y += (mouse.targetY - mouse.y) * 0.08;

            giftBoxGroup.rotation.y = elapsedTime * 0.35 + mouse.x * 0.4;
            giftBoxGroup.rotation.x = Math.sin(elapsedTime * 0.2) * 0.1 + mouse.y * 0.4;
            giftBoxGroup.position.y = Math.sin(elapsedTime * 1.1) * 0.1; // Soft floating bobbing
        } else {
            // Unwrapped state: Erupt confetti
            const posAttr = particlesGeometry.getAttribute('position');
            for (let i = 0; i < particleCount; i++) {
                posAttr.array[i * 3] += velocities[i].x;
                posAttr.array[i * 3 + 1] += velocities[i].y;
                posAttr.array[i * 3 + 2] += velocities[i].z;
                
                // Add soft gravity/deceleration drift
                velocities[i].x *= 0.98;
                velocities[i].y *= 0.98;
                velocities[i].z *= 0.98;
            }
            posAttr.needsUpdate = true;
        }

        renderer.render(scene, camera);
    }

    animate();

    // Resize Handler
    window.addEventListener('resize', () => {
        const newWidth = container.clientWidth;
        const newHeight = container.clientHeight;
        camera.aspect = newWidth / newHeight;
        camera.updateProjectionMatrix();
        renderer.setSize(newWidth, newHeight);
    });

    /* ==========================================================================
       GSAP: Core Unwrapping Orchestrator Event Click
       ========================================================================== */
    const unwrapBtn = document.getElementById('unwrap-btn');
    if (!unwrapBtn) return;

    unwrapBtn.addEventListener('click', () => {
        unwrapBtn.disabled = true;
        unwrapBtn.innerHTML = '<i class="bi bi-clock-history"></i><span>Unwrapping...</span>';

        isUnwrapped = true; // Signals particle system to begin drifting

        // 1. Choreographed 3D Box Shaking
        const shakeTl = gsap.timeline();
        shakeTl.to(giftBoxGroup.position, { x: 0.12, duration: 0.05, yoyo: true, repeat: 14 })
               .to(giftBoxGroup.position, { x: 0, duration: 0.05 });

        // 2. Explode Bow off
        gsap.to([bowLoop1, bowLoop2, knotMesh], {
            y: "+=3.5",
            scaleX: 0.1, scaleY: 0.1, scaleZ: 0.1,
            rotationX: 8, rotationY: 8,
            duration: 0.7,
            delay: 0.7,
            ease: "power2.in"
        });

        // 3. Lift and Cast Lid off
        gsap.to(lidMesh, {
            y: "+=5.5",
            rotationX: 2.5,
            rotationZ: 1.5,
            scaleX: 0.1, scaleY: 0.1, scaleZ: 0.1,
            duration: 0.9,
            delay: 0.8,
            ease: "power2.inOut"
        });

        // 4. Trigger Erupting Confetti Particle System Visibility
        setTimeout(() => {
            unwrapConfetti.visible = true;
        }, 820);

        // 5. Dive Camera closer inside the box and Fade Canvas Opacity
        gsap.to(camera.position, {
            z: 2,
            duration: 1.2,
            delay: 1.0,
            ease: "power2.in",
            onComplete: () => {
                // Fade canvas element
                const canvas = container.querySelector('canvas');
                if (canvas) canvas.style.opacity = '0';
            }
        });

        // 6. Transition DOM views and animate sliding letter & cards
        setTimeout(() => {
            const wrappedSection = document.getElementById('gift-wrapped');
            const unwrappedSection = document.getElementById('gift-unwrapped');

            wrappedSection.classList.add('d-none');
            unwrappedSection.classList.remove('d-none');

            // Sequential sliding elements reveals
            const revealTl = gsap.timeline({ defaults: { ease: 'power3.out', duration: 1.4 } });
            
            const letter = document.getElementById('personalization-letter');
            const mainCard = document.getElementById('voucher-card-main');

            if (letter) {
                revealTl.to(letter, {
                    y: 0,
                    opacity: 1,
                    duration: 1.2
                });
                revealTl.to(mainCard, {
                    y: 0,
                    opacity: 1,
                    duration: 1.2
                }, "-=0.8");
            } else {
                revealTl.to(mainCard, {
                    y: 0,
                    opacity: 1,
                    duration: 1.2
                });
            }
        }, 2200);
    });
}
</script>
@endpush
