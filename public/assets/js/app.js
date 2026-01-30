/**
 * App.js - Memora Movie
 * JavaScript principal para funcionalidades globais
 */

// API Helper
const API_BASE = '/api';

const api = {
    async fetch(endpoint, options = {}) {
        const response = await fetch(API_BASE + endpoint, {
            ...options,
            headers: {
                'Content-Type': 'application/json',
                ...options.headers,
            },
        });

        const result = await response.json();

        if (!result.ok) {
            throw new Error(result.error || 'Erro na requisição');
        }

        return result.data;
    },

    get: (endpoint) => api.fetch(endpoint, { method: 'GET' }),
    post: (endpoint, body) => api.fetch(endpoint, { method: 'POST', body: JSON.stringify(body) }),
    put: (endpoint, body) => api.fetch(endpoint, { method: 'PUT', body: JSON.stringify(body) }),
    delete: (endpoint) => api.fetch(endpoint, { method: 'DELETE' }),
};

// Smooth scroll para links internos
document.addEventListener('DOMContentLoaded', () => {
    // Smooth scroll para anchors
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const targetId = this.getAttribute('href').slice(1);
            const target = document.getElementById(targetId);

            if (target) {
                e.preventDefault();
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Animação de fade-in para elementos com data-animate
    const animateOnScroll = () => {
        const elements = document.querySelectorAll('[data-animate]');

        elements.forEach(el => {
            const rect = el.getBoundingClientRect();
            const isVisible = rect.top < window.innerHeight - 100;

            if (isVisible) {
                el.classList.add('animate-fade-in');
                el.style.opacity = '1';
            }
        });
    };

    window.addEventListener('scroll', animateOnScroll);
    animateOnScroll(); // Initial check
});

// Scroll to top utility
function scrollToTop() {
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
}

// Console log for debug
console.log('🎬 Memora Movie - PHP Version Loaded');
