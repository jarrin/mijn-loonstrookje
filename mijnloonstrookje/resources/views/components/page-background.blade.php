<div class="loginIllustration">
    <svg viewBox="0 0 1920 1080" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice">
        <defs>
            <linearGradient id="waveGradient" x1="0%" y1="0%" x2="0%" y2="100%">
                <stop offset="0%" style="stop-color:#0052cc;stop-opacity:0.08" />
                <stop offset="100%" style="stop-color:#0052cc;stop-opacity:0" />
            </linearGradient>

            <filter id="soften">
                <feGaussianBlur in="SourceGraphic" stdDeviation="2" />
            </filter>
        </defs>
        
        <!-- Large subtle wave at bottom -->
        <path 
            d="M 0,850 Q 480,800 960,840 T 1920,850 L 1920,1080 L 0,1080 Z" 
            fill="url(#waveGradient)"
            opacity="0.6"
        />
        
        <!-- Secondary gentle wave -->
        <path 
            d="M 0,920 Q 240,880 480,920 T 960,920 T 1440,920 T 1920,920 L 1920,1080 L 0,1080 Z" 
            fill="url(#waveGradient)"
            opacity="0.4"
        />
        
        <!-- Abstract document shapes - left side -->
        <g opacity="0.7">
            <!-- Large document outline -->
            <rect x="120" y="150" width="120" height="160" fill="none" stroke="#0052cc" stroke-width="2" opacity="0.15" rx="4"/>
            <!-- Document lines -->
            <line x1="140" y1="180" x2="220" y2="180" stroke="#0052cc" stroke-width="1.5" opacity="0.1"/>
            <line x1="140" y1="200" x2="220" y2="200" stroke="#0052cc" stroke-width="1.5" opacity="0.1"/>
            <line x1="140" y1="220" x2="210" y2="220" stroke="#0052cc" stroke-width="1.5" opacity="0.1"/>
            <line x1="140" y1="240" x2="220" y2="240" stroke="#0052cc" stroke-width="1.5" opacity="0.1"/>
            <line x1="140" y1="260" x2="200" y2="260" stroke="#0052cc" stroke-width="1.5" opacity="0.1"/>
        </g>
        
        <!-- Shield / security symbol - left -->
        <g opacity="0.6" filter="url(#soften)">
            <path 
                d="M 220,420 L 270,420 L 270,470 Q 270,515 220,535 Q 170,515 170,470 L 170,420 Z" 
                fill="none" 
                stroke="#0052cc" 
                stroke-width="2"
                opacity="0.2"
            />
        </g>
        
        <!-- Circular accent elements - center area -->
        <g opacity="0.5">
            <!-- Circle 1 -->
            <circle cx="480" cy="320" r="35" fill="none" stroke="#0052cc" stroke-width="1.5" opacity="0.15"/>
            <circle cx="480" cy="320" r="25" fill="none" stroke="#0052cc" stroke-width="1" opacity="0.1"/>
            
            <!-- Circle 2 - right side -->
            <circle cx="1520" cy="500" r="40" fill="none" stroke="#0052cc" stroke-width="1.5" opacity="0.12"/>
            <circle cx="1520" cy="500" r="28" fill="none" stroke="#0052cc" stroke-width="1" opacity="0.08"/>
        </g>
        
        <!-- Calendar - right side -->
        <g opacity="0.6">
            <!-- Calendar body -->
            <rect x="1580" y="200" width="120" height="110" fill="none" stroke="#0052cc" stroke-width="2" opacity="0.15" rx="4"/>
            <!-- Calendar header -->
            <rect x="1580" y="200" width="120" height="28" fill="none" stroke="#0052cc" stroke-width="2" opacity="0.15" rx="4"/>
            <!-- Calendar hooks -->
            <line x1="1605" y1="190" x2="1605" y2="210" stroke="#0052cc" stroke-width="2" opacity="0.15" stroke-linecap="round"/>
            <line x1="1675" y1="190" x2="1675" y2="210" stroke="#0052cc" stroke-width="2" opacity="0.15" stroke-linecap="round"/>
            <!-- Calendar grid -->
            <circle cx="1598" cy="248" r="4" fill="#0052cc" opacity="0.1"/>
            <circle cx="1640" cy="248" r="4" fill="#0052cc" opacity="0.1"/>
            <circle cx="1682" cy="248" r="4" fill="#0052cc" opacity="0.1"/>
            <circle cx="1598" cy="285" r="4" fill="#0052cc" opacity="0.1"/>
            <circle cx="1640" cy="285" r="4" fill="#0052cc" opacity="0.15"/>
            <circle cx="1682" cy="285" r="4" fill="#0052cc" opacity="0.1"/>
        </g>
    </svg>
</div>
