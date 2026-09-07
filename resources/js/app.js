import './bootstrap';
import { initNavigation } from './modules/navigation.js';
import { initChecker } from './modules/checker.js';
import { initUpload } from './modules/upload.js';
import { initResult } from './modules/result.js';
import { initProfile } from './modules/profile.js';
import { initAuth } from './modules/auth.js';

// ─── hoaxlin.id — Global behavior (UI-v2 Phase 6) ─────────────────────────────
// Minimal global JS: shell + checker + result + account + auth. Modules no-op
// when their DOM is absent. Phase 7 pages need no JS; Phase 8 removes legacy.

document.addEventListener('DOMContentLoaded', () => {
    initNavigation();
    initChecker();
    initUpload();
    initResult();
    initProfile();
    initAuth();
});
