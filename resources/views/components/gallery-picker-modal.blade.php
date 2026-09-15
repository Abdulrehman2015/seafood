<!-- Universal Gallery Picker Modal matching reference design -->
<div id="galleryPickerModal" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.65);backdrop-filter:blur(3px);z-index:99999;align-items:center;justify-content:center;padding:16px">
    <div style="background:white;border-radius:12px;max-width:980px;width:100%;max-height:92vh;display:flex;flex-direction:column;box-shadow:0 25px 50px -12px rgba(0,0,0,0.35);overflow:hidden">
        
        <!-- Header -->
        <div style="padding:14px 20px;border-bottom:1px solid #e5e7eb;display:flex;justify-content:space-between;align-items:center;background:white">
            <div style="font-weight:700;font-size:1.1rem;color:#1f2937">Select or Upload an Image</div>
            <button type="button" onclick="closeGalleryPicker()" style="background:transparent;border:none;font-size:1.4rem;color:#9ca3af;cursor:pointer;line-height:1;padding:4px" hover="color:#111">✕</button>
        </div>

        <!-- Scrollable Modal Content -->
        <div style="padding:18px 24px;overflow-y:auto;flex:1;display:flex;flex-direction:column;gap:14px">
            
            <!-- 1. Dropzone Block at Top matching screenshot -->
            <div>
                <div id="pickerDropArea" 
                     ondragover="event.preventDefault(); this.style.borderColor='#2563eb'; this.style.background='#eff6ff';" 
                     ondragleave="this.style.borderColor='#cbd5e1'; this.style.background='#ffffff';"
                     ondrop="event.preventDefault(); this.style.borderColor='#cbd5e1'; this.style.background='#ffffff'; handlePickerDropFiles(event.dataTransfer.files);"
                     onclick="document.getElementById('pickerNativeFileInput').click()"
                     style="border:1.5px dashed #cbd5e1;border-radius:8px;background:#ffffff;padding:32px 16px;text-align:center;cursor:pointer;transition:all 0.15s ease">
                    
                    <div style="font-weight:700;font-size:1.05rem;color:#1e293b;margin-bottom:4px">
                        Drop images here or click to upload
                    </div>
                    <div style="font-size:0.85rem;color:#64748b">
                        (Allowed: JPG, PNG, GIF, WEBP, SVG, BMP, AVIF · Max 5 MB)
                    </div>
                    <div id="pickerUploadProgress" style="display:none;margin-top:10px;font-weight:700;color:#0d9488;font-size:0.85rem"></div>

                    <input type="file" id="pickerNativeFileInput" multiple accept="image/*,application/pdf,video/mp4" style="display:none" onchange="handlePickerDropFiles(this.files)">
                </div>
            </div>

            <!-- 2. Path & Folder Creator Toolbar -->
            <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:10px;padding-top:4px">
                <!-- Current Path Breadcrumb -->
                <div style="display:flex;align-items:center;font-size:0.88rem;color:#111827;font-weight:700">
                    <span>Current Path:&nbsp;</span>
                    <span id="pickerPathBreadcrumbs" style="color:#4b5563;font-weight:500">/uploads/files</span>
                </div>

                <!-- Create Folder Input & Button -->
                <div style="display:flex;align-items:center">
                    <input type="text" id="pickerNewFolderName" placeholder="Enter folder name" 
                           style="height:36px;font-size:0.85rem;border:1px solid #d1d5db;border-right:none;border-radius:4px 0 0 4px;padding:0 12px;width:180px;outline:none"
                           onkeydown="if(event.key==='Enter'){ event.preventDefault(); submitPickerCreateFolder(); }">
                    <button type="button" onclick="submitPickerCreateFolder()" 
                            style="height:36px;background:#ffffff;border:1px solid #d1d5db;border-radius:0 4px 4px 0;padding:0 14px;font-size:0.85rem;font-weight:500;color:#374151;cursor:pointer">
                        Create Folder
                    </button>
                </div>
            </div>

            <!-- 3. Grid Container (Folders & Images) -->
            <div id="pickerCardsGrid" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(118px,1fr));gap:14px;min-height:280px;align-content:start">
                <div style="grid-column:1/-1;text-align:center;padding:40px;color:#9ca3af">Loading library...</div>
            </div>

        </div>

        <!-- Footer -->
        <div style="padding:14px 24px;border-top:1px solid #e5e7eb;display:flex;justify-content:flex-end;gap:10px;align-items:center;background:white">
            <button type="button" id="pickerSelectBtn" onclick="confirmPickerSelection()" 
                    style="background:#2563eb;color:white;font-weight:600;font-size:0.88rem;border:none;border-radius:6px;padding:9px 24px;cursor:pointer;opacity:0.5;pointer-events:none;transition:all 0.15s">
                Select Image
            </button>
            <button type="button" onclick="closeGalleryPicker()" 
                    style="background:#4b5563;color:white;font-weight:600;font-size:0.88rem;border:none;border-radius:6px;padding:9px 22px;cursor:pointer">
                Close
            </button>
        </div>

    </div>
