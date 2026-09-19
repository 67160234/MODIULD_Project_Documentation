import os

files_to_patch = [
    "frontend/create-loadout.html",
    "frontend/workspace.html",
    "frontend/modules/asset-tracking.html",
    "frontend/modules/profile-management.html",
    "frontend/modules/queueing.html",
    "frontend/modules/room-booking.html",
    "frontend/modules/seat-allocation.html",
    "frontend/modules/team-schedule.html",
    "frontend/modules/work-tracking.html"
]

base_dir = r"C:\Users\Lenovo\Downloads\MODIULD_Project_Documentation\modiuld"

for file_path in files_to_patch:
    full_path = os.path.join(base_dir, file_path)
    if not os.path.exists(full_path):
        print(f"Not found: {full_path}")
        continue
        
    with open(full_path, 'r', encoding='utf-8') as f:
        content = f.read()
        
    # 1. Update html tag
    content = content.replace('<html lang="en" data-theme="dark">', '<html lang="en" class="theme-default" data-theme="default">')
    
    # 2. Add scripts if not present
    if "theme.js" not in content:
        content = content.replace('<script src="/frontend/js/auth.js?v=3" defer></script>',
                                  '<script src="/frontend/js/theme.js?v=3" defer></script>\n<script src="/frontend/js/auth.js?v=3" defer></script>')
    
    if "version.js" not in content:
        content = content.replace('<script src="/frontend/js/auth.js?v=3" defer></script>',
                                  '<script src="/frontend/js/auth.js?v=3" defer></script>\n<script src="/frontend/js/version.js?v=3" defer></script>')
                                  
    # 3. Add version badge
    if "version-badge" not in content:
        content = content.replace('</body>', '<div id="version-badge" class="version-badge"></div>\n</body>')
        
    with open(full_path, 'w', encoding='utf-8') as f:
        f.write(content)
        
    print(f"Patched: {file_path}")
