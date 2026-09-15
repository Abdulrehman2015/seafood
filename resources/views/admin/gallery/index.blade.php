@extends('layouts.admin')
@section('title', 'Media Gallery — Admin')

@section('content')


<div class="admin-topbar" style="margin-bottom:var(--space-4)">
    <div>
        <h1 class="admin-page-title">Media Gallery</h1>
        <p class="text-sm text-muted">All uploaded media is automatically converted to optimized <strong>.webp</strong> format preserving 100% native resolution.</p>
    </div>
    <div style="display:flex;gap:var(--space-3);align-items:center">
        <div class="badge" style="background:#ccfbf1;color:#0f766e;font-weight:700;padding:6px 12px;font-size:0.8rem">
            ✓ 100% WebP Engine Active
        </div>
        <div style="font-size:0.85rem;color:var(--gray-600);font-weight:600">
            {{ $totalCount }} Assets · {{ $totalSizeFormatted }}
        </div>
    </div>
</div>

<!-- Main File Manager Container matching screenshot layout -->
<div class="card" style="background:white;border-radius:12px;border:1px solid #e5e7eb;box-shadow:0 4px 6px -1px rgba(0,0,0,0.05);overflow:hidden;padding:20px 24px">

    <!-- 1. Dropzone Block at Top matching screenshot -->
    <div style="margin-bottom:16px">
        <div id="mainDropArea" 
             ondragover="event.preventDefault(); this.style.borderColor='#2563eb'; this.style.background='#eff6ff';" 
             ondragleave="this.style.borderColor='#cbd5e1'; this.style.background='#ffffff';"
             ondrop="event.preventDefault(); this.style.borderColor='#cbd5e1'; this.style.background='#ffffff'; handleDirectUpload(event.dataTransfer.files);"
             onclick="document.getElementById('nativeFileInput').click()"
             style="border:1.5px dashed #cbd5e1;border-radius:8px;background:#ffffff;padding:32px 16px;text-align:center;cursor:pointer;transition:all 0.15s ease">
            
            <div style="font-weight:700;font-size:1.05rem;color:#1e293b;margin-bottom:4px">
                Drop images here or click to upload
            </div>
            <div style="font-size:0.85rem;color:#64748b">
                (Allowed: JPG, PNG, GIF, WEBP, SVG, BMP, AVIF · Max 5 MB)
            </div>
            <div id="uploadStatusMsg" style="display:none;margin-top:10px;font-weight:700;color:#0d9488;font-size:0.85rem"></div>

            <input type="file" id="nativeFileInput" multiple accept="image/*,application/pdf,video/mp4" style="display:none" onchange="handleDirectUpload(this.files)">
        </div>
    </div>

    <!-- 2. Path & Folder Creator Toolbar -->
    <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;margin-bottom:18px;padding-bottom:12px;border-bottom:1px solid #f1f5f9">
        
        <!-- Current Path Breadcrumbs & Active Folder Actions -->
        <div style="display:flex;align-items:center;gap:14px;flex-wrap:wrap">
            <div style="display:flex;align-items:center;font-size:0.9rem;color:#111827;font-weight:700">
                <span>Current Path:&nbsp;</span>
                <span id="galleryPathDisplay">
                    @if($currentFolder === 'all' || $currentFolder === 'root' || $currentFolder === 'gallery')
                        <a href="{{ route('admin.gallery.index') }}" style="color:#2563eb;text-decoration:none;font-weight:600">/uploads/files</a>
                    @else
                        <a href="{{ route('admin.gallery.index') }}" style="color:#2563eb;text-decoration:none">/uploads/files</a> / <strong style="color:#111827">{{ $currentFolder }}</strong>
                    @endif
                </span>
            </div>

            @php
                $isRootView = ($currentFolder === 'all' || $currentFolder === 'root' || $currentFolder === 'gallery');
                $activeFolderObj = $isRootView ? null : $folders->firstWhere('slug', $currentFolder);
            @endphp
            @if(!$isRootView && $activeFolderObj)
            <div style="display:flex;gap:6px;align-items:center">
                @if(!$activeFolderObj->is_system)
                <button type="button" class="btn btn-secondary btn-sm" onclick="showFolderRenameModal({{ json_encode($activeFolderObj) }})" style="padding:4px 10px;font-size:0.8rem" title="Rename this folder">
                    ✏️ Rename
                </button>
                <button type="button" class="btn btn-secondary btn-sm" onclick="deleteFolder({{ json_encode($activeFolderObj) }})" style="padding:4px 10px;font-size:0.8rem;color:#ef4444" title="Delete this folder">
                    🗑 Delete
                </button>
                @endif
                <a href="{{ route('admin.gallery.folders.download', ['folder' => $activeFolderObj->id, 'filename' => \Illuminate\Support\Str::slug($activeFolderObj->name) . '.zip']) }}" 
                   download="{{ \Illuminate\Support\Str::slug($activeFolderObj->name) }}.zip"
                   class="btn btn-secondary btn-sm" 
                   style="padding:4px 10px;font-size:0.8rem;text-decoration:none;display:inline-flex;align-items:center;gap:4px" 
                   title="Download ZIP of all files in this folder">
                    📦 Download ZIP ({{ $activeFolderObj->media_count }})
                </a>
                <button type="button" class="btn btn-secondary btn-sm" onclick="showFolderMoveModal({{ json_encode($activeFolderObj) }})" style="padding:4px 10px;font-size:0.8rem" title="Move all files from this folder">
                    ↗️ Move Files
                </button>
            </div>
            @elseif($isRootView)
            <div style="display:flex;gap:6px;align-items:center">
                <a href="{{ route('admin.gallery.folders.download', ['folder' => 'all', 'filename' => 'mst-all-media.zip']) }}" 
                   download="mst-all-media.zip"
                   class="btn btn-secondary btn-sm" 
                   style="padding:4px 10px;font-size:0.8rem;text-decoration:none;display:inline-flex;align-items:center;gap:4px" 
                   title="Download ZIP of all gallery assets">
                    📦 Download All Assets (ZIP)
                </a>
            </div>
            @endif
        </div>

        <!-- Inline Folder Creator Form -->
        <form action="{{ route('admin.gallery.folders.create') }}" method="POST" style="display:flex;align-items:center;margin:0">
            @csrf
            <input type="text" name="name" placeholder="Enter folder name" required
                   style="height:36px;font-size:0.85rem;border:1px solid #d1d5db;border-right:none;border-radius:4px 0 0 4px;padding:0 12px;width:180px;outline:none">
            <button type="submit" 
                    style="height:36px;background:#ffffff;border:1px solid #d1d5db;border-radius:0 4px 4px 0;padding:0 14px;font-size:0.85rem;font-weight:500;color:#374151;cursor:pointer">
                Create Folder
            </button>
        </form>

    </div>

    <!-- 3. Grid Container (Folders & Images) -->
    <div class="gallery-cards-grid" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(120px,1fr));gap:16px;min-height:300px;align-content:start">
        
        <!-- FOLDERS -->
        @if($currentFolder === 'all' || $currentFolder === 'root' || $currentFolder === 'gallery')
            @foreach($folders as $f)
            <div class="gallery-folder-card" 
                 data-folder-id="{{ $f->id }}"
                 data-folder-slug="{{ $f->slug }}"
                 data-folder-info="{{ json_encode($f) }}"
                 onclick="window.location='{{ route('admin.gallery.index', ['folder' => $f->slug]) }}'"
                 oncontextmenu="event.preventDefault(); event.stopPropagation(); openFolderContextMenu(event, {{ json_encode($f) }});"
                 style="aspect-ratio:1;background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;position:relative;display:flex;flex-direction:column;align-items:center;justify-content:center;cursor:pointer;transition:all 0.15s;padding:8px"
                 title="Open folder: {{ $f->name }} ({{ $f->media_count }} items)">
                
                <!-- Action Buttons Container (top right) -->
                <div style="position:absolute;top:6px;right:6px;display:flex;gap:3px;z-index:3">
                    <!-- Download ZIP Button for Folder -->
                    <a href="{{ route('admin.gallery.folders.download', ['folder' => $f->id, 'filename' => \Illuminate\Support\Str::slug($f->name) . '.zip']) }}" 
                       download="{{ \Illuminate\Support\Str::slug($f->name) }}.zip"
                       onclick="event.stopPropagation();" 
                       title="Download ZIP ({{ $f->media_count }} items)"
                       class="gallery-card-options-btn"
                       style="background:#ffffff;color:#334155;border:1px solid #cbd5e1;border-radius:4px;width:22px;height:22px;display:flex;align-items:center;justify-content:center;cursor:pointer;opacity:0.85;transition:all 0.15s;text-decoration:none">
                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                    </a>
                    @if(!$f->is_system)
                    <!-- Rename Button for Folder -->
                    <button type="button" onclick="event.stopPropagation(); showFolderRenameModal({{ json_encode($f) }});" title="Rename Folder"
                            class="gallery-card-options-btn"
                            style="background:#ffffff;color:#334155;border:1px solid #cbd5e1;border-radius:4px;width:22px;height:22px;display:flex;align-items:center;justify-content:center;cursor:pointer;opacity:0.85;transition:all 0.15s">
                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                    </button>
                    <!-- Red Delete Button for Folder -->
                    <button type="button" onclick="event.stopPropagation(); deleteFolder({{ json_encode($f) }});" title="Delete Folder"
                            class="gallery-card-options-btn"
                            style="background:#ef4444;color:white;border:none;border-radius:4px;width:22px;height:22px;display:flex;align-items:center;justify-content:center;cursor:pointer;opacity:0.85;transition:all 0.15s">
                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                    </button>
                    @endif
                </div>

                <!-- 3-Dots Trigger Button for Folder -->
                <button type="button" onclick="event.stopPropagation(); openFolderContextMenu(event, {{ json_encode($f) }}, this)"
                        title="Folder options"
                        class="gallery-card-options-btn"
                        style="position:absolute;top:6px;left:6px;background:rgba(255,255,255,0.92);color:#334155;border:1px solid #cbd5e1;border-radius:4px;width:22px;height:22px;display:flex;align-items:center;justify-content:center;cursor:pointer;font-size:12px;font-weight:700;z-index:3;opacity:0.85;transition:opacity 0.15s">
                    ⋮
                </button>

                <!-- Yellow Folder Graphic -->
                <div style="display:flex;align-items:center;justify-content:center;margin-bottom:4px">
                    <svg width="52" height="42" viewBox="0 0 24 24" fill="#fbbf24" stroke="#f59e0b" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path>
                    </svg>
                </div>

                <!-- Folder Name -->
                <div style="font-size:0.75rem;color:#475569;font-weight:600;text-align:center;width:100%;overflow:hidden;text-overflow:ellipsis;white-space:nowrap" title="{{ $f->name }}">
                    {{ $f->name }}
                </div>
            </div>
            @endforeach
        @endif

        <!-- FILES / IMAGES -->
        @forelse($mediaItems as $media)
        <div class="gallery-file-card" 
             data-media-id="{{ $media->id }}"
             data-media-info="{{ json_encode($media) }}"
             style="aspect-ratio:1;background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;position:relative;overflow:hidden;cursor:pointer;transition:all 0.15s"
             oncontextmenu="event.preventDefault(); event.stopPropagation(); openGalleryContextMenu(event, {{ json_encode($media) }});"
             onclick="openPreviewModal({{ json_encode($media) }})"
             title="{{ $media->original_name }} ({{ $media->size_formatted }})">
            
            @php
                $cleanBase = preg_replace('/\.(jpeg|jpg|png|webp|gif|svg|bmp|avif)$/i', '', pathinfo($media->original_name, PATHINFO_FILENAME));
                $slug = \Illuminate\Support\Str::slug($cleanBase) ?: 'image';
                $origExt = strtolower(pathinfo($media->original_name, PATHINFO_EXTENSION) ?: 'webp');
                $imgExt = in_array($origExt, ['jpg', 'jpeg', 'jpe']) ? 'jpg' : ($origExt === 'png' ? 'png' : 'webp');
                $downloadImgName = $slug . '.' . $imgExt;
            @endphp
            <!-- Quick Download Button for Image -->
            <a href="{{ route('admin.gallery.download', ['media' => $media->id, 'filename' => $downloadImgName]) }}" 
               download="{{ $downloadImgName }}"
               onclick="event.stopPropagation();" 
               title="Download Image ({{ $downloadImgName }})"
               class="gallery-card-options-btn"
               style="position:absolute;top:6px;right:32px;margin:0;z-index:3;background:rgba(255,255,255,0.92);color:#334155;border:1px solid #cbd5e1;border-radius:4px;width:22px;height:22px;display:flex;align-items:center;justify-content:center;cursor:pointer;opacity:0.85;transition:all 0.15s;text-decoration:none">
                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
            </a>

            <!-- Red Delete Button for Image -->
            <button type="button" 
                    onclick="event.stopPropagation(); deleteMedia({{ json_encode($media) }});" 
                    title="Delete Image"
                    style="position:absolute;top:6px;right:6px;margin:0;z-index:3;background:#ef4444;color:white;border:none;border-radius:4px;width:22px;height:22px;display:flex;align-items:center;justify-content:center;cursor:pointer">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </button>

            <!-- 3-Dots Trigger Button -->
            <button type="button" onclick="event.stopPropagation(); openGalleryContextMenu(event, {{ json_encode($media) }}, this)"
                    title="More options"
                    class="gallery-card-options-btn"
                    style="position:absolute;top:6px;left:6px;background:rgba(255,255,255,0.92);color:#334155;border:1px solid #cbd5e1;border-radius:4px;width:22px;height:22px;display:flex;align-items:center;justify-content:center;cursor:pointer;font-size:12px;font-weight:700;z-index:2;opacity:0.85;transition:opacity 0.15s">
                ⋮
            </button>

            <!-- WEBP badge -->
            <span style="position:absolute;bottom:6px;left:6px;background:rgba(15,118,110,0.88);color:white;font-size:0.6rem;font-weight:700;padding:2px 5px;border-radius:4px;z-index:1">
                WEBP
            </span>

            <!-- Image Thumbnail -->
            <img id="thumb-img-{{ $media->id }}" src="{{ $media->url }}" alt="{{ $media->original_name }}" loading="lazy" style="width:100%;height:100%;object-fit:cover;display:block">
        </div>
        @empty
            @if($currentFolder !== 'all' && $currentFolder !== 'root' && $currentFolder !== 'gallery')
            <div style="grid-column:1/-1;text-align:center;padding:50px 20px;color:#9ca3af">
                <div style="font-size:2.5rem;margin-bottom:8px">📁</div>
                <div style="font-weight:700;color:#374151;font-size:1rem;margin-bottom:4px">This folder is empty</div>
                <p class="text-sm text-muted">Drop files above or click to upload into [{{ $currentFolder }}].</p>
                <a href="{{ route('admin.gallery.index') }}" class="btn btn-secondary btn-sm" style="margin-top:10px">← Back to All Folders</a>
            </div>
            @endif
        @endforelse

    </div>

    @if($mediaItems->hasPages())
    <div class="pagination" style="margin-top:20px">{{ $mediaItems->links() }}</div>
    @endif