</div>

<!-- Floating Context Menu matching exact screenshot -->
<div id="galleryContextMenu" style="display:none;position:fixed;z-index:1000002;background:#ffffff;border:1px solid #e2e8f0;border-radius:8px;box-shadow:0 10px 25px -5px rgba(0,0,0,0.18),0 8px 10px -6px rgba(0,0,0,0.08);min-width:165px;padding:6px 0;font-family:inherit">
    <div class="context-item" onclick="triggerContextMenuAction('preview')" style="padding:8px 18px;font-size:0.85rem;color:#334155;font-weight:500;cursor:pointer;transition:background 0.1s">
        Preview
    </div>
    <div class="context-item" onclick="triggerContextMenuAction('rename')" style="padding:8px 18px;font-size:0.85rem;color:#334155;font-weight:500;cursor:pointer;transition:background 0.1s">
        Rename
    </div>
    <div class="context-item" onclick="triggerContextMenuAction('crop')" style="padding:8px 18px;font-size:0.85rem;color:#334155;font-weight:500;cursor:pointer;transition:background 0.1s">
        Crop
    </div>
    <div class="context-item" onclick="triggerContextMenuAction('move')" style="padding:8px 18px;font-size:0.85rem;color:#334155;font-weight:500;cursor:pointer;transition:background 0.1s">
        Move
    </div>
    <div class="context-item" onclick="triggerContextMenuAction('download')" style="padding:8px 18px;font-size:0.85rem;color:#334155;font-weight:500;cursor:pointer;transition:background 0.1s">
        Download
    </div>
    <div class="context-item" onclick="triggerContextMenuAction('copylink')" style="padding:8px 18px;font-size:0.85rem;color:#334155;font-weight:500;cursor:pointer;transition:background 0.1s">
        Copy Link
    </div>
    <div style="height:1px;background:#f1f5f9;margin:4px 0"></div>
    <div class="context-item" onclick="triggerContextMenuAction('delete')" style="padding:8px 18px;font-size:0.85rem;color:#ef4444;font-weight:500;cursor:pointer;transition:background 0.1s">
        Delete
    </div>
</div>

<!-- Lightbox Preview Modal -->
<div id="galleryPreviewModal" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.85);z-index:1000005;align-items:center;justify-content:center;padding:20px" onclick="if(event.target===this) closePreviewModal()">
    <div style="background:white;border-radius:12px;max-width:920px;width:100%;overflow:hidden;box-shadow:0 25px 50px rgba(0,0,0,0.4)">
        <div style="padding:14px 20px;display:flex;justify-content:space-between;align-items:center;border-bottom:1px solid #e5e7eb;background:#f8fafc">
            <div>
                <div id="previewModalTitle" style="font-weight:700;color:#111827;font-size:1rem">Image Preview</div>
                <div id="previewModalMeta" style="font-size:0.78rem;color:#64748b"></div>
            </div>
            <button type="button" onclick="closePreviewModal()" style="background:transparent;border:none;font-size:1.4rem;color:#94a3b8;cursor:pointer;line-height:1">✕</button>
        </div>
        <div style="max-height:72vh;overflow:auto;background:#0f172a;display:flex;align-items:center;justify-content:center;padding:16px">
            <img id="previewModalImg" src="" alt="Preview" style="max-width:100%;max-height:68vh;object-fit:contain;border-radius:4px">
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
                    <!-- Corner handles -->
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

