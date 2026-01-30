<?php
/**
 * Modal Component - Memora Movie
 * Modal fullscreen com overlay
 * 
 * Uso: Incluir este arquivo e usar data-modal-trigger e data-modal-target
 */
?>

<!-- Modal Container (hidden by default) -->
<div id="modal-overlay" class="fixed inset-0 z-[100] hidden">
    <!-- Backdrop -->
    <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" onclick="closeModal()"></div>
    
    <!-- Modal Content -->
    <div class="relative z-10 flex items-center justify-center min-h-screen p-4">
        <div id="modal-content" class="relative bg-black w-full max-w-4xl mx-auto rounded-lg overflow-hidden transform scale-95 opacity-0 transition-all duration-300">
            <!-- Close button -->
            <button onclick="closeModal()" class="absolute top-4 right-4 z-20 text-white/70 hover:text-white transition-colors">
                <svg class="w-8 h-8" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 6 6 18"/>
                    <path d="m6 6 12 12"/>
                </svg>
            </button>
            
            <!-- Dynamic content will be inserted here -->
            <div id="modal-body" class="aspect-video bg-black flex items-center justify-center">
                <!-- Placeholder -->
            </div>
        </div>
    </div>
</div>

<script>
// Modal functionality
function openModal(content) {
    const overlay = document.getElementById('modal-overlay');
    const modalContent = document.getElementById('modal-content');
    const modalBody = document.getElementById('modal-body');
    
    // Set content if provided
    if (content) {
        modalBody.innerHTML = content;
    }
    
    // Show modal
    overlay.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    
    // Animate in
    requestAnimationFrame(() => {
        modalContent.classList.remove('scale-95', 'opacity-0');
        modalContent.classList.add('scale-100', 'opacity-100');
    });
}

function closeModal() {
    const overlay = document.getElementById('modal-overlay');
    const modalContent = document.getElementById('modal-content');
    
    // Animate out
    modalContent.classList.add('scale-95', 'opacity-0');
    modalContent.classList.remove('scale-100', 'opacity-100');
    
    // Hide after animation
    setTimeout(() => {
        overlay.classList.add('hidden');
        document.body.style.overflow = '';
    }, 300);
}

// Close on escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeModal();
    }
});

// Open modal with video player
function openVideoModal(videoUrl) {
    const isYoutube = videoUrl.includes('youtube.com') || videoUrl.includes('youtu.be');
    let content = '';
    
    if (isYoutube) {
        // Extract video ID and create embed
        let videoId = '';
        if (videoUrl.includes('embed/')) {
            videoId = videoUrl.split('embed/')[1].split('?')[0];
        } else if (videoUrl.includes('v=')) {
            videoId = videoUrl.split('v=')[1].split('&')[0];
        } else if (videoUrl.includes('youtu.be/')) {
            videoId = videoUrl.split('youtu.be/')[1].split('?')[0];
        }
        
        content = `<iframe 
            class="w-full h-full absolute inset-0" 
            src="https://www.youtube.com/embed/${videoId}?autoplay=1" 
            frameborder="0" 
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
            allowfullscreen>
        </iframe>`;
    } else {
        content = `<video 
            class="w-full h-full" 
            controls 
            autoplay>
            <source src="${videoUrl}" type="video/mp4">
        </video>`;
    }
    
    openModal(content);
}
</script>