</div>

<!-- Floating Context Menu matching exact screenshot -->
<div id="galleryContextMenu" style="display:none;position:fixed;z-index:1000002;background:#ffffff;border:1px solid #e2e8f0;border-radius:8px;box-shadow:0 10px 25px -5px rgba(0,0,0,0.18),0 8px 10px -6px rgba(0,0,0,0.08);min-width:165px;padding:6px 0;font-family:inherit">
    <div class="gallery-context-item" onclick="triggerContextAction('preview')" style="padding:8px 18px;font-size:0.85rem;color:#334155;font-weight:500;cursor:pointer;transition:background 0.1s">
        Preview
    </div>
    <div class="gallery-context-item" onclick="triggerContextAction('rename')" style="padding:8px 18px;font-size:0.85rem;color:#334155;font-weight:500;cursor:pointer;transition:background 0.1s">
        Rename
    </div>
    <div class="gallery-context-item" onclick="triggerContextAction('crop')" style="padding:8px 18px;font-size:0.85rem;color:#334155;font-weight:500;cursor:pointer;transition:background 0.1s">
        Crop
    </div>
    <div class="gallery-context-item" onclick="triggerContextAction('move')" style="padding:8px 18px;font-size:0.85rem;color:#334155;font-weight:500;cursor:pointer;transition:background 0.1s">
        Move
    </div>
    <div class="gallery-context-item" onclick="triggerContextAction('download')" style="padding:8px 18px;font-size:0.85rem;color:#334155;font-weight:500;cursor:pointer;transition:background 0.1s">
        Download
    </div>
    <div class="gallery-context-item" onclick="triggerContextAction('copylink')" style="padding:8px 18px;font-size:0.85rem;color:#334155;font-weight:500;cursor:pointer;transition:background 0.1s">
        Copy Link
    </div>
    <div style="height:1px;background:#f1f5f9;margin:4px 0"></div>
    <div class="gallery-context-item" onclick="triggerContextAction('delete')" style="padding:8px 18px;font-size:0.85rem;color:#ef4444;font-weight:500;cursor:pointer;transition:background 0.1s">
        Delete
    </div>