<!-- Picker Move Modal -->
<div id="pickerMoveModal" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.6);z-index:1000005;align-items:center;justify-content:center;padding:16px" onclick="if(event.target===this) closePickerMoveModal()">
    <div style="background:white;border-radius:12px;max-width:440px;width:100%;box-shadow:0 20px 40px rgba(0,0,0,0.25);overflow:hidden">
        <div style="padding:14px 20px;border-bottom:1px solid #e5e7eb;display:flex;justify-content:space-between;align-items:center;background:#f8fafc">
            <div style="font-weight:700;font-size:0.95rem;color:#111827">📁 Move Image to Folder</div>
            <button type="button" onclick="closePickerMoveModal()" style="background:transparent;border:none;font-size:1.2rem;color:#94a3b8;cursor:pointer">✕</button>
        </div>
        <div style="padding:20px">
            <label style="display:block;font-size:0.85rem;font-weight:600;color:#374151;margin-bottom:6px">Select Destination Folder</label>
            <select id="pickerMoveFolderSelect" style="width:100%;height:38px;padding:0 12px;border:1px solid #cbd5e1;border-radius:6px;font-size:0.9rem;outline:none">
            </select>
        </div>
        <div style="padding:12px 20px;border-top:1px solid #e5e7eb;display:flex;justify-content:flex-end;gap:8px;background:#f8fafc">
            <button type="button" onclick="closePickerMoveModal()" style="background:#ffffff;border:1px solid #d1d5db;border-radius:6px;padding:7px 16px;font-size:0.85rem;color:#374151;cursor:pointer">Cancel</button>
            <button type="button" onclick="submitPickerMove()" style="background:#2563eb;color:white;border:none;border-radius:6px;padding:7px 18px;font-size:0.85rem;font-weight:600;cursor:pointer">Move File</button>
        </div>
    </div>
</div>

<!-- Toast Notification -->
<div id="galleryPickerToast" style="display:none;position:fixed;bottom:24px;right:24px;background:#1e293b;color:white;padding:10px 18px;border-radius:8px;font-size:0.85rem;font-weight:500;box-shadow:0 10px 25px rgba(0,0,0,0.25);z-index:1000010;align-items:center;gap:8px;transition:opacity 0.2s"></div>

<style>
.context-item:hover {
    background: #f1f5f9;
}
.context-item[style*="color:#ef4444"]:hover {
    background: #fef2f2 !important;
    color: #dc2626 !important;
}
.picker-file-card:hover .picker-card-actions {
    opacity: 1 !important;
}
</style>

<script>
let currentPickerCallback = null;
let isPickerMulti = false;
let pickerActiveFolder = 'root';
let pickerSelectedFiles = []; // array of {path, url, name, id}
let activeContextMedia = null; // currently right-clicked media item
let currentCropAspect = 'free';

// Open Modal API
function openGalleryPicker(callback, isMultiple = false) {
    currentPickerCallback = callback;
    isPickerMulti = isMultiple;
    pickerSelectedFiles = [];
    updatePickerSelectButton();
    document.getElementById('galleryPickerModal').style.display = 'flex';
    loadPickerExplorer('root');
}

function closeGalleryPicker() {
    document.getElementById('galleryPickerModal').style.display = 'none';
    closeContextMenu();
    currentPickerCallback = null;
    pickerSelectedFiles = [];
}

