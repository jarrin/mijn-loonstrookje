<svg 
    viewBox="0 0 1920 600" 
    preserveAspectRatio="xMidYMid slice" 
    xmlns="http://www.w3.org/2000/svg"
    width="100%"
    height="100%"
    style="position: absolute; top: 0; left: 0;"
>
    <defs>
        <linearGradient id="bgGradient" x1="0%" y1="0%" x2="100%" y2="100%">
            <stop offset="0%" style="stop-color:var(--header-bg-start, #ffffff);stop-opacity:1" />
            <stop offset="100%" style="stop-color:var(--header-bg-end, #f0f4ff);stop-opacity:1" />
        </linearGradient>
        
        <linearGradient id="waveGradient" x1="0%" y1="0%" x2="0%" y2="100%">
            <stop offset="0%" style="stop-color:var(--wave-color, #0052cc);stop-opacity:0.08" />
            <stop offset="100%" style="stop-color:var(--wave-color, #0052cc);stop-opacity:0" />
        </linearGradient>
        
        <linearGradient id="accentGradient" x1="0%" y1="0%" x2="100%" y2="100%">
            <stop offset="0%" style="stop-color:var(--accent-start, #0052cc);stop-opacity:0.12" />
            <stop offset="100%" style="stop-color:var(--accent-end, #0066ff);stop-opacity:0.06" />
        </linearGradient>

        <filter id="soften">
            <feGaussianBlur in="SourceGraphic" stdDeviation="2" />
        </filter>
    </defs>
    
    <!-- Background -->
    <rect width="1920" height="600" fill="url(#bgGradient)"/>
    
    <!-- Large subtle wave at bottom -->
    <path 
        d="M 0,420 Q 480,370 960,410 T 1920,420 L 1920,600 L 0,600 Z" 
        fill="url(#waveGradient)"
        opacity="0.9"
    />
    
    <!-- Secondary gentle wave -->
    <path 
        d="M 0,450 Q 240,420 480,450 T 960,450 T 1440,450 T 1920,450 L 1920,600 L 0,600 Z" 
        fill="url(#waveGradient)"
        opacity="0.7"
    />
    
    <!-- Abstract document shapes - left side -->
    <g opacity="1">
        <!-- Large document outline -->
        <rect x="60" y="80" width="120" height="160" fill="none" stroke="var(--accent-color, #0052cc)" stroke-width="2" opacity="0.25" rx="4"/>
        <!-- Document lines -->
        <line x1="80" y1="110" x2="160" y2="110" stroke="var(--accent-color, #0052cc)" stroke-width="1.5" opacity="0.18"/>
        <line x1="80" y1="130" x2="160" y2="130" stroke="var(--accent-color, #0052cc)" stroke-width="1.5" opacity="0.18"/>
        <line x1="80" y1="150" x2="150" y2="150" stroke="var(--accent-color, #0052cc)" stroke-width="1.5" opacity="0.18"/>
        <line x1="80" y1="170" x2="160" y2="170" stroke="var(--accent-color, #0052cc)" stroke-width="1.5" opacity="0.18"/>
        <line x1="80" y1="190" x2="140" y2="190" stroke="var(--accent-color, #0052cc)" stroke-width="1.5" opacity="0.18"/>
    </g>
    
    <!-- Shield / security symbol - center left -->
    <g opacity="1" filter="url(#soften)">
        <path 
            d="M 317,210 L 360,230 L 360,285 Q 360,315 317,335 Q 275,315 275,285 L 275,230 Z" 
            fill="none" 
            stroke="var(--accent-color, #0052cc)" 
            stroke-width="1.8"
            opacity="0.28"
        />
    </g>
    
    <!-- Lock/Security symbol - right side of text -->
    <g opacity="1" filter="url(#soften)">
        <!-- Lock body -->
        <rect x="1510" y="85" width="70" height="75" fill="none" stroke="var(--accent-color, #0052cc)" stroke-width="1.8" opacity="0.24" rx="2"/>
        <!-- Lock shackle/arc -->
        <path d="M 1530 85 Q 1530 55 1545 55 Q 1560 55 1560 85" fill="none" stroke="var(--accent-color, #0052cc)" stroke-width="1.8" opacity="0.24" stroke-linecap="round"/>
        <!-- Lock keyhole circle -->
        <circle cx="1545" cy="120" r="4" fill="none" stroke="var(--accent-color, #0052cc)" stroke-width="1.5" opacity="0.2"/>
        <!-- Keyhole line -->
        <line x1="1545" y1="124" x2="1545" y2="135" stroke="var(--accent-color, #0052cc)" stroke-width="1.5" opacity="0.18"/>
    </g>
    
    
    <!-- Document stack - right side -->
    <g opacity="1">
        <!-- Back document -->
        <rect x="1740" y="100" width="100" height="130" fill="none" stroke="var(--accent-color, #0052cc)" stroke-width="2" opacity="0.2" rx="3"/>
        <!-- Middle document (offset) -->
        <rect x="1755" y="115" width="100" height="130" fill="none" stroke="var(--accent-color, #0052cc)" stroke-width="2" opacity="0.22" rx="3"/>
        <!-- Front document (offset) -->
        <rect x="1770" y="130" width="100" height="130" fill="none" stroke="var(--accent-color, #0052cc)" stroke-width="2.5" opacity="0.25" rx="3"/>
        <!-- Checkmark on front -->
        <path d="M 1785 175 L 1795 185 L 1815 165" fill="none" stroke="var(--accent-color, #0052cc)" stroke-width="2" opacity="0.3" stroke-linecap="round" stroke-linejoin="round"/>
        <!-- Document lines on front -->
        <line x1="1790" y1="145" x2="1860" y2="145" stroke="var(--accent-color, #0052cc)" stroke-width="1.5" opacity="0.18"/>
        <line x1="1790" y1="165" x2="1860" y2="165" stroke="var(--accent-color, #0052cc)" stroke-width="1.5" opacity="0.18"/>
    </g>
    
    
    <!-- Grid accent removed -->

    
    <!-- Soft gradient overlay for smooth transition -->
    <defs>
        <linearGradient id="fadeOut" x1="0%" y1="0%" x2="0%" y2="100%">
            <stop offset="0%" style="stop-color:#ffffff;stop-opacity:0" />
            <stop offset="70%" style="stop-color:#ffffff;stop-opacity:0" />
            <stop offset="100%" style="stop-color:rgba(0, 82, 204, 0.02);stop-opacity:1" />
        </linearGradient>
    </defs>
    <rect width="1920" height="600" fill="url(#fadeOut)"/>
</svg>