</div>

<!-- Floating Context Menu for Folders -->
<div id="folderContextMenu" style="display:none;position:fixed;z-index:1000002;background:#ffffff;border:1px solid #e2e8f0;border-radius:8px;box-shadow:0 10px 25px -5px rgba(0,0,0,0.18),0 8px 10px -6px rgba(0,0,0,0.08);min-width:165px;padding:6px 0;font-family:inherit">
    <div class="gallery-context-item" onclick="triggerFolderAction('open')" style="padding:8px 18px;font-size:0.85rem;color:#334155;font-weight:500;cursor:pointer;transition:background 0.1s">
        Open
    </div>
    <div id="folderCtxRename" class="gallery-context-item" onclick="triggerFolderAction('rename')" style="padding:8px 18px;font-size:0.85rem;color:#334155;font-weight:500;cursor:pointer;transition:background 0.1s">
        Rename
    </div>
    <div class="gallery-context-item" onclick="triggerFolderAction('move')" style="padding:8px 18px;font-size:0.85rem;color:#334155;font-weight:500;cursor:pointer;transition:background 0.1s">
        Move Files
    </div>
    <div class="gallery-context-item" onclick="triggerFolderAction('download')" style="padding:8px 18px;font-size:0.85rem;color:#334155;font-weight:500;cursor:pointer;transition:background 0.1s">
        Download (ZIP)
    </div>
    <div class="gallery-context-item" onclick="triggerFolderAction('copylink')" style="padding:8px 18px;font-size:0.85rem;color:#334155;font-weight:500;cursor:pointer;transition:background 0.1s">
        Copy Link
    </div>
    <div id="folderCtxDivider" style="height:1px;background:#f1f5f9;margin:4px 0"></div>
    <div id="folderCtxDelete" class="gallery-context-item" onclick="triggerFolderAction('delete')" style="padding:8px 18px;font-size:0.85rem;color:#ef4444;font-weight:500;cursor:pointer;transition:background 0.1s">
        Delete
    </div>
</div>

<!-- Full-Size Image Inspection Modal -->
<div id="previewModal" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.85);z-index:99999;align-items:center;justify-content:center;padding:20px" onclick="if(event.target===this) closePreviewModal()">
    <div style="background:white;border-radius:12px;max-width:900px;width:100%;overflow:hidden;box-shadow:0 25px 50px -12px rgba(0,0,0,0.4)">
        <div style="padding:14px 20px;display:flex;justify-content:space-between;align-items:center;border-bottom:1px solid #e5e7eb;background:#f9fafb">
            <div>
                <div id="modalImgTitle" style="font-weight:700;color:#111827;font-size:1rem"></div>
                <div id="modalImgMeta" class="text-xs text-muted"></div>
            </div>
            <div style="display:flex;gap:8px;align-items:center;flex-wrap:wrap">
                <a id="modalDownloadBtn" href="#" download="" class="btn btn-primary btn-sm" style="display:flex;align-items:center;gap:4px;text-decoration:none" title="Download image file">
                    ⬇ Download
                </a>
                <button type="button" class="btn btn-secondary btn-sm" onclick="triggerModalCopyUrl()">📋 Copy URL</button>
                <button type="button" class="btn btn-secondary btn-sm" onclick="openMoveFromModal()">📁 Move</button>
                <button type="button" class="btn btn-secondary btn-sm" onclick="openCopyFromModal()">📋 Duplicate</button>
                <button type="button" class="btn btn-sm" style="background:#ef4444;color:white;border:none;border-radius:6px;padding:5px 12px;font-size:0.85rem;cursor:pointer" onclick="deleteFromPreviewModal()">🗑 Delete</button>
                <button type="button" class="btn btn-secondary btn-sm" onclick="closePreviewModal()">✕ Close</button>
            </div>
        </div>

        <div style="max-height:72vh;overflow:auto;background:#0b1329;display:flex;align-items:center;justify-content:center;padding:14px">
            <img id="modalImgSrc" src="" alt="Preview" style="max-width:100%;max-height:68vh;object-fit:contain;border-radius:6px">
        </div>
    </div>
</div>

<!-- Rename Modal -->
<div id="galleryRenameModal" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.6);z-index:1000005;align-items:center;justify-content:center;padding:16px" onclick="if(event.target===this) closeRenameModal()">
    <div style="background:white;border-radius:12px;max-width:440px;width:100%;box-shadow:0 20px 40px rgba(0,0,0,0.25);overflow:hidden">
        <div style="padding:14px 20px;border-bottom:1px solid #e5e7eb;display:flex;justify-content:space-between;align-items:center;background:#f8fafc">
            <div style="font-weight:700;font-size:0.95rem;color:#111827">Rename Image</div>
            <button type="button" onclick="closeRenameModal()" style="background:transparent;border:none;font-size:1.2rem;color:#94a3b8;cursor:pointer">✕</button>
        </div>
        <div style="padding:20px">
            <label style="display:block;font-size:0.85rem;font-weight:600;color:#374151;margin-bottom:6px">New Name</label>
            <input type="text" id="renameInput" style="width:100%;height:38px;padding:0 12px;border:1px solid #cbd5e1;border-radius:6px;font-size:0.9rem;outline:none" onkeydown="if(event.key==='Enter'){ event.preventDefault(); submitRename(); }">
        </div>
        <div style="padding:12px 20px;border-top:1px solid #e5e7eb;display:flex;justify-content:flex-end;gap:8px;background:#f8fafc">
            <button type="button" onclick="closeRenameModal()" style="background:#ffffff;border:1px solid #d1d5db;border-radius:6px;padding:7px 16px;font-size:0.85rem;color:#374151;cursor:pointer">Cancel</button>
            <button type="button" onclick="submitRename()" style="background:#2563eb;color:white;border:none;border-radius:6px;padding:7px 18px;font-size:0.85rem;font-weight:600;cursor:pointer">Save Changes</button>
        </div>
    </div>
</div>