// Load Files and Folders via API
async function loadPickerExplorer(folder = 'root') {
    pickerActiveFolder = folder;
    const grid = document.getElementById('pickerCardsGrid');
    grid.innerHTML = '<div style="grid-column:1/-1;text-align:center;padding:50px;color:#9ca3af">Loading gallery...</div>';
    
    // Update breadcrumbs
    const bc = document.getElementById('pickerPathBreadcrumbs');
    if (folder === 'root' || folder === 'all') {
        bc.innerHTML = '<span style="color:#2563eb;cursor:pointer;font-weight:600">/uploads/files</span>';
    } else {
        bc.innerHTML = `<span onclick="loadPickerExplorer('root')" style="color:#2563eb;cursor:pointer;text-decoration:underline">/uploads/files</span> / <span style="font-weight:700;color:#111827">${folder}</span>`;
    }

    try {
        const res = await fetch(`{{ route("admin.gallery.api") }}?folder=${encodeURIComponent(folder)}`);
        const json = await res.json();
        const folders = json.folders || [];
        const files = json.data || [];

        renderPickerGrid(folders, files);
    } catch(e) {
        grid.innerHTML = '<div style="grid-column:1/-1;text-align:center;padding:50px;color:#ef4444">Failed to load media files.</div>';
    }
}

// Render the exact Cards Grid matching screenshot
function renderPickerGrid(folders, files) {
    const grid = document.getElementById('pickerCardsGrid');
    grid.innerHTML = '';

    if (!folders.length && !files.length) {
        grid.innerHTML = '<div style="grid-column:1/-1;text-align:center;padding:40px;color:#9ca3af">This folder is empty. Drop files above to upload!</div>';
        return;
    }

    // 1. Render Folder Cards
    folders.forEach(f => {
        const folderCard = document.createElement('div');
        folderCard.className = 'picker-folder-card';
        folderCard.style = 'aspect-ratio:1;background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;position:relative;display:flex;flex-direction:column;align-items:center;justify-content:center;cursor:pointer;transition:all 0.15s;padding:8px';
        folderCard.title = `Open folder: ${f.name}`;
        
        folderCard.innerHTML = `
            <!-- Red Delete Button for Folder -->
            ${!f.is_system ? `
            <button type="button" onclick="event.stopPropagation(); deletePickerFolder('${f.id}', '${f.name}')" 
                    title="Delete Folder"
                    style="position:absolute;top:6px;right:6px;background:#ef4444;color:white;border:none;border-radius:4px;width:22px;height:22px;display:flex;align-items:center;justify-content:center;cursor:pointer;font-size:11px;z-index:2">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
            </button>` : ''}

            <!-- Yellow Folder Graphic -->
            <div style="display:flex;align-items:center;justify-content:center;margin-bottom:4px">
                <svg width="52" height="42" viewBox="0 0 24 24" fill="#fbbf24" stroke="#f59e0b" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path>
                </svg>
            </div>

            <!-- Folder Name -->
            <div style="font-size:0.75rem;color:#475569;font-weight:600;text-align:center;width:100%;overflow:hidden;text-overflow:ellipsis;white-space:nowrap" title="${f.name}">
                ${f.name}
            </div>
        `;

        folderCard.onclick = () => loadPickerExplorer(f.slug);
        grid.appendChild(folderCard);
    });

    // 2. Render File / Image Cards
    files.forEach(file => {
        const isSelected = pickerSelectedFiles.some(item => item.path === file.path);

        const fileCard = document.createElement('div');
        fileCard.className = 'picker-file-card';
        fileCard.dataset.path = file.path;
        fileCard.dataset.id = file.id;
        fileCard.style = `aspect-ratio:1;background:#f8fafc;border:${isSelected ? '3px solid #2563eb' : '1px solid #e2e8f0'};border-radius:8px;position:relative;overflow:hidden;cursor:pointer;transition:all 0.15s;box-shadow:${isSelected ? '0 0 0 2px rgba(37,99,235,0.3)' : 'none'}`;
        fileCard.title = `${file.original_name} (${file.size_formatted})`;

        fileCard.innerHTML = `
            <!-- Red Delete Button for Image -->
            <button type="button" onclick="event.stopPropagation(); deletePickerFile('${file.id}', '${file.original_name}')" 
                    title="Delete Image"
                    style="position:absolute;top:6px;right:6px;background:#ef4444;color:white;border:none;border-radius:4px;width:22px;height:22px;display:flex;align-items:center;justify-content:center;cursor:pointer;font-size:11px;z-index:2">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </button>

            <!-- 3-Dots Trigger Button -->
            <button type="button" onclick="event.stopPropagation(); openContextMenuForCard(event, ${JSON.stringify(file).replace(/"/g, '&quot;')}, this)"
                    title="More options"
                    class="picker-card-actions"
                    style="position:absolute;top:6px;left:6px;background:rgba(255,255,255,0.9);color:#334155;border:1px solid #cbd5e1;border-radius:4px;width:22px;height:22px;display:flex;align-items:center;justify-content:center;cursor:pointer;font-size:12px;font-weight:700;z-index:2;opacity:0.85;transition:opacity 0.15s">
                ⋮
            </button>

            <!-- Image Thumbnail -->
            <img src="${file.url}" alt="${file.original_name}" loading="lazy" style="width:100%;height:100%;object-fit:cover;display:block">
        `;

        // Right Click triggers Context Menu
        fileCard.oncontextmenu = (e) => {
            e.preventDefault();
            e.stopPropagation();
            openContextMenuForCard(e, file);
        };

        // Click to select
        fileCard.onclick = () => toggleFileSelection(file, fileCard);

        // Double click to immediately pick & close
        fileCard.ondblclick = () => {
            selectSingleFileAndClose(file);
        };

        grid.appendChild(fileCard);
    });
}

