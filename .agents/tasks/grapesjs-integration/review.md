# GrapesJS Integration Review

## Scope
Files reviewed against the approved task description.

---

## 1. Migration — `2026_11_01_043325_add_gjs_columns_to_pages_table.php`

| Column | Type | Nullable | Assessment |
|---|---|---|---|
| `gjs_css` | `text` | yes | ✅ Adequate for CSS strings |
| `gjs_components` | `longText` | yes | ✅ Correct — GrapesJS project JSON can be large |
| `gjs_styles` | `text` | yes | ✅ Adequate |

`down()` drops all three columns. No issues.

---

## 2. Model — `app/Models/Page.php`

All three new columns are present in `$fillable`: `gjs_css`, `gjs_components`, `gjs_styles`. ✅  
Casts are declared as `'string'` — harmless no-ops that prevent accidental JSON auto-decoding. ✅

---

## 3. Controller — `PageBuilderController`

### `grapesjsEdit`
- Guards: existence check and `use_page_builder` guard both present and redirect with appropriate messages. ✅

### `grapesjsSave`
- Validates all four fields as `nullable|string`. ✅
- Maps `gjs_html` → `content` column (intentional round-trip with `grapesjsLoad`). ✅
- Null-coalesces to `''` for all fields, so no null write against the `update()` call. ✅
- Returns `response()->json(['success'=>true,'message'=>...])`. ✅

### `grapesjsLoad`
- Returns `$page->content` as `gjs_html` — correct inverse of the save mapping. ✅
- Always returns a JSON response (no redirect paths). ✅

---

## 4. Routes — `routes/web.php`

All three GrapesJS routes are nested inside:

```php
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    ...
    Route::middleware('check.permission:page.edit')->group(function () {
        Route::get('pages/{page}/builder/grapesjs', ...)->name('pages.grapesjs');
        Route::get('pages/{page}/builder/grapesjs/load', ...)->name('pages.grapesjs.load');
        Route::post('pages/{page}/builder/grapesjs/save', ...)->name('pages.grapesjs.save');
    });
```

Effective full names: `admin.pages.grapesjs`, `admin.pages.grapesjs.load`, `admin.pages.grapesjs.save`. ✅  
Protected by both `auth` and `check.permission:page.edit`. ✅

---

## 5. Blade view — `resources/views/admin/page-builder/grapesjs.blade.php`

- **CSRF**: `<meta name="csrf-token">` present; `savePage()` reads it via `document.querySelector` and appends as `_token` in URL-encoded body — Laravel accepts this for `application/x-www-form-urlencoded`. ✅
- **Route calls**: Uses `route('admin.pages.grapesjs.save', $page)` and `route('admin.pages.grapesjs.load', $page)` — both match registered names. ✅
- **Variable name consistency**: `monetraBlocks` is used consistently throughout (declaration and `.forEach` call). No `monetaBlocks` typo. ✅
- **GrapesJS load**: `loadContent()` checks `data.gjs_components.length > 2` before calling `loadProjectData`; falls back to `setComponents(gjs_html)`. Correct — `JSON.stringify({})` = `"{}"` (2 chars) which would not pass the threshold, but `getProjectData()` always includes page structure, making the serialized length well above 2 on any real save. ✅
- **Plugin initialization**: Guards `typeof gjsPresetWebpage !== 'undefined'` prevent errors when CDN scripts fail to load. ✅
- **No Blade/PHP syntax errors**: Template is a standalone `<!DOCTYPE html>` (not extending a layout), which is correct for a full-screen editor. ✅

---

## 6. Frontend template — `resources/views/frontend/page/grapesjs.blade.php`

- `{!! $page->gjs_css !!}` inside `<style>` — unescaped, admin-authored CSS. Intentional and acceptable since only `page.edit`-permissioned admins can write this content. ✅
- `{!! $page->content !!}` — same justification. ✅
- No visitor-supplied data is rendered unescaped. ✅

---

## 7. `PageFrontController::showWithPageBuilder`

- GrapesJS branch triggers when `isset($page->gjs_components) && !empty($page->gjs_components)`.  
- Before first GrapesJS save, `gjs_components` is `null` → `!empty(null)` = `false` → falls through to legacy builder. ✅  
- After first GrapesJS save, `getProjectData()` serializes at minimum the full page structure (always non-empty JSON) → condition is `true` → GrapesJS template is used. ✅  
- All SEO variables and breadcrumbs are passed to the GrapesJS view. ✅

---

## 8. Admin index — `resources/views/admin/pages/index.blade.php`

- "Visual Editor" button uses `route('admin.pages.grapesjs', $page)` — matches the registered route name. ✅  
- Button only rendered when `$page->use_page_builder` is true. ✅  
- Delete modal uses the existing `components.admin.delete-modal` include (unchanged from prior implementation). ✅

---

## Result

No correctness, security, or data blockers found. All requirements from the approved task are implemented correctly.