<!-- Interactive Crop Modal -->
<div id="galleryCropModal" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.75);z-index:1000005;align-items:center;justify-content:center;padding:16px" onclick="if(event.target===this) closeCropModal()">
    <div style="background:white;border-radius:12px;max-width:820px;width:100%;box-shadow:0 25px 50px rgba(0,0,0,0.35);overflow:hidden;display:flex;flex-direction:column;max-height:92vh">
        <div style="padding:14px 20px;border-bottom:1px solid #e5e7eb;display:flex;justify-content:space-between;align-items:center;background:#f8fafc">
            <div>
                <div style="font-weight:700;font-size:1rem;color:#111827">Crop Image</div>
                <div id="cropResolutionBadge" style="font-size:0.75rem;color:#64748b;margin-top:2px">Drag handles to adjust crop area</div>
            </div>
            <div style="display:flex;gap:6px;align-items:center">
                <button type="button" onclick="setCropAspect('free')" class="crop-aspect-btn active" style="padding:4px 10px;font-size:0.75rem;border-radius:4px;border:1px solid #cbd5e1;background:#2563eb;color:white;cursor:pointer">Free</button>
                <button type="button" onclick="setCropAspect('1:1')" class="crop-aspect-btn" style="padding:4px 10px;font-size:0.75rem;border-radius:4px;border:1px solid #cbd5e1;background:white;color:#334155;cursor:pointer">1:1</button>
                <button type="button" onclick="setCropAspect('4:3')" class="crop-aspect-btn" style="padding:4px 10px;font-size:0.75rem;border-radius:4px;border:1px solid #cbd5e1;background:white;color:#334155;cursor:pointer">4:3</button>
                <button type="button" onclick="setCropAspect('16:9')" class="crop-aspect-btn" style="padding:4px 10px;font-size:0.75rem;border-radius:4px;border:1px solid #cbd5e1;background:white;color:#334155;cursor:pointer">16:9</button>
                <button type="button" onclick="closeCropModal()" style="background:transparent;border:none;font-size:1.3rem;color:#94a3b8;cursor:pointer;margin-left:8px">✕</button>
            </div>
        </div>
        
        <!-- Canvas Viewport -->
        <div style="flex:1;background:#0f172a;display:flex;align-items:center;justify-content:center;overflow:hidden;position:relative;padding:16px;min-height:360px">
            <div id="cropStage" style="position:relative;display:inline-block;max-width:100%;max-height:56vh;box-shadow:0 10px 25px rgba(0,0,0,0.5)">
                <img id="cropTargetImage" src="" alt="To Crop" style="display:block;max-width:100%;max-height:56vh;user-select:none;pointer-events:none">
                
                <!-- Darkened overlay with cutout -->
                <div id="cropOverlayBox" style="position:absolute;top:10%;left:10%;width:80%;height:80%;border:2px dashed #38bdf8;box-shadow:0 0 0 9999px rgba(0,0,0,0.55);cursor:move">
                    <div class="crop-handle" data-dir="nw" style="position:absolute;top:-5px;left:-5px;width:12px;height:12px;background:#38bdf8;border:2px solid white;border-radius:2px;cursor:nwse-resize"></div>
                    <div class="crop-handle" data-dir="ne" style="position:absolute;top:-5px;right:-5px;width:12px;height:12px;background:#38bdf8;border:2px solid white;border-radius:2px;cursor:nesw-resize"></div>
                    <div class="crop-handle" data-dir="se" style="position:absolute;bottom:-5px;right:-5px;width:12px;height:12px;background:#38bdf8;border:2px solid white;border-radius:2px;cursor:nwse-resize"></div>
                    <div class="crop-handle" data-dir="sw" style="position:absolute;bottom:-5px;left:-5px;width:12px;height:12px;background:#38bdf8;border:2px solid white;border-radius:2px;cursor:nesw-resize"></div>
                </div>
            </div>
        </div>

        <div style="padding:12px 20px;border-top:1px solid #e5e7eb;display:flex;justify-content:space-between;align-items:center;background:#f8fafc">
            <span id="cropCoordsInfo" style="font-size:0.8rem;color:#64748b;font-weight:500">Output: WebP · Resolution preserved</span>
            <div style="display:flex;gap:8px">
                <button type="button" onclick="closeCropModal()" style="background:#ffffff;border:1px solid #d1d5db;border-radius:6px;padding:8px 18px;font-size:0.85rem;color:#374151;cursor:pointer">Cancel</button>
                <button type="button" id="saveCropBtn" onclick="submitCrop()" style="background:#2563eb;color:white;border:none;border-radius:6px;padding:8px 20px;font-size:0.85rem;font-weight:600;cursor:pointer">Apply & Save Crop</button>
            </div>
        </div>
    </div>
</div>

<!-- Move Modal -->
<div id="moveMediaModal" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.6);z-index:999999;align-items:center;justify-content:center;padding:16px">
    <div style="background:white;border-radius:12px;max-width:440px;width:100%;box-shadow:0 20px 40px rgba(0,0,0,0.25);overflow:hidden">
        <form id="moveMediaForm" method="POST">
            @csrf
            <div style="padding:14px 18px;border-bottom:1px solid #e5e7eb;display:flex;justify-content:space-between;align-items:center;background:#f9fafb">
                <div style="font-weight:700;font-size:0.95rem;color:#111827">📁 Move to Folder</div>
                <button type="button" class="btn btn-secondary btn-sm" onclick="document.getElementById('moveMediaModal').style.display='none'">✕</button>
            </div>
            <div style="padding:18px">
                <label class="form-label">Select Destination Folder <span class="required">*</span></label>
                <select name="target_folder" class="form-control" required>
                    @foreach($folders as $f)
                        <option value="{{ $f->slug }}">📁 {{ $f->name }}</option>
                    @endforeach
                </select>
            </div>
            <div style="padding:12px 18px;border-top:1px solid #e5e7eb;display:flex;justify-content:flex-end;gap:8px;background:#f9fafb">
                <button type="button" class="btn btn-secondary btn-sm" onclick="document.getElementById('moveMediaModal').style.display='none'">Cancel</button>
                <button type="submit" class="btn btn-primary btn-sm">Move File</button>
            </div>
        </form>
    </div>
</div>

<!-- Copy Modal -->
<div id="copyMediaModal" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.6);z-index:999999;align-items:center;justify-content:center;padding:16px">
    <div style="background:white;border-radius:12px;max-width:440px;width:100%;box-shadow:0 20px 40px rgba(0,0,0,0.25);overflow:hidden">
        <form id="copyMediaForm" method="POST">
            @csrf
            <div style="padding:14px 18px;border-bottom:1px solid #e5e7eb;display:flex;justify-content:space-between;align-items:center;background:#f9fafb">
                <div style="font-weight:700;font-size:0.95rem;color:#111827">📋 Duplicate / Copy File</div>
                <button type="button" class="btn btn-secondary btn-sm" onclick="document.getElementById('copyMediaModal').style.display='none'">✕</button>
            </div>
            <div style="padding:18px">
                <label class="form-label">Destination Folder</label>
                <select name="target_folder" class="form-control">
                    @foreach($folders as $f)
                        <option value="{{ $f->slug }}">📁 {{ $f->name }}</option>
                    @endforeach
                </select>
            </div>
            <div style="padding:12px 18px;border-top:1px solid #e5e7eb;display:flex;justify-content:flex-end;gap:8px;background:#f9fafb">
                <button type="button" class="btn btn-secondary btn-sm" onclick="document.getElementById('copyMediaModal').style.display='none'">Cancel</button>
                <button type="submit" class="btn btn-primary btn-sm">Duplicate File</button>
            </div>
        </form>
    </div>
</div>

<!-- Toast Notification -->
<div id="galleryPageToast" style="display:none;position:fixed;bottom:24px;right:24px;background:#1e293b;color:white;padding:10px 18px;border-radius:8px;font-size:0.85rem;font-weight:500;box-shadow:0 10px 25px rgba(0,0,0,0.25);z-index:1000010;align-items:center;gap:8px;transition:opacity 0.2s"></div>

<!-- Delete Confirmation Modal -->
<div id="galleryDeleteModal" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.6);z-index:1000006;align-items:center;justify-content:center;padding:16px" onclick="if(event.target===this) closeDeleteModal()">
    <div style="background:white;border-radius:12px;max-width:420px;width:100%;box-shadow:0 20px 40px rgba(0,0,0,0.25);overflow:hidden">
        <div style="padding:16px 20px;border-bottom:1px solid #e5e7eb;display:flex;justify-content:space-between;align-items:center;background:#fef2f2">
            <div style="font-weight:700;font-size:1rem;color:#b91c1c;display:flex;align-items:center;gap:8px">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                Delete Image
            </div>
            <button type="button" onclick="closeDeleteModal()" style="background:transparent;border:none;font-size:1.2rem;color:#94a3b8;cursor:pointer">✕</button>
        </div>
        <div style="padding:20px;color:#374151;font-size:0.9rem;line-height:1.5">
            Are you sure you want to permanently delete <strong id="deleteModalItemName" style="color:#111827"></strong>? This action cannot be undone.
        </div>
        <div style="padding:12px 20px;border-top:1px solid #e5e7eb;display:flex;justify-content:flex-end;gap:8px;background:#f8fafc">
            <button type="button" onclick="closeDeleteModal()" style="background:#ffffff;border:1px solid #d1d5db;border-radius:6px;padding:8px 16px;font-size:0.85rem;color:#374151;cursor:pointer">Cancel</button>
            <button type="button" id="confirmDeleteBtn" onclick="executeDeleteMedia()" style="background:#ef4444;color:white;border:none;border-radius:6px;padding:8px 20px;font-size:0.85rem;font-weight:600;cursor:pointer">Delete</button>
        </div>
    </div>