// Open Floating Context Menu
function openContextMenuForCard(e, file, triggerBtn = null) {
    activeContextMedia = file;
    const menu = document.getElementById('galleryContextMenu');
    menu.style.display = 'block';

    let x = e.clientX;
    let y = e.clientY;

    if (triggerBtn) {
        const rect = triggerBtn.getBoundingClientRect();
        x = rect.left;
        y = rect.bottom + 4;
    }

    // Keep menu inside viewport
    const menuWidth = 170;
    const menuHeight = 220;
    if (x + menuWidth > window.innerWidth) x = window.innerWidth - menuWidth - 10;
    if (y + menuHeight > window.innerHeight) y = window.innerHeight - menuHeight - 10;

    menu.style.left = `${Math.max(10, x)}px`;
    menu.style.top = `${Math.max(10, y)}px`;
}

function closeContextMenu() {
    const menu = document.getElementById('galleryContextMenu');
    if (menu) menu.style.display = 'none';
}

// Global dismiss for context menu
document.addEventListener('click', (e) => {
    if (!e.target.closest('#galleryContextMenu')) {
        closeContextMenu();
    }
});

// Context Menu Action Dispatcher
function triggerContextMenuAction(action) {
    closeContextMenu();
    if (!activeContextMedia) return;

    switch(action) {
        case 'preview':
            showPreview(activeContextMedia);
            break;
        case 'rename':
            showRename(activeContextMedia);
            break;
        case 'crop':
            showCrop(activeContextMedia);
            break;
        case 'move':
            showPickerMove(activeContextMedia);
            break;
        case 'download':
            downloadMedia(activeContextMedia);
            break;
        case 'copylink':
            copyMediaLink(activeContextMedia);
            break;
        case 'delete':
            deletePickerFile(activeContextMedia.id, activeContextMedia.original_name);
            break;
    }
}

