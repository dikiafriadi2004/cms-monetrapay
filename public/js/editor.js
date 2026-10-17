/**
 * TinyMCE Editor Configuration
 *
 * Integrates TinyMCE with the File Manager for image picking,
 * provides YouTube/Vimeo video embed validation, enforces semantic HTML output,
 * and limits content to 100,000 characters.
 */

(function () {
    'use strict';

    var MAX_CHARS = 100000;

    /**
     * Validate and convert YouTube/Vimeo URLs to embed format.
     * Returns the embed URL or null if invalid.
     */
    function parseVideoUrl(url) {
        if (!url || typeof url !== 'string') {
            return null;
        }

        url = url.trim();

        // YouTube patterns
        // youtube.com/watch?v=ID
        var match = url.match(/(?:https?:\/\/)?(?:www\.)?youtube\.com\/watch\?v=([a-zA-Z0-9_-]{11})/);
        if (match) {
            return { type: 'youtube', id: match[1], embedUrl: 'https://www.youtube.com/embed/' + match[1] };
        }

        // youtu.be/ID
        match = url.match(/(?:https?:\/\/)?youtu\.be\/([a-zA-Z0-9_-]{11})/);
        if (match) {
            return { type: 'youtube', id: match[1], embedUrl: 'https://www.youtube.com/embed/' + match[1] };
        }

        // youtube.com/embed/ID
        match = url.match(/(?:https?:\/\/)?(?:www\.)?youtube\.com\/embed\/([a-zA-Z0-9_-]{11})/);
        if (match) {
            return { type: 'youtube', id: match[1], embedUrl: 'https://www.youtube.com/embed/' + match[1] };
        }

        // Vimeo patterns
        // vimeo.com/ID
        match = url.match(/(?:https?:\/\/)?(?:www\.)?vimeo\.com\/(\d+)/);
        if (match) {
            return { type: 'vimeo', id: match[1], embedUrl: 'https://player.vimeo.com/video/' + match[1] };
        }

        // player.vimeo.com/video/ID
        match = url.match(/(?:https?:\/\/)?player\.vimeo\.com\/video\/(\d+)/);
        if (match) {
            return { type: 'vimeo', id: match[1], embedUrl: 'https://player.vimeo.com/video/' + match[1] };
        }

        return null;
    }

    /**
     * Open the File Manager picker in a modal overlay.
     */
    function openFileManagerPicker(callback) {
        // Create modal overlay
        var overlay = document.createElement('div');
        overlay.id = 'tinymce-file-picker-overlay';
        overlay.style.cssText = 'position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.6);z-index:99999;display:flex;align-items:center;justify-content:center;';

        // Create modal container
        var modal = document.createElement('div');
        modal.style.cssText = 'background:#fff;border-radius:12px;width:90%;max-width:900px;height:80vh;display:flex;flex-direction:column;overflow:hidden;box-shadow:0 25px 50px -12px rgba(0,0,0,0.25);';

        // Modal header
        var header = document.createElement('div');
        header.style.cssText = 'display:flex;align-items:center;justify-content:space-between;padding:16px 20px;border-bottom:1px solid #e2e8f0;flex-shrink:0;';
        header.innerHTML = '<span style="font-weight:600;font-size:16px;color:#1e293b;">Pilih Gambar dari File Manager</span>';

        var closeBtn = document.createElement('button');
        closeBtn.type = 'button';
        closeBtn.innerHTML = '&times;';
        closeBtn.style.cssText = 'font-size:24px;color:#64748b;background:none;border:none;cursor:pointer;padding:0 8px;line-height:1;';
        closeBtn.onclick = function () {
            cleanup();
        };
        header.appendChild(closeBtn);

        // Iframe
        var iframe = document.createElement('iframe');
        var pickerMeta = document.querySelector('meta[name="file-picker-url"]');
        iframe.src = pickerMeta ? pickerMeta.content : '/admin/files/picker';
        iframe.style.cssText = 'flex:1;border:none;width:100%;';

        modal.appendChild(header);
        modal.appendChild(iframe);
        overlay.appendChild(modal);
        document.body.appendChild(overlay);

        // Close on overlay click
        overlay.addEventListener('click', function (e) {
            if (e.target === overlay) {
                cleanup();
            }
        });

        // Close on Escape key
        function handleKeydown(e) {
            if (e.key === 'Escape') {
                cleanup();
            }
        }
        document.addEventListener('keydown', handleKeydown);

        // Listen for postMessage from picker iframe
        function handleMessage(e) {
            if (e.data && e.data.type === 'file-manager-select' && e.data.url) {
                callback(e.data.url);
                cleanup();
            }
        }
        window.addEventListener('message', handleMessage);

        function cleanup() {
            window.removeEventListener('message', handleMessage);
            document.removeEventListener('keydown', handleKeydown);
            if (overlay.parentNode) {
                overlay.parentNode.removeChild(overlay);
            }
        }
    }

    /**
     * Strip HTML tags to get plain text.
     */
    function stripHtml(html) {
        var tmp = document.createElement('div');
        tmp.innerHTML = html;
        return tmp.textContent || tmp.innerText || '';
    }

    /**
     * Get the text content length of the editor (excluding HTML tags).
     */
    function getContentLength(editor) {
        var content = editor.getContent({ format: 'text' });
        return content ? content.length : 0;
    }

    /**
     * Remove empty tags from HTML content.
     * Preserves self-closing tags like img, br, hr, iframe.
     */
    function cleanEmptyTags(html) {
        if (!html) return html;

        // Self-closing/void elements that should not be removed
        var selfClosing = ['img', 'br', 'hr', 'iframe', 'input', 'embed', 'source'];

        // Iteratively remove empty tags (tags with no content or only whitespace)
        var previous = '';
        var cleaned = html;
        var maxIterations = 10;
        var iteration = 0;

        while (cleaned !== previous && iteration < maxIterations) {
            previous = cleaned;
            cleaned = cleaned.replace(/<([a-z][a-z0-9]*)\b[^>]*>\s*<\/\1>/gi, function (match, tag) {
                if (selfClosing.indexOf(tag.toLowerCase()) !== -1) {
                    return match; // Keep self-closing elements
                }
                return ''; // Remove empty tag
            });
            iteration++;
        }

        return cleaned.trim();
    }

    /**
     * Initialize TinyMCE on all matching textareas.
     */
    function initTinyMCE() {
        // Target textareas with id="content" or class="tinymce-editor"
        var selector = 'textarea#content, textarea.tinymce-editor';

        // Check if any matching elements exist
        if (!document.querySelector(selector)) {
            return;
        }

        // Show loading indicator while TinyMCE loads
        var targetTextarea = document.querySelector(selector);
        var loadingIndicator = null;
        if (targetTextarea) {
            loadingIndicator = document.createElement('div');
            loadingIndicator.id = 'tinymce-loading';
            loadingIndicator.style.cssText = 'display:flex;align-items:center;gap:10px;padding:12px 16px;background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;font-size:13px;color:#64748b;margin-bottom:4px;';
            loadingIndicator.innerHTML = '<div style="width:16px;height:16px;border:2px solid #3D9E7A;border-top-color:transparent;border-radius:50%;animation:spin 0.8s linear infinite;"></div>Memuat editor...';
            var style = document.createElement('style');
            style.textContent = '@keyframes spin{to{transform:rotate(360deg)}}';
            document.head.appendChild(style);
            targetTextarea.parentNode.insertBefore(loadingIndicator, targetTextarea);
        }

        // Check if TinyMCE is loaded — if not, show fallback after timeout
        if (typeof tinymce === 'undefined') {
            console.warn('TinyMCE is not loaded. Editor initialization skipped.');
            if (loadingIndicator) {
                loadingIndicator.innerHTML = '⚠ Editor tidak dapat dimuat. Anda masih bisa mengedit konten HTML secara langsung di bawah ini.';
                loadingIndicator.style.background = '#fffbeb';
                loadingIndicator.style.borderColor = '#fde68a';
                loadingIndicator.style.color = '#92400e';
                if (targetTextarea) {
                    targetTextarea.style.display = 'block';
                    targetTextarea.style.visibility = 'visible';
                    targetTextarea.style.height = '400px';
                    targetTextarea.style.fontFamily = 'monospace';
                    targetTextarea.style.fontSize = '13px';
                }
            }
            return;
        }

        tinymce.init({
            selector: selector,

            // Plugins
            plugins: 'lists link image media code fullscreen',

            // Toolbar configuration
            toolbar: 'bold italic underline | blocks | bullist numlist | blockquote link | code | image media | fullscreen',

            // Block formats (headings)
            block_formats: 'Paragraph=p; Heading 1=h1; Heading 2=h2; Heading 3=h3; Heading 4=h4; Heading 5=h5; Heading 6=h6',

            // Height
            height: 500,
            min_height: 400,

            // Semantic HTML output configuration
            valid_elements: 'p,h1,h2,h3,h4,h5,h6,ul,ol,li,blockquote,pre,code,'
                + 'a[href|target|rel],img[src|alt|width|height|loading],'
                + 'strong,em,u,iframe[src|width|height|frameborder|allowfullscreen|allow|style]',

            // Force paragraphs as root block
            forced_root_block: 'p',

            // Remove trailing line breaks
            remove_trailing_brs: true,

            // Block inline styles (except on iframes for responsive video)
            invalid_styles: {
                '*': 'color background background-color font-size font-family text-align margin padding border'
            },

            // Paste configuration to avoid inline styles from clipboard
            paste_as_text: false,
            paste_remove_styles_if_webkit: true,
            paste_strip_class_attributes: 'all',

            // Image configuration - use File Manager picker
            image_advtab: false,
            image_description: true,
            automatic_uploads: false,

            // File picker callback for image insertion
            file_picker_types: 'image',
            file_picker_callback: function (callback, value, meta) {
                if (meta.filetype === 'image') {
                    openFileManagerPicker(function (url) {
                        callback(url, { alt: '' });
                    });
                }
            },

            // Media/Video embed configuration
            media_alt_source: false,
            media_poster: false,
            media_dimensions: true,
            media_url_resolver: function (data, resolve, reject) {
                var videoInfo = parseVideoUrl(data.url);

                if (videoInfo) {
                    var embedHtml = '<iframe src="' + videoInfo.embedUrl + '" '
                        + 'width="560" height="315" '
                        + 'frameborder="0" '
                        + 'allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" '
                        + 'allowfullscreen '
                        + 'style="max-width:100%;"></iframe>';
                    resolve({ html: embedHtml });
                } else {
                    reject({ msg: 'URL tidak valid. Masukkan URL YouTube atau Vimeo yang valid.' });
                }
            },

            // Editor setup callback
            setup: function (editor) {
                // Character count enforcement
                var charCountEl = null;

                editor.on('init', function () {
                    // Remove loading indicator
                    var loader = document.getElementById('tinymce-loading');
                    if (loader && loader.parentNode) {
                        loader.parentNode.removeChild(loader);
                    }
                    // Create character count indicator below editor
                    var container = editor.getContainer();
                    if (container) {
                        charCountEl = document.createElement('div');
                        charCountEl.style.cssText = 'padding:4px 12px;font-size:12px;color:#64748b;text-align:right;border-top:1px solid #e2e8f0;background:#f8fafc;';
                        container.appendChild(charCountEl);
                        updateCharCount(editor);
                    }
                });

                editor.on('keyup change input SetContent', function () {
                    updateCharCount(editor);
                });

                editor.on('BeforeSetContent', function (e) {
                    if (e.initial) return;
                    var currentLength = getContentLength(editor);
                    if (currentLength >= MAX_CHARS && e.content && e.content.length > 0) {
                        var addedTextLength = stripHtml(e.content).length;
                        if (currentLength + addedTextLength > MAX_CHARS) {
                            var remaining = MAX_CHARS - currentLength;
                            if (remaining <= 0) {
                                e.preventDefault();
                                showCharLimitWarning(editor);
                            }
                        }
                    }
                });

                editor.on('keydown', function (e) {
                    var currentLength = getContentLength(editor);
                    // Allow control keys, backspace, delete, arrows, etc.
                    var allowedKeys = [8, 46, 37, 38, 39, 40, 27, 9, 13];
                    var isCtrl = e.ctrlKey || e.metaKey;

                    if (currentLength >= MAX_CHARS && !isCtrl && allowedKeys.indexOf(e.keyCode) === -1) {
                        e.preventDefault();
                        showCharLimitWarning(editor);
                    }
                });

                function updateCharCount(ed) {
                    if (!charCountEl) return;
                    var length = getContentLength(ed);
                    var remaining = MAX_CHARS - length;
                    var color = remaining < 1000 ? '#dc2626' : remaining < 5000 ? '#d97706' : '#64748b';
                    charCountEl.style.color = color;
                    charCountEl.textContent = length.toLocaleString('id-ID') + ' / ' + MAX_CHARS.toLocaleString('id-ID') + ' karakter';
                }

                function showCharLimitWarning(ed) {
                    ed.notificationManager.open({
                        text: 'Batas maksimal 100.000 karakter telah tercapai.',
                        type: 'warning',
                        timeout: 3000
                    });
                }
            },

            // Content styling in editor
            content_style: [
                'body {',
                '  font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;',
                '  font-size: 15px;',
                '  line-height: 1.7;',
                '  color: #1e293b;',
                '  padding: 16px;',
                '  max-width: 100%;',
                '}',
                'h1 { font-size: 2em; font-weight: 700; margin: 0.67em 0; }',
                'h2 { font-size: 1.5em; font-weight: 700; margin: 0.75em 0; }',
                'h3 { font-size: 1.25em; font-weight: 600; margin: 0.75em 0; }',
                'h4 { font-size: 1.1em; font-weight: 600; margin: 0.75em 0; }',
                'h5 { font-size: 1em; font-weight: 600; margin: 0.75em 0; }',
                'h6 { font-size: 0.9em; font-weight: 600; margin: 0.75em 0; }',
                'p { margin: 0.75em 0; }',
                'blockquote { border-left: 4px solid #3b82f6; padding-left: 16px; margin: 1em 0; color: #475569; font-style: italic; }',
                'pre { background: #1e293b; color: #e2e8f0; padding: 16px; border-radius: 8px; overflow-x: auto; font-size: 14px; }',
                'code { background: #f1f5f9; padding: 2px 6px; border-radius: 4px; font-size: 0.9em; }',
                'pre code { background: none; padding: 0; }',
                'img { max-width: 100%; height: auto; border-radius: 8px; }',
                'iframe { max-width: 100%; }',
                'a { color: #2563eb; text-decoration: underline; }',
                'ul, ol { padding-left: 1.5em; margin: 0.75em 0; }',
                'li { margin: 0.25em 0; }'
            ].join('\n'),

            // Promotion/branding
            promotion: false,
            branding: false,

            // Cleanup empty tags on save
            init_instance_callback: function (editor) {
                editor.on('GetContent', function (e) {
                    if (e.content) {
                        e.content = cleanEmptyTags(e.content);
                    }
                });
            }
        });
    }

    // Export for testing purposes
    if (typeof window !== 'undefined') {
        window.CmsEditor = {
            parseVideoUrl: parseVideoUrl,
            cleanEmptyTags: cleanEmptyTags,
            MAX_CHARS: MAX_CHARS
        };
    }

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initTinyMCE);
    } else {
        initTinyMCE();
    }
})();
