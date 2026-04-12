import './bootstrap';

const csrf = () => document.head.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '';

function showToast(message, isError = false) {
    let el = document.getElementById('admin-toast');
    if (!el) {
        el = document.createElement('div');
        el.id = 'admin-toast';
        document.body.appendChild(el);
    }
    el.textContent = message;
    el.className = [
        'fixed bottom-6 right-6 z-[100] max-w-md rounded-lg px-4 py-3 text-sm shadow-lg',
        isError ? 'bg-red-800 text-white' : 'bg-slate-900 text-white',
    ].join(' ');
    clearTimeout(window.__adminToastTimer);
    window.__adminToastTimer = setTimeout(() => {
        el.textContent = '';
        el.className = '';
    }, 4500);
}

const refreshBtn = document.getElementById('btn-refresh-reports');
if (refreshBtn) {
    refreshBtn.addEventListener('click', async () => {
        refreshBtn.disabled = true;
        const label = refreshBtn.querySelector('span');
        const prev = label ? label.textContent : '';
        if (label) {
            label.textContent = 'Refreshing…';
        }
        try {
            const res = await fetch(refreshBtn.dataset.url, {
                method: 'POST',
                headers: {
                    Accept: 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrf(),
                    'X-Requested-With': 'XMLHttpRequest',
                },
                credentials: 'same-origin',
                body: JSON.stringify({}),
            });
            const data = await res.json().catch(() => ({}));
            if (!res.ok) {
                showToast(data.message || 'Refresh failed.', true);
                return;
            }
            showToast(
                `${data.message || 'Done'} (${data.inserted ?? 0} new, ${data.skipped ?? 0} skipped)`
            );
            window.location.reload();
        } catch {
            showToast('Network error while refreshing.', true);
        } finally {
            refreshBtn.disabled = false;
            if (label) {
                label.textContent = prev;
            }
        }
    });
}

const remarksModal = document.getElementById('remarks-modal');
const remarksForm = document.getElementById('remarks-form');
const remarksReportId = document.getElementById('remarks-report-id');
const remarksSaveBtn = document.getElementById('remarks-save');

function destroyRemarksEditor() {
    if (window.tinymce?.get('remarks-editor')) {
        window.tinymce.remove('#remarks-editor');
    }
}

function bumpRemarksEditorFontSize(deltaPx) {
    const ed = window.tinymce?.get('remarks-editor');
    if (!ed) {
        return;
    }
    const body = ed.getBody();
    const px = parseFloat(window.getComputedStyle(body).fontSize) || 18;
    const next = Math.min(28, Math.max(14, Math.round((px + deltaPx) * 10) / 10));
    body.style.fontSize = `${next}px`;
}

function parseRemarksFromButton(btn) {
    const raw = btn.getAttribute('data-remarks');
    if (raw === null || raw === '') {
        return '';
    }
    try {
        const v = JSON.parse(raw);
        if (v === null || v === undefined) {
            return '';
        }
        return typeof v === 'string' ? v : String(v);
    } catch {
        return '';
    }
}

function openRemarksModal(reportId, initialHtml) {
    if (!remarksModal || !remarksForm || !remarksReportId) {
        return;
    }

    remarksReportId.value = String(reportId);
    remarksForm.action = remarksForm.dataset.baseUrl.replace('__ID__', String(reportId));

    destroyRemarksEditor();

    const ta = document.getElementById('remarks-editor');
    if (ta) {
        ta.value = initialHtml || '';
    }

    remarksModal.classList.remove('hidden');
    remarksModal.classList.add('flex');
    remarksModal.setAttribute('aria-hidden', 'false');

    if (typeof window.tinymce === 'undefined') {
        showToast('Editor failed to load. Please refresh the page.', true);
        return;
    }

    window.tinymce.init({
        selector: '#remarks-editor',
        width: '100%',
        height: 520,
        menubar: false,
        plugins: 'lists link',
        toolbar:
            'undo redo | styles | bold italic underline | bullist numlist | link removeformat',
        style_formats_merge: false,
        style_formats: [
            { title: 'Paragraph', format: 'p' },
            {
                title: 'Text size — 16px',
                block: 'p',
                styles: { fontSize: '16px' },
            },
            {
                title: 'Text size — 18px (default)',
                block: 'p',
                styles: { fontSize: '18px' },
            },
            {
                title: 'Text size — 20px',
                block: 'p',
                styles: { fontSize: '20px' },
            },
            {
                title: 'Text size — 22px',
                block: 'p',
                styles: { fontSize: '22px' },
            },
            {
                title: 'Text size — 24px',
                block: 'p',
                styles: { fontSize: '24px' },
            },
        ],
        content_style:
            'body { font-family: ui-sans-serif, system-ui, -apple-system, Segoe UI, sans-serif; font-size: 18px; line-height: 1.65; color: #0f172a; padding: 10px 12px; } p { margin: 0.4em 0; }',
        branding: false,
        promotion: false,
        license_key: 'gpl',
        init_instance_callback: (ed) => {
            ed.setContent(initialHtml || '');
        },
    });
}

function closeRemarksModal() {
    destroyRemarksEditor();
    if (!remarksModal) {
        return;
    }
    remarksModal.classList.add('hidden');
    remarksModal.classList.remove('flex');
    remarksModal.setAttribute('aria-hidden', 'true');
}

document.getElementById('remarks-size-up')?.addEventListener('click', () => {
    bumpRemarksEditorFontSize(2);
});

document.getElementById('remarks-size-down')?.addEventListener('click', () => {
    bumpRemarksEditorFontSize(-2);
});

document.querySelectorAll('[data-open-remarks]').forEach((btn) => {
    btn.addEventListener('click', () => {
        openRemarksModal(btn.dataset.reportId, parseRemarksFromButton(btn));
    });
});

document.querySelectorAll('[data-close-remarks]').forEach((el) => {
    el.addEventListener('click', closeRemarksModal);
});

if (remarksModal) {
    remarksModal.addEventListener('click', (e) => {
        if (e.target === remarksModal) {
            closeRemarksModal();
        }
    });
}

if (remarksSaveBtn && remarksForm) {
    remarksSaveBtn.addEventListener('click', async () => {
        let body = '';
        const ed = window.tinymce?.get('remarks-editor');
        if (ed) {
            body = ed.getContent();
        } else {
            const ta = document.getElementById('remarks-editor');
            body = ta?.value ?? '';
        }

        remarksSaveBtn.disabled = true;
        try {
            const res = await fetch(remarksForm.action, {
                method: 'PATCH',
                headers: {
                    Accept: 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrf(),
                    'X-Requested-With': 'XMLHttpRequest',
                },
                credentials: 'same-origin',
                body: JSON.stringify({ remarks: body }),
            });
            const data = await res.json().catch(() => ({}));
            if (!res.ok) {
                const msg =
                    data.message ||
                    (data.errors && Object.values(data.errors).flat().join(' ')) ||
                    'Could not save remarks.';
                showToast(msg, true);
                return;
            }
            showToast('Remarks saved.');
            const id = remarksReportId?.value;
            if (id) {
                const rowBtn = document.querySelector(`[data-open-remarks][data-report-id="${id}"]`);
                if (rowBtn) {
                    rowBtn.setAttribute('data-remarks', JSON.stringify(body));
                }
            }
            closeRemarksModal();
        } catch {
            showToast('Network error while saving.', true);
        } finally {
            remarksSaveBtn.disabled = false;
        }
    });
}