// 1. Preview
function showPreview(file) {
    document.getElementById('previewModalImg').src = file.url;
    document.getElementById('previewModalTitle').textContent = file.original_name;
    document.getElementById('previewModalMeta').textContent = `${file.dimensions || ''} · ${file.size_formatted || ''} · Folder: ${file.folder || 'gallery'}`;
    document.getElementById('galleryPreviewModal').style.display = 'flex';
}
function closePreviewModal() {
    document.getElementById('galleryPreviewModal').style.display = 'none';
}

// 2. Rename
function showRename(file) {
    document.getElementById('renameInput').value = file.original_name;
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
    if (!newName || !activeContextMedia) return;

    try {
        const res = await fetch(`/admin/gallery/${activeContextMedia.id}/rename`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ name: newName })
        });
        const data = await res.json();
        if (data.success) {
            closeRenameModal();
            showPickerToast('✓ Image renamed successfully');
            loadPickerExplorer(pickerActiveFolder);
        } else {
            alert(data.message || 'Error renaming image');
        }
    } catch(e) {
        alert('Network error while renaming');
    }
}

// 3. Crop Tool
let cropBox = { x: 50, y: 50, w: 200, h: 200 };
let isDraggingCrop = false;
let isResizingCrop = false;
let currentResizeDir = '';
let cropDragStart = { x: 0, y: 0 };

function showCrop(file) {
    const img = document.getElementById('cropTargetImage');
    img.onload = () => {
        initCropBox();
    };
    img.src = file.url + (file.url.includes('?') ? '&' : '?') + 't=' + new Date().getTime();
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
    
    // Initial crop box at 70% of view
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

    // Calculate actual pixel dimensions
    const img = document.getElementById('cropTargetImage');
    if (img && img.naturalWidth) {
        const scaleX = img.naturalWidth / img.clientWidth;
        const scaleY = img.naturalHeight / img.clientHeight;
        const realW = Math.round(cropBox.w * scaleX);
        const realH = Math.round(cropBox.h * scaleY);
        document.getElementById('cropResolutionBadge').textContent = `Crop Area: ${realW} × ${realH} px (Native Resolution)`;
    }
}

// Mouse dragging & resizing for Crop Box
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

// Submit Crop to Server
async function submitCrop() {
    if (!activeContextMedia) return;
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

    // Render cropped result to canvas to send both base64 & coordinates
    const canvas = document.createElement('canvas');
    canvas.width = realW;
    canvas.height = realH;
    const ctx = canvas.getContext('2d');
    ctx.drawImage(img, realX, realY, realW, realH, 0, 0, realW, realH);
    const base64Data = canvas.toDataURL('image/webp', 0.92);

    try {
        const res = await fetch(`/admin/gallery/${activeContextMedia.id}/crop`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
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
            showPickerToast('✓ Image cropped and saved as WebP');
            loadPickerExplorer(pickerActiveFolder);
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

// 4. Download
function downloadMedia(file) {
    const a = document.createElement('a');
    a.href = `/admin/gallery/${file.id}/download`;
    a.download = file.original_name;
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    showPickerToast('✓ Starting download...');
}

// 5. Copy Link
function copyMediaLink(file) {
    const fullUrl = file.url.startsWith('http') ? file.url : window.location.origin + file.url;
    navigator.clipboard.writeText(fullUrl).then(() => {
        showPickerToast('✓ Image URL copied to clipboard');
    }).catch(() => {
        // Fallback
        const temp = document.createElement('input');
        temp.value = fullUrl;
        document.body.appendChild(temp);
        temp.select();
        document.execCommand('copy');
        document.body.removeChild(temp);
        showPickerToast('✓ Image URL copied to clipboard');
    });
}

// 6. Move Image
async function showPickerMove(file) {
    if (!file) return;
    const select = document.getElementById('pickerMoveFolderSelect');
    select.innerHTML = '<option value="">Loading folders...</option>';
    document.getElementById('pickerMoveModal').style.display = 'flex';

    try {
        const res = await fetch(`{{ route("admin.gallery.api") }}?folder=root`);
        const json = await res.json();
        const folders = json.folders || [];
        select.innerHTML = '';
        folders.forEach(f => {
            const opt = document.createElement('option');
            opt.value = f.slug;
            opt.textContent = `📁 ${f.name}`;
            if (f.slug === file.folder) {
                opt.disabled = true;
                opt.textContent += ' (Current)';
            }
            select.appendChild(opt);
        });
    } catch(e) {
        select.innerHTML = '<option value="">Error loading folders</option>';
    }
}

function closePickerMoveModal() {
    document.getElementById('pickerMoveModal').style.display = 'none';
}

async function submitPickerMove() {
    if (!activeContextMedia) return;
    const targetFolder = document.getElementById('pickerMoveFolderSelect').value;
    if (!targetFolder) {
        alert('Please select a destination folder.');
        return;
    }

    try {
        const res = await fetch(`/admin/gallery/${activeContextMedia.id}/move`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ target_folder: targetFolder })
        });
        const data = await res.json();
        if (data.success) {
            closePickerMoveModal();
            showPickerToast('✓ ' + (data.message || 'Image moved successfully'));
            loadPickerExplorer(pickerActiveFolder);
        } else {
            alert(data.message || 'Error moving image');
        }
    } catch(e) {
        alert('Network error moving image');
    }
}