</div>

<!-- Rename Folder Modal -->
<div id="folderRenameModal" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.6);z-index:1000005;align-items:center;justify-content:center;padding:16px" onclick="if(event.target===this) closeFolderRenameModal()">
    <div style="background:white;border-radius:12px;max-width:440px;width:100%;box-shadow:0 20px 40px rgba(0,0,0,0.25);overflow:hidden">
        <div style="padding:14px 20px;border-bottom:1px solid #e5e7eb;display:flex;justify-content:space-between;align-items:center;background:#f8fafc">
            <div style="font-weight:700;font-size:0.95rem;color:#111827">📁 Rename Folder</div>
            <button type="button" onclick="closeFolderRenameModal()" style="background:transparent;border:none;font-size:1.2rem;color:#94a3b8;cursor:pointer">✕</button>
        </div>
        <div style="padding:20px">
            <label style="display:block;font-size:0.85rem;font-weight:600;color:#374151;margin-bottom:6px">Folder Name</label>
            <input type="text" id="renameFolderInput" style="width:100%;height:38px;padding:0 12px;border:1px solid #cbd5e1;border-radius:6px;font-size:0.9rem;outline:none" onkeydown="if(event.key==='Enter'){ event.preventDefault(); submitFolderRename(); }">
        </div>
        <div style="padding:12px 20px;border-top:1px solid #e5e7eb;display:flex;justify-content:flex-end;gap:8px;background:#f8fafc">
            <button type="button" onclick="closeFolderRenameModal()" style="background:#ffffff;border:1px solid #d1d5db;border-radius:6px;padding:7px 16px;font-size:0.85rem;color:#374151;cursor:pointer">Cancel</button>
            <button type="button" onclick="submitFolderRename()" style="background:#2563eb;color:white;border:none;border-radius:6px;padding:7px 18px;font-size:0.85rem;font-weight:600;cursor:pointer">Save Changes</button>
        </div>
    </div>
</div>

<!-- Move Folder Files Modal -->
<div id="folderMoveFilesModal" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.6);z-index:999999;align-items:center;justify-content:center;padding:16px" onclick="if(event.target===this) closeFolderMoveModal()">
    <div style="background:white;border-radius:12px;max-width:440px;width:100%;box-shadow:0 20px 40px rgba(0,0,0,0.25);overflow:hidden">
        <form id="folderMoveFilesForm" method="POST">
            @csrf
            <div style="padding:14px 18px;border-bottom:1px solid #e5e7eb;display:flex;justify-content:space-between;align-items:center;background:#f9fafb">
                <div style="font-weight:700;font-size:0.95rem;color:#111827">📁 Move All Files From Folder</div>
                <button type="button" class="btn btn-secondary btn-sm" onclick="closeFolderMoveModal()">✕</button>
            </div>
            <div style="padding:18px">
                <p style="font-size:0.85rem;color:#64748b;margin-bottom:14px">Move all images from <strong id="moveFolderFilesSource"></strong> to another folder:</p>
                <label class="form-label">Destination Folder <span class="required">*</span></label>
                <select name="target_folder" class="form-control" required id="folderMoveSelect">
                    @foreach($folders as $f)
                        <option value="{{ $f->slug }}">📁 {{ $f->name }}</option>
                    @endforeach
                </select>
            </div>
            <div style="padding:12px 18px;border-top:1px solid #e5e7eb;display:flex;justify-content:flex-end;gap:8px;background:#f9fafb">
                <button type="button" class="btn btn-secondary btn-sm" onclick="closeFolderMoveModal()">Cancel</button>
                <button type="submit" class="btn btn-primary btn-sm">Move All Files</button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Folder Confirmation Modal -->
<div id="folderDeleteModal" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.6);z-index:1000006;align-items:center;justify-content:center;padding:16px" onclick="if(event.target===this) closeFolderDeleteModal()">
    <div style="background:white;border-radius:12px;max-width:420px;width:100%;box-shadow:0 20px 40px rgba(0,0,0,0.25);overflow:hidden">
        <div style="padding:16px 20px;border-bottom:1px solid #e5e7eb;display:flex;justify-content:space-between;align-items:center;background:#fef2f2">
            <div style="font-weight:700;font-size:1rem;color:#b91c1c;display:flex;align-items:center;gap:8px">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                Delete Folder
            </div>
            <button type="button" onclick="closeFolderDeleteModal()" style="background:transparent;border:none;font-size:1.2rem;color:#94a3b8;cursor:pointer">✕</button>
        </div>
        <div style="padding:20px;color:#374151;font-size:0.9rem;line-height:1.5">
            Are you sure you want to delete folder <strong id="deleteFolderModalName" style="color:#111827"></strong>?
            <div id="deleteFolderWarningText" style="margin-top:8px;font-size:0.85rem"></div>
        </div>
        <div style="padding:12px 20px;border-top:1px solid #e5e7eb;display:flex;justify-content:flex-end;gap:8px;background:#f8fafc">
            <button type="button" onclick="closeFolderDeleteModal()" style="background:#ffffff;border:1px solid #d1d5db;border-radius:6px;padding:8px 16px;font-size:0.85rem;color:#374151;cursor:pointer">Cancel</button>
            <button type="button" id="confirmFolderDeleteBtn" onclick="executeDeleteFolder()" style="background:#ef4444;color:white;border:none;border-radius:6px;padding:8px 20px;font-size:0.85rem;font-weight:600;cursor:pointer">Delete Folder</button>
        </div>
    </div>
</div>

<style>
.gallery-context-item:hover {
    background: #f1f5f9;
}
.gallery-context-item[style*="color:#ef4444"]:hover {
    background: #fef2f2 !important;
    color: #dc2626 !important;
}
.gallery-file-card:hover .gallery-card-options-btn,
.gallery-folder-card:hover .gallery-card-options-btn {
    opacity: 1 !important;
}
</style>

@endsection

@push('scripts')
<script>
const uploadUrl = '{{ route("admin.gallery.upload") }}';
const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

let currentActiveMedia = null;
let currentCropAspect = 'free';

// Direct Upload Handler
async function handleDirectUpload(files) {
    if (!files || !files.length) return;

    const statusEl = document.getElementById('uploadStatusMsg');
    statusEl.style.display = 'block';
    statusEl.textContent = `⏳ Converting ${files.length} file(s) to .webp preserving 100% resolution...`;

    const formData = new FormData();
    for (let i = 0; i < files.length; i++) {
        formData.append('files[]', files[i]);
    }
    const uploadFolder = '{{ $currentFolder === "all" || $currentFolder === "root" ? "gallery" : $currentFolder }}';
    formData.append('folder', uploadFolder);

    try {
        const res = await fetch(uploadUrl, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: formData
        });

        const data = await res.json();
        if (data.success) {
            statusEl.textContent = '✓ ' + data.message + ' Refreshing...';
            setTimeout(() => window.location.reload(), 700);
        } else {
            alert('Upload failed: ' + (data.message || 'Unknown error'));
            statusEl.style.display = 'none';
        }
    } catch(e) {
        alert('Network error uploading files.');
        statusEl.style.display = 'none';
    }
}

// Open Floating Context Menu
function openGalleryContextMenu(e, media, triggerBtn = null) {
    currentActiveMedia = media;
    const menu = document.getElementById('galleryContextMenu');
    menu.style.display = 'block';

    let x = e.clientX;
    let y = e.clientY;

    if (triggerBtn) {
        const rect = triggerBtn.getBoundingClientRect();
        x = rect.left;
        y = rect.bottom + 4;
    }

    const menuWidth = 170;
    const menuHeight = 220;
    if (x + menuWidth > window.innerWidth) x = window.innerWidth - menuWidth - 10;
    if (y + menuHeight > window.innerHeight) y = window.innerHeight - menuHeight - 10;

    menu.style.left = `${Math.max(10, x)}px`;
    menu.style.top = `${Math.max(10, y)}px`;
}

