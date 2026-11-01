# Code Review — fix-audit-bugs
**Commit:** `8cab352` — `fix: resolve all audit-report critical bugs`  
**Branch:** `fix/cms-issues`  
**Reviewer pass:** 1 (initial)

---

## Evidence Summary

### 1a — `_makeColumns()` and `layouts` array (`edit.blade.php`)

**Checked:** `layouts` array at JS line ~565.  
Every entry carries a numeric `cols` property:
```js
{id: '100',       label: '1 Kol',  cols: 1},
{id: '50/50',     label: '50/50',  cols: 2},
{id: '30/70',     label: '30/70',  cols: 2},
{id: '70/30',     label: '70/30',  cols: 2},
{id: '33/33/33',  label: '3 Kol',  cols: 3},
{id: '25/75',     label: '25/75',  cols: 2},
```

`_makeColumns()` body:
```js
const layout = this.layouts.find(l => l.id === layoutId) || {cols: 1};
const count  = typeof layout.cols === 'number' ? layout.cols : 1;
return Array.from({length: count}, () => ({ id: uid('col'), widgets: [] }));
```
Uses `Array.from({length: count})` — not `layout.cols.map()`. ✅

`setRowLayout()` reads `newLayout.cols` as integer with the same guard. ✅

**Result: PASS**

---

### 1b — Native HTML5 drag & drop in `initSortable()` (`edit.blade.php`)

**Checked:** lines 773–820.

- Sidebar items have `draggable="true"` and `data-widget-type="{{ $type }}"`. ✅  
- `initSortable()` clears `_sidebarListeners` and calls `removeEventListener` on the old handlers before registering new ones — duplicate-handler prevention. ✅  
- `dragstart` sets `dataTransfer.setData('widget-type', el.dataset.widgetType)` and `effectAllowed = 'copy'`. ✅  
- Each `.widget-dropzone` receives `dragover` (calls `preventDefault()` + visual highlight), `dragleave` (removes highlight with `contains()` guard), and `drop` (reads `getData('widget-type')` → calls `this.addWidget(rowIdx, colIdx, type)`). ✅  
- SortableJS retained for widget reorder / cross-column move via `.widget-drag-handle`. ✅  
- `rowIdx`/`colIdx` are captured from `dataset` at `initSortable()` call time; `initSortable` is re-called from `$nextTick` after every mutation that changes row/column count, so the closures stay current. ✅

**Result: PASS**

---

### 1c — Validation rule `'components.*.content'` (`PageBuilderController.php`)

**Checked:** line 99.  
```php
'components.*.content' => 'present|array',
```
Changed from `required|array`. A `row` component serialised with no widgets in any column will have `content: { columns: [...] }` — an array key is always present for the builder payload, so `present` is the correct constraint. ✅

**Result: PASS**

---

### 1d — `use Illuminate\Http\RedirectResponse;` (`PageBuilderController.php`)

**Checked:** line 11.  
```php
use Illuminate\Http\RedirectResponse;
```
Present in use-statements block. Return type `JsonResponse|RedirectResponse` is correct for `save()`. ✅

**Result: PASS**

---

### 2a — `preview.blade.php` exists and renders preview

**Checked:** file `resources/views/admin/page-builder/preview.blade.php`.  
- Extends `admin.layouts.app`. ✅  
- Top bar has back-to-editor link and "Buka di Tab Baru" button. ✅  
- Responsive iframe pointing to `url('/' . $page->slug)` fills remaining viewport. ✅  
- Controller `preview()` method loads `visibleComponents` and returns the view. ✅

**Result: PASS**

---

### 3a — Migration file for flat → row conversion

**Checked:** `database/migrations/2026_11_01_110723_convert_flat_page_components_to_row_format.php`.  
- `up()`: fetches all non-row components, groups by `page_id`, wraps into `row` component with `columns[0].widgets`, inserts the row record, then deletes original flat records. ✅  
- `down()`: reverses by unpacking widgets back to flat component rows, then deletes the wrapping row record. ✅  
- Commit message records `migration ran: DONE (70ms)`. ✅

**Result: PASS**

---

### 4a — Contact page with slug `contact` deleted from DB

**Evidence:** Commit message states "Deleted duplicate 'contact' page slug via artisan tinker (kept 'kontak')".  
This is a DB-only operation (no file diff possible). No file in the codebase hardcodes a seeder or factory that would re-create a `contact` slug on next migrate:fresh (test fixture in `SeoServiceTest.php` uses a factory and is test-only). Accepted on coder's recorded tinker evidence. ✅

**Result: PASS (DB-only operation, no file diff expected)**

---

### 4b — No double MenuService calls in `frontend/layouts/app.blade.php`

**Checked:** Full file content.  
- Zero occurrences of `MenuService` or `getMenuByLocation()`. ✅  
- `$primaryMenu = $headerMenu ?? []` and `$footerMenuItems = $footerMenu ?? []` — both derive from FrontendComposer-injected variables. ✅  
- `$socialMenuItems = []` — empty array, no MenuService call. ✅  
- CTA uses `Setting::get(...)` which is acceptable (Settings facade, not MenuService). ✅

**Result: PASS**

---

## Verdict

All 8 required changes (1a, 1b, 1c, 1d, 2a, 3a, 4a, 4b) are correctly implemented with no correctness, security, or data failures introduced. No blockers found.