// 7. Delete Image
async function deletePickerFile(id, name) {
    if (!confirm(`Are you sure you want to delete "${name}"?`)) return;

    try {
        const res = await fetch(`/admin/gallery/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        });
        const data = await res.json();
        if (data.success) {
            showPickerToast('✓ ' + (data.message || 'Image deleted successfully'));
            loadPickerExplorer(pickerActiveFolder);
        } else {
            alert(data.message || 'Error deleting file');
        }
    } catch(e) {
        alert('Network error deleting image');
    }
}

// 8. Delete Folder
async function deletePickerFolder(id, name) {
    if (!confirm(`Delete folder "${name}"? Folder must be empty.`)) return;

    try {
        const res = await fetch(`/admin/gallery/folders/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        });
        const data = await res.json();
        if (data.success) {
            showPickerToast('✓ ' + (data.message || 'Folder deleted successfully'));
            loadPickerExplorer('root');
        } else {
            alert(data.message || 'Error deleting folder');
        }
    } catch(e) {
        alert('Network error deleting folder');
    }
}

// Toast Helper
function showPickerToast(msg) {
    const t = document.getElementById('galleryPickerToast');
    t.textContent = msg;
    t.style.display = 'flex';
    t.style.opacity = '1';
    setTimeout(() => {
        t.style.opacity = '0';
        setTimeout(() => { t.style.display = 'none'; }, 200);
    }, 2800);
}

// Toggle file selection
function toggleFileSelection(file, cardEl) {
    const idx = pickerSelectedFiles.findIndex(item => item.path === file.path);

    if (isPickerMulti) {
        if (idx > -1) {
            pickerSelectedFiles.splice(idx, 1);
            cardEl.style.border = '1px solid #e2e8f0';
            cardEl.style.boxShadow = 'none';
        } else {
            pickerSelectedFiles.push({ path: file.path, url: file.url, name: file.original_name, id: file.id });
            cardEl.style.border = '3px solid #2563eb';
            cardEl.style.boxShadow = '0 0 0 2px rgba(37,99,235,0.3)';
        }
    } else {
        // Single selection
        pickerSelectedFiles = [{ path: file.path, url: file.url, name: file.original_name, id: file.id }];
        
        document.querySelectorAll('.picker-file-card').forEach(c => {
            c.style.border = '1px solid #e2e8f0';
            c.style.boxShadow = 'none';
        });
        
        cardEl.style.border = '3px solid #2563eb';
        cardEl.style.boxShadow = '0 0 0 2px rgba(37,99,235,0.3)';
    }

    updatePickerSelectButton();
}

function selectSingleFileAndClose(file) {
    if (typeof currentPickerCallback === 'function') {
        currentPickerCallback(file.path, file.url, file.original_name);
    }
    closeGalleryPicker();
}