function closeGalleryContextMenu() {
    const menu = document.getElementById('galleryContextMenu');
    if (menu) menu.style.display = 'none';
}

function closeFolderContextMenu() {
    const menu = document.getElementById('folderContextMenu');
    if (menu) menu.style.display = 'none';
}

// Folder Context Menu & Action Handlers
let currentActiveFolder = null;
let folderPendingDelete = null;

function openFolderContextMenu(e, folder, triggerBtn = null) {
    currentActiveFolder = folder;
    const menu = document.getElementById('folderContextMenu');
    menu.style.display = 'block';

    const renameItem = document.getElementById('folderCtxRename');
    const deleteItem = document.getElementById('folderCtxDelete');
    const divider = document.getElementById('folderCtxDivider');

    if (folder.is_system) {
        if (renameItem) renameItem.style.display = 'none';
        if (deleteItem) deleteItem.style.display = 'none';
        if (divider) divider.style.display = 'none';
    } else {
        if (renameItem) renameItem.style.display = 'block';
        if (deleteItem) deleteItem.style.display = 'block';
        if (divider) divider.style.display = 'block';
    }

    let x = e.clientX;
    let y = e.clientY;

    if (triggerBtn) {
        const rect = triggerBtn.getBoundingClientRect();
        x = rect.left;
        y = rect.bottom + 4;
    }

    const menuWidth = 175;
    const menuHeight = 220;
    if (x + menuWidth > window.innerWidth) x = window.innerWidth - menuWidth - 10;
    if (y + menuHeight > window.innerHeight) y = window.innerHeight - menuHeight - 10;

    menu.style.left = `${Math.max(10, x)}px`;
    menu.style.top = `${Math.max(10, y)}px`;
}

function triggerFolderAction(action) {
    closeFolderContextMenu();
    if (!currentActiveFolder) return;

    switch(action) {
        case 'open':
            window.location.href = `{{ route('admin.gallery.index') }}?folder=${currentActiveFolder.slug}`;
            break;
        case 'rename':
            showFolderRenameModal(currentActiveFolder);
            break;
        case 'move':
            showFolderMoveModal(currentActiveFolder);
            break;
        case 'download':
            downloadFolderZip(currentActiveFolder);
            break;
        case 'copylink':
            const folderUrl = `${window.location.origin}{{ route('admin.gallery.index') }}?folder=${currentActiveFolder.slug}`;
            navigator.clipboard.writeText(folderUrl).then(() => {
                showGalleryToast('✓ Folder link copied to clipboard');
            }).catch(() => {
                showGalleryToast('✓ Folder link: ' + folderUrl);
            });
            break;
        case 'delete':
            deleteFolder(currentActiveFolder);
            break;
    }
}

// Rename Folder Modal
function showFolderRenameModal(folder) {
    currentActiveFolder = folder;
    const input = document.getElementById('renameFolderInput');
    input.value = folder.name;
    document.getElementById('folderRenameModal').style.display = 'flex';
    setTimeout(() => {
        input.focus();
        input.select();
    }, 50);
}

function closeFolderRenameModal() {
    document.getElementById('folderRenameModal').style.display = 'none';
}

async function submitFolderRename() {
    if (!currentActiveFolder) return;
    const newName = document.getElementById('renameFolderInput').value.trim();
    if (!newName) {
        alert('Please enter a folder name.');
        return;
    }

    try {
        const res = await fetch(`/admin/gallery/folders/${currentActiveFolder.id}/rename`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ name: newName })
        });

        const data = await res.json();
        if (data.success) {
            closeFolderRenameModal();
            showGalleryToast('✓ ' + (data.message || 'Folder renamed successfully'));
            setTimeout(() => window.location.reload(), 500);
        } else {
            alert(data.message || 'Error renaming folder');
        }
    } catch(e) {
        alert('Network error while renaming folder');
    }
}

// Move Folder Files Modal
function showFolderMoveModal(folder) {
    currentActiveFolder = folder;
    document.getElementById('moveFolderFilesSource').textContent = `[${folder.name}]`;
    const form = document.getElementById('folderMoveFilesForm');
    form.action = `/admin/gallery/folders/${folder.id}/move-files`;

    const select = document.getElementById('folderMoveSelect');
    if (select) {
        Array.from(select.options).forEach(opt => {
            if (opt.value === folder.slug) {
                opt.disabled = true;
            } else {
                opt.disabled = false;
            }
        });
    }

    document.getElementById('folderMoveFilesModal').style.display = 'flex';
}

function closeFolderMoveModal() {
    document.getElementById('folderMoveFilesModal').style.display = 'none';
}

// Delete Folder Modal
function deleteFolder(folder) {
    if (!folder) return;
    if (folder.is_system) {
        alert('System folders cannot be deleted.');
        return;
    }
    folderPendingDelete = folder;
    document.getElementById('deleteFolderModalName').textContent = `"${folder.name}"`;
    const warningEl = document.getElementById('deleteFolderWarningText');
    if (warningEl) {
        if (folder.media_count > 0) {
            warningEl.innerHTML = `<span style="color:#ef4444;font-weight:600">⚠️ Note: This folder currently contains ${folder.media_count} file(s). Files must be moved or deleted before this folder can be removed.</span>`;
        } else {
            warningEl.innerHTML = '<span style="color:#64748b">This empty folder will be permanently removed.</span>';
        }
    }
    document.getElementById('folderDeleteModal').style.display = 'flex';
}

function closeFolderDeleteModal() {
    document.getElementById('folderDeleteModal').style.display = 'none';
    folderPendingDelete = null;
}

