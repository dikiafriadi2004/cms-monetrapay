/**
 * Admin Panel JavaScript
 * Handles AJAX notifications, toast messages, and interactive features
 */

document.addEventListener('DOMContentLoaded', function() {
    // ═══════════════════════════════════════════════════════════
    // AJAX Notifications Loader
    // ═══════════════════════════════════════════════════════════
    
    const notificationBadges = {
        comments: document.querySelector('[data-notification="comments"]'),
        contacts: document.querySelector('[data-notification="contacts"]'),
        bell: document.querySelector('[data-notification="bell"]'),
    };

    async function loadNotifications() {
        try {
            const response = await fetch('/api/admin/notifications', {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                }
            });

            if (!response.ok) return;

            const data = await response.json();

            // Update comments badge
            if (notificationBadges.comments) {
                updateBadge(notificationBadges.comments, data.pending_comments);
            }

            // Update contacts badge
            if (notificationBadges.contacts) {
                updateBadge(notificationBadges.contacts, data.unread_contacts);
            }

            // Update bell badge
            if (notificationBadges.bell) {
                updateBadge(notificationBadges.bell, data.total);
            }

        } catch (error) {
            console.error('Failed to load notifications:', error);
        }
    }

    function updateBadge(element, count) {
        if (count > 0) {
            element.textContent = count > 99 ? '99+' : count;
            element.classList.remove('hidden');
        } else {
            element.classList.add('hidden');
        }
    }

    // Load notifications on page load (after 500ms to not block page render)
    setTimeout(loadNotifications, 500);

    // Reload notifications every 2 minutes
    setInterval(loadNotifications, 120000);


    // ═══════════════════════════════════════════════════════════
    // Toast Notification System
    // ═══════════════════════════════════════════════════════════
    
    window.showToast = function(message, type = 'info') {
        const toast = document.createElement('div');
        toast.className = `fixed bottom-4 right-4 z-50 max-w-sm px-4 py-3 rounded-xl shadow-lg transition-all duration-300 transform translate-y-0 opacity-100`;
        
        const colors = {
            success: 'bg-green-500 text-white',
            error: 'bg-red-500 text-white',
            warning: 'bg-amber-500 text-white',
            info: 'bg-blue-500 text-white',
        };

        toast.className += ' ' + (colors[type] || colors.info);
        
        const icons = {
            success: '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>',
            error: '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>',
            warning: '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>',
            info: '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
        };

        toast.innerHTML = `
            <div class="flex items-center gap-3">
                <div class="shrink-0">${icons[type] || icons.info}</div>
                <p class="text-sm font-medium">${message}</p>
                <button onclick="this.parentElement.parentElement.remove()" class="shrink-0 ml-2 hover:opacity-75">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        `;

        document.body.appendChild(toast);

        // Auto remove after 5 seconds
        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateY(1rem)';
            setTimeout(() => toast.remove(), 300);
        }, 5000);
    };


    // ═══════════════════════════════════════════════════════════
    // Global Loading State
    // ═══════════════════════════════════════════════════════════
    
    window.showLoading = function() {
        const loader = document.createElement('div');
        loader.id = 'global-loader';
        loader.className = 'fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center';
        loader.innerHTML = `
            <div class="bg-white rounded-2xl p-6 shadow-xl">
                <div class="flex items-center gap-3">
                    <svg class="animate-spin h-5 w-5 text-primary" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="text-sm font-medium text-slate-700">Loading...</span>
                </div>
            </div>
        `;
        document.body.appendChild(loader);
    };

    window.hideLoading = function() {
        const loader = document.getElementById('global-loader');
        if (loader) loader.remove();
    };


    // ═══════════════════════════════════════════════════════════
    // Confirm Delete
    // ═══════════════════════════════════════════════════════════
    
    document.querySelectorAll('[data-confirm-delete]').forEach(button => {
        button.addEventListener('click', function(e) {
            const message = this.dataset.confirmDelete || 'Apakah Anda yakin ingin menghapus ini?';
            if (!confirm(message)) {
                e.preventDefault();
                return false;
            }
        });
    });


    // ═══════════════════════════════════════════════════════════
    // Auto-save Draft (for forms with data-autosave attribute)
    // ═══════════════════════════════════════════════════════════
    
    const autosaveForms = document.querySelectorAll('[data-autosave]');
    autosaveForms.forEach(form => {
        let timeout;
        const inputs = form.querySelectorAll('input, textarea, select');
        
        inputs.forEach(input => {
            input.addEventListener('input', function() {
                clearTimeout(timeout);
                timeout = setTimeout(() => {
                    saveDraft(form);
                }, 3000); // Save after 3 seconds of inactivity
            });
        });
    });

    async function saveDraft(form) {
        const url = form.dataset.autosave;
        if (!url) return;

        const formData = new FormData(form);
        
        try {
            const response = await fetch(url, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                }
            });

            if (response.ok) {
                showToast('Draft saved', 'success');
            }
        } catch (error) {
            console.error('Autosave failed:', error);
        }
    }


    // ═══════════════════════════════════════════════════════════
    // Image Preview Before Upload
    // ═══════════════════════════════════════════════════════════
    
    document.querySelectorAll('input[type="file"][data-preview]').forEach(input => {
        input.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (!file || !file.type.startsWith('image/')) return;

            const previewId = this.dataset.preview;
            const preview = document.getElementById(previewId);
            if (!preview) return;

            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        });
    });


    // ═══════════════════════════════════════════════════════════
    // Copy to Clipboard
    // ═══════════════════════════════════════════════════════════
    
    window.copyToClipboard = function(text) {
        navigator.clipboard.writeText(text).then(() => {
            showToast('Copied to clipboard!', 'success');
        }).catch(() => {
            showToast('Failed to copy', 'error');
        });
    };


    // ═══════════════════════════════════════════════════════════
    // Table Row Click (for clickable rows)
    // ═══════════════════════════════════════════════════════════
    
    document.querySelectorAll('tr[data-href]').forEach(row => {
        row.style.cursor = 'pointer';
        row.addEventListener('click', function(e) {
            // Don't navigate if clicking on a button or link
            if (e.target.closest('button, a, input, select, textarea')) return;
            
            window.location.href = this.dataset.href;
        });
    });

});