function updatePickerSelectButton() {
    const btn = document.getElementById('pickerSelectBtn');
    if (pickerSelectedFiles.length > 0) {
        btn.style.opacity = '1';
        btn.style.pointerEvents = 'auto';
        btn.textContent = isPickerMulti ? `Select (${pickerSelectedFiles.length})` : 'Select Image';
    } else {
        btn.style.opacity = '0.5';
        btn.style.pointerEvents = 'none';
        btn.textContent = 'Select Image';
    }
}

function confirmPickerSelection() {
    if (!pickerSelectedFiles.length) return;

    if (typeof currentPickerCallback === 'function') {
        if (isPickerMulti) {
            pickerSelectedFiles.forEach(file => {
                currentPickerCallback(file.path, file.url, file.name);
            });
        } else {
            const first = pickerSelectedFiles[0];
            currentPickerCallback(first.path, first.url, first.name);
        }
    }
    closeGalleryPicker();
}

// Create Folder via inline toolbar
async function submitPickerCreateFolder() {
    const input = document.getElementById('pickerNewFolderName');
    const name = input.value.trim();
    if (!name) return alert('Please enter a folder name.');

    const formData = new FormData();
    formData.append('name', name);

    try {
        const res = await fetch('{{ route("admin.gallery.folders.create") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: formData
        });

        const data = await res.json();
        if (data.success) {
            input.value = '';
            loadPickerExplorer(pickerActiveFolder);
        } else {
            alert(data.message || 'Error creating folder.');
        }
    } catch(e) {
        alert('Failed to create folder.');
    }
}

// Delete Folder
async function deletePickerFolder(folderId, folderName) {
    if (!confirm(`Delete folder "${folderName}"? Folder must be empty.`)) return;

    try {
        const res = await fetch(`/admin/gallery/folders/${folderId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        });

        const data = await res.json();
        if (data.success) {
            loadPickerExplorer(pickerActiveFolder);
        } else {
            alert(data.message || 'Could not delete folder.');
        }
    } catch(e) {
        alert('Network error deleting folder.');
    }
}

// Delete File
async function deletePickerFile(mediaId, originalName) {
    if (!confirm(`Delete "${originalName}"?`)) return;

    try {
        const res = await fetch(`/admin/gallery/${mediaId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        });

        const data = await res.json();
        if (data.success) {
            pickerSelectedFiles = pickerSelectedFiles.filter(item => item.id != mediaId);
            updatePickerSelectButton();
            loadPickerExplorer(pickerActiveFolder);
            showPickerToast('✓ Image deleted');
        } else {
            alert(data.message || 'Could not delete file.');
        }
    } catch(e) {
        alert('Network error deleting file.');
    }
}

// Handle Drop or File Picker Upload
async function handlePickerDropFiles(files) {
    if (!files || !files.length) return;

    const progress = document.getElementById('pickerUploadProgress');
    progress.style.display = 'block';
    progress.textContent = `⏳ Converting ${files.length} file(s) to .webp preserving 100% resolution...`;

    const formData = new FormData();
    for (let i = 0; i < files.length; i++) {
        formData.append('files[]', files[i]);
    }
    const uploadFolder = (pickerActiveFolder === 'root' || pickerActiveFolder === 'all') ? 'gallery' : pickerActiveFolder;
    formData.append('folder', uploadFolder);

    try {
        const res = await fetch('{{ route("admin.gallery.upload") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: formData
        });

        const data = await res.json();
        if (data.success) {
            progress.textContent = '✓ ' + data.message;
            setTimeout(() => {
                progress.style.display = 'none';
                loadPickerExplorer(pickerActiveFolder);
            }, 700);
        } else {
            alert('Upload failed: ' + (data.message || 'Unknown error'));
            progress.style.display = 'none';
        }
    } catch(e) {
        alert('Network error uploading files.');
        progress.style.display = 'none';
    }
}
</script>