async function executeDeleteFolder() {
    if (!folderPendingDelete) return;
    const btn = document.getElementById('confirmFolderDeleteBtn');
    btn.disabled = true;
    btn.textContent = 'Deleting...';

    try {
        const res = await fetch(`/admin/gallery/folders/${folderPendingDelete.id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        });

        const data = await res.json();
        closeFolderDeleteModal();

        if (data.success) {
            showGalleryToast('✓ ' + (data.message || 'Folder deleted'));
            setTimeout(() => window.location.reload(), 500);
        } else {
            alert(data.message || 'Could not delete folder');
        }
    } catch(e) {
        alert('Network error deleting folder');
        closeFolderDeleteModal();
    } finally {
        btn.disabled = false;
        btn.textContent = 'Delete Folder';
    }
}

document.addEventListener('click', (e) => {
    if (!e.target.closest('#galleryContextMenu')) {
        closeGalleryContextMenu();
    }
    if (!e.target.closest('#folderContextMenu')) {
        closeFolderContextMenu();
    }
});

// Context Menu Action Dispatcher
function triggerContextAction(action) {
    closeGalleryContextMenu();
    if (!currentActiveMedia) return;

    switch(action) {
        case 'preview':
            openPreviewModal(currentActiveMedia);
            break;
        case 'rename':
            showRenameModal(currentActiveMedia);
            break;
        case 'crop':
            showCropModal(currentActiveMedia);
            break;
        case 'move':
            openMoveFromModal();
            break;
        case 'download':
            downloadMedia(currentActiveMedia);
            break;
        case 'copylink':
            copyMediaLink(currentActiveMedia);
            break;
        case 'delete':
            deleteMedia(currentActiveMedia);
            break;
    }
}

// 1. Preview Modal
function openPreviewModal(urlOrMedia, name, dimensions, size, mediaId, folder) {
    let activeId = null;
    let activeName = '';
    if (typeof urlOrMedia === 'object' && urlOrMedia !== null) {
        currentActiveMedia = urlOrMedia;
        activeId = urlOrMedia.id;
        activeName = urlOrMedia.original_name || 'image';
        document.getElementById('modalImgSrc').src = urlOrMedia.url;
        document.getElementById('modalImgTitle').textContent = urlOrMedia.original_name;
        document.getElementById('modalImgMeta').textContent = `${urlOrMedia.dimensions || ''} · ${urlOrMedia.size_formatted || ''} · Folder: [${urlOrMedia.folder || 'gallery'}] · WebP`;
    } else {
        const url = urlOrMedia;
        activeId = mediaId;
        activeName = name || 'image';
        document.getElementById('modalImgSrc').src = url;
        document.getElementById('modalImgTitle').textContent = name;
        document.getElementById('modalImgMeta').textContent = `${dimensions} · ${size} · Folder: [${folder}] · WebP`;
    }

    const downloadBtn = document.getElementById('modalDownloadBtn');
    if (downloadBtn && activeId) {
        const cleanBase = activeName.replace(/\.[^/.]+$/, "");
        const slug = cleanBase.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)/g, '') || 'image';
        const rawExt = (activeName.includes('.') ? activeName.split('.').pop() : 'webp').toLowerCase();
        const ext = ['jpg', 'jpeg', 'jpe'].includes(rawExt) ? 'jpg' : (rawExt === 'png' ? 'png' : 'webp');
        const filename = slug + '.' + ext;
        downloadBtn.href = `/admin/gallery/${activeId}/download/${filename}`;
        downloadBtn.download = filename;
    }

    document.getElementById('previewModal').style.display = 'flex';
}

function closePreviewModal() {
    document.getElementById('previewModal').style.display = 'none';
    document.getElementById('modalImgSrc').src = '';
}

function downloadFromPreviewModal() {
    if (!currentActiveMedia) return;
    downloadMedia(currentActiveMedia);
}

function triggerModalCopyUrl() {
    if (!currentActiveMedia) return;
    copyMediaLink(currentActiveMedia);
}

function openMoveFromModal() {
    if (!currentActiveMedia) return;
    const form = document.getElementById('moveMediaForm');
    form.action = `/admin/gallery/${currentActiveMedia.id}/move`;

    const select = form.querySelector('select[name="target_folder"]');
    if (select) {
        let firstAvailable = null;
        Array.from(select.options).forEach(opt => {
            if (opt.value === currentActiveMedia.folder) {
                opt.disabled = true;
                if (!opt.text.includes('(Current)')) opt.text += ' (Current)';
            } else {
                opt.disabled = false;
                opt.text = opt.text.replace(' (Current)', '');
                if (!firstAvailable) firstAvailable = opt.value;
            }
        });
        if (firstAvailable) select.value = firstAvailable;
    }

    document.getElementById('moveMediaModal').style.display = 'flex';
}

function deleteFromPreviewModal() {
    if (!currentActiveMedia) return;
    closePreviewModal();
    deleteMedia(currentActiveMedia);
}

function openCopyFromModal() {
    if (!currentActiveMedia) return;
    const form = document.getElementById('copyMediaForm');
    form.action = `/admin/gallery/${currentActiveMedia.id}/copy`;
    document.getElementById('copyMediaModal').style.display = 'flex';
}

// 2. Rename Modal
function showRenameModal(media) {
    document.getElementById('renameInput').value = media.original_name;
    document.getElementById('galleryRenameModal').style.display = 'flex';
    setTimeout(() => {
        const input = document.getElementById('renameInput');
        input.focus();
        input.select();
    }, 50);
}

function closeRenameModal() {
    document.getElementById('galleryRenameModal').style.display = 'none';
}

async function submitRename() {
    const newName = document.getElementById('renameInput').value.trim();
    if (!newName || !currentActiveMedia) return;

    try {
        const res = await fetch(`/admin/gallery/${currentActiveMedia.id}/rename`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ name: newName })
        });
        const data = await res.json();
        if (data.success) {
            closeRenameModal();
            showGalleryToast('✓ Image renamed successfully');
            setTimeout(() => window.location.reload(), 600);
        } else {
            alert(data.message || 'Error renaming image');
        }
    } catch(e) {
        alert('Network error while renaming');
    }
}

// 3. Crop Modal
let cropBox = { x: 50, y: 50, w: 200, h: 200 };
let isDraggingCrop = false;
let isResizingCrop = false;
let currentResizeDir = '';
let cropDragStart = { x: 0, y: 0 };

function showCropModal(media) {
    const img = document.getElementById('cropTargetImage');
    img.onload = () => {
        initCropBox();
    };
    img.src = media.url + (media.url.includes('?') ? '&' : '?') + 't=' + new Date().getTime();
    document.getElementById('galleryCropModal').style.display = 'flex';
}

function closeCropModal() {
    document.getElementById('galleryCropModal').style.display = 'none';
}

function setCropAspect(aspect) {
    currentCropAspect = aspect;
    document.querySelectorAll('.crop-aspect-btn').forEach(btn => {
        btn.style.background = 'white';
        btn.style.color = '#334155';
    });
    const activeBtn = Array.from(document.querySelectorAll('.crop-aspect-btn')).find(b => b.textContent.trim().toLowerCase().startsWith(aspect));
    if (activeBtn) {
        activeBtn.style.background = '#2563eb';
        activeBtn.style.color = 'white';
    }
    applyAspectToCropBox();
}

function initCropBox() {
    const img = document.getElementById('cropTargetImage');
    const w = img.clientWidth;
    const h = img.clientHeight;
    
    const initialW = Math.round(w * 0.7);
    const initialH = Math.round(h * 0.7);
    cropBox = {
        x: Math.round((w - initialW) / 2),
        y: Math.round((h - initialH) / 2),
        w: initialW,
        h: initialH
    };
    renderCropOverlay();
}

function applyAspectToCropBox() {
    const img = document.getElementById('cropTargetImage');
    if (!img) return;
    if (currentCropAspect === '1:1') {
        const side = Math.min(cropBox.w, cropBox.h);
        cropBox.w = side;
        cropBox.h = side;
    } else if (currentCropAspect === '4:3') {
        cropBox.h = Math.round(cropBox.w * (3 / 4));
    } else if (currentCropAspect === '16:9') {
        cropBox.h = Math.round(cropBox.w * (9 / 16));
    }
    renderCropOverlay();
}

function renderCropOverlay() {
    const overlay = document.getElementById('cropOverlayBox');
    if (!overlay) return;
    overlay.style.left = `${cropBox.x}px`;
    overlay.style.top = `${cropBox.y}px`;
    overlay.style.width = `${cropBox.w}px`;
    overlay.style.height = `${cropBox.h}px`;

    const img = document.getElementById('cropTargetImage');
    if (img && img.naturalWidth) {
        const scaleX = img.naturalWidth / img.clientWidth;
        const scaleY = img.naturalHeight / img.clientHeight;
        const realW = Math.round(cropBox.w * scaleX);
        const realH = Math.round(cropBox.h * scaleY);
        document.getElementById('cropResolutionBadge').textContent = `Crop Area: ${realW} × ${realH} px (Native Resolution)`;
    }
}

document.addEventListener('mousedown', (e) => {
    const handle = e.target.closest('.crop-handle');
    const overlay = e.target.closest('#cropOverlayBox');

    if (handle) {
        isResizingCrop = true;
        currentResizeDir = handle.dataset.dir;
        cropDragStart = { x: e.clientX, y: e.clientY };
        e.preventDefault();
        return;
    }

    if (overlay) {
        isDraggingCrop = true;
        cropDragStart = { x: e.clientX, y: e.clientY };
        e.preventDefault();
        return;
    }
});

document.addEventListener('mousemove', (e) => {
    if (!isDraggingCrop && !isResizingCrop) return;
    const img = document.getElementById('cropTargetImage');
    if (!img) return;

    const dx = e.clientX - cropDragStart.x;
    const dy = e.clientY - cropDragStart.y;
    cropDragStart = { x: e.clientX, y: e.clientY };

    const maxW = img.clientWidth;
    const maxH = img.clientHeight;

    if (isDraggingCrop) {
        cropBox.x = Math.max(0, Math.min(maxW - cropBox.w, cropBox.x + dx));
        cropBox.y = Math.max(0, Math.min(maxH - cropBox.h, cropBox.y + dy));
        renderCropOverlay();
    } else if (isResizingCrop) {
        if (currentResizeDir === 'se') {
            cropBox.w = Math.max(40, Math.min(maxW - cropBox.x, cropBox.w + dx));
            cropBox.h = Math.max(40, Math.min(maxH - cropBox.y, cropBox.h + dy));
        } else if (currentResizeDir === 'sw') {
            const newW = Math.max(40, cropBox.w - dx);
            cropBox.x = Math.max(0, cropBox.x + (cropBox.w - newW));
            cropBox.w = newW;
            cropBox.h = Math.max(40, Math.min(maxH - cropBox.y, cropBox.h + dy));
        } else if (currentResizeDir === 'ne') {
            cropBox.w = Math.max(40, Math.min(maxW - cropBox.x, cropBox.w + dx));
            const newH = Math.max(40, cropBox.h - dy);
            cropBox.y = Math.max(0, cropBox.y + (cropBox.h - newH));
            cropBox.h = newH;
        } else if (currentResizeDir === 'nw') {
            const newW = Math.max(40, cropBox.w - dx);
            cropBox.x = Math.max(0, cropBox.x + (cropBox.w - newW));
            cropBox.w = newW;
            const newH = Math.max(40, cropBox.h - dy);
            cropBox.y = Math.max(0, cropBox.y + (cropBox.h - newH));
            cropBox.h = newH;
        }
        applyAspectToCropBox();
    }
});

document.addEventListener('mouseup', () => {
    isDraggingCrop = false;
    isResizingCrop = false;
});

async function submitCrop() {
    if (!currentActiveMedia) return;
    const saveBtn = document.getElementById('saveCropBtn');
    saveBtn.disabled = true;
    saveBtn.textContent = 'Cropping...';

    const img = document.getElementById('cropTargetImage');
    const scaleX = img.naturalWidth / img.clientWidth;
    const scaleY = img.naturalHeight / img.clientHeight;

    const realX = Math.round(cropBox.x * scaleX);
    const realY = Math.round(cropBox.y * scaleY);
    const realW = Math.round(cropBox.w * scaleX);
    const realH = Math.round(cropBox.h * scaleY);

    const canvas = document.createElement('canvas');
    canvas.width = realW;
    canvas.height = realH;
    const ctx = canvas.getContext('2d');
    ctx.drawImage(img, realX, realY, realW, realH, 0, 0, realW, realH);
    const base64Data = canvas.toDataURL('image/webp', 0.92);

    try {
        const res = await fetch(`/admin/gallery/${currentActiveMedia.id}/crop`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                x: realX,
                y: realY,
                width: realW,
                height: realH,
                image_data: base64Data
            })
        });

        const data = await res.json();
        if (data.success) {
            closeCropModal();
            showGalleryToast('✓ Image cropped and saved as WebP');
            const thumb = document.getElementById(`thumb-img-${currentActiveMedia.id}`);
            if (thumb) {
                thumb.src = currentActiveMedia.url + '?t=' + new Date().getTime();
            } else {
                setTimeout(() => window.location.reload(), 600);
            }
        } else {
            alert(data.message || 'Crop failed');
        }
    } catch(e) {
        alert('Network error while saving crop');
    } finally {
        saveBtn.disabled = false;
        saveBtn.textContent = 'Apply & Save Crop';
    }
}

// Helper for reliable browser downloads without navigating away
function triggerFileDownload(url, filename) {
    const a = document.createElement('a');
    a.style.display = 'none';
    a.href = url;
    if (filename) {
        a.setAttribute('download', filename);
    }
    document.body.appendChild(a);
    a.click();
    setTimeout(() => {
        if (a.parentNode) {
            a.parentNode.removeChild(a);
        }
    }, 1500);
}

// 4. Download Single Image
function downloadMedia(media) {
    if (!media) return;
    const mediaId = typeof media === 'object' ? media.id : media;
    const originalName = typeof media === 'object' ? (media.original_name || 'image') : 'image';
    const cleanBase = originalName.replace(/\.[^/.]+$/, "");
    const slug = cleanBase.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)/g, '') || 'image';
    const rawExt = (originalName.includes('.') ? originalName.split('.').pop() : 'webp').toLowerCase();
    const ext = ['jpg', 'jpeg', 'jpe'].includes(rawExt) ? 'jpg' : (rawExt === 'png' ? 'png' : 'webp');
    const filename = slug + '.' + ext;
    const downloadUrl = `/admin/gallery/${mediaId}/download/${filename}`;
    
    showGalleryToast('✓ Downloading: ' + filename);
    triggerFileDownload(downloadUrl, filename);
}

// Download Folder ZIP
function downloadFolderZip(folder) {
    if (!folder) return;

    if (folder === 'all') {
        showGalleryToast('✓ Packaging all gallery assets into ZIP...');
        triggerFileDownload('/admin/gallery/folders/all/download/mst-all-media.zip', 'mst-all-media.zip');
        return;
    }

    if (typeof folder === 'object') {
        if (folder.media_count === 0) {
            alert(`Folder "${folder.name}" is empty. There are no images to download.`);
            return;
        }
        const cleanName = folder.name || folder.slug || 'folder';
        const slug = cleanName.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)/g, '') || 'folder';
        const filename = slug + '.zip';
        showGalleryToast('✓ Packaging folder "' + folder.name + '" into ZIP...');
        triggerFileDownload(`/admin/gallery/folders/${folder.id}/download/${filename}`, filename);
    } else {
        triggerFileDownload(`/admin/gallery/folders/${folder}/download/gallery.zip`, 'gallery.zip');
    }
}

// 5. Copy Link
function copyMediaLink(media) {
    const fullUrl = media.url.startsWith('http') ? media.url : window.location.origin + media.url;
    navigator.clipboard.writeText(fullUrl).then(() => {
        showGalleryToast('✓ Image URL copied to clipboard');
    }).catch(() => {
        const temp = document.createElement('input');
        temp.value = fullUrl;
        document.body.appendChild(temp);
        temp.select();
        document.execCommand('copy');
        document.body.removeChild(temp);
        showGalleryToast('✓ Image URL copied to clipboard');
    });
}

// 6. Delete
let mediaPendingDelete = null;

function deleteMedia(media) {
    if (!media) return;
    mediaPendingDelete = media;
    const mediaName = typeof media === 'object' ? (media.original_name || 'this image') : 'this image';
    document.getElementById('deleteModalItemName').textContent = `"${mediaName}"`;
    document.getElementById('galleryDeleteModal').style.display = 'flex';
}

function closeDeleteModal() {
    document.getElementById('galleryDeleteModal').style.display = 'none';
    mediaPendingDelete = null;
}

async function executeDeleteMedia() {
    if (!mediaPendingDelete) return;
    const btn = document.getElementById('confirmDeleteBtn');
    btn.disabled = true;
    btn.textContent = 'Deleting...';

    const mediaId = typeof mediaPendingDelete === 'object' ? mediaPendingDelete.id : mediaPendingDelete;

    try {
        const res = await fetch(`/admin/gallery/${mediaId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        });

        const data = await res.json();
        closeDeleteModal();

        if (data.success) {
            const card = document.querySelector(`[data-media-id="${mediaId}"]`);
            if (card) {
                card.style.transition = 'opacity 0.25s, transform 0.25s';
                card.style.opacity = '0';
                card.style.transform = 'scale(0.8)';
                setTimeout(() => {
                    card.remove();
                    const remaining = document.querySelectorAll('.gallery-file-card');
                    if (remaining.length === 0) {
                        window.location.reload();
                    }
                }, 250);
            }
            showGalleryToast('✓ ' + (data.message || 'Image deleted'));
        } else {
            alert(data.message || 'Could not delete file');
        }
    } catch(e) {
        alert('Network error deleting image');
        closeDeleteModal();
    } finally {
        btn.disabled = false;
        btn.textContent = 'Delete';
    }
}

// Toast Helper
function showGalleryToast(msg) {
    const t = document.getElementById('galleryPageToast');
    t.textContent = msg;
    t.style.display = 'flex';
    t.style.opacity = '1';
    setTimeout(() => {
        t.style.opacity = '0';
        setTimeout(() => { t.style.display = 'none'; }, 200);
    }, 2800);
}
</script>
@endpush
