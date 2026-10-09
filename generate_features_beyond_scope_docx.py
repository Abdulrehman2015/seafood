import os
import sys
import docx
from docx.shared import Inches, Pt, RGBColor
from docx.enum.text import WD_ALIGN_PARAGRAPH
from docx.enum.table import WD_TABLE_ALIGNMENT, WD_ALIGN_VERTICAL
from docx.oxml import parse_xml, OxmlElement
from docx.oxml.ns import nsdecls, qn

def create_report():
    doc = docx.Document()

    # Page Margins: 0.75 inches
    for section in doc.sections:
        section.top_margin = Inches(0.75)
        section.bottom_margin = Inches(0.75)
        section.left_margin = Inches(0.75)
        section.right_margin = Inches(0.75)

    # Palette
    c_navy      = RGBColor(15, 39, 74)     # #0F274A
    c_blue      = RGBColor(29, 78, 216)    # #1D4ED8
    c_teal      = RGBColor(15, 118, 110)   # #0F766E
    c_slate     = RGBColor(71, 85, 105)    # #475569
    c_charcoal  = RGBColor(30, 41, 59)     # #1E293B
    c_dark      = RGBColor(15, 23, 42)     # #0F172A
    c_green     = RGBColor(22, 101, 52)    # #166534

    # Helper XML functions
    def set_cell_background(cell, fill_hex):
        tcPr = cell._tc.get_or_add_tcPr()
        shd = parse_xml(f'<w:shd {nsdecls("w")} w:fill="{fill_hex}" w:color="auto" w:val="clear"/>')
        tcPr.append(shd)

    def set_cell_margins(cell, top=100, bottom=100, left=140, right=140):
        tcPr = cell._tc.get_or_add_tcPr()
        tcMar = parse_xml(
            f'<w:tcMar {nsdecls("w")}>'
            f'<w:top w:w="{top}" w:type="dxa"/>'
            f'<w:bottom w:w="{bottom}" w:type="dxa"/>'
            f'<w:left w:w="{left}" w:type="dxa"/>'
            f'<w:right w:w="{right}" w:type="dxa"/>'
            f'</w:tcMar>'
        )
        tcPr.append(tcMar)

    def set_table_border(table, color="CBD5E1"):
        tbl = table._tbl
        tblPr = tbl.tblPr
        if tblPr is None:
            tblPr = OxmlElement('w:tblPr')
            tbl.insert(0, tblPr)
        tblBorders = OxmlElement('w:tblBorders')
        for border_name in ('top', 'left', 'bottom', 'right', 'insideH', 'insideV'):
            border = OxmlElement(f'w:{border_name}')
            border.set(qn('w:val'), 'single')
            border.set(qn('w:sz'), '4')
            border.set(qn('w:space'), '0')
            border.set(qn('w:color'), color)
            tblBorders.append(border)
        tblPr.append(tblBorders)

    def add_heading(text, level=1, color=None, space_before=14, space_after=4):
        p = doc.add_paragraph()
        p.paragraph_format.space_before = Pt(space_before)
        p.paragraph_format.space_after = Pt(space_after)
        p.paragraph_format.keep_with_next = True
        run = p.add_run(text)
        run.font.name = "Calibri"
        run.font.bold = True
        if level == 1:
            run.font.size = Pt(13.5)
            run.font.color.rgb = color or c_navy
        elif level == 2:
            run.font.size = Pt(11.5)
            run.font.color.rgb = color or c_blue
        elif level == 3:
            run.font.size = Pt(10.5)
            run.font.color.rgb = color or c_teal
        return p

    def add_body(text, size=9.5, italic=False, bold=False, color=None, space_before=2, space_after=4):
        p = doc.add_paragraph()
        p.paragraph_format.space_before = Pt(space_before)
        p.paragraph_format.space_after = Pt(space_after)
        p.paragraph_format.line_spacing = 1.15
        run = p.add_run(text)
        run.font.name = "Calibri"
        run.font.size = Pt(size)
        run.font.italic = italic
        run.font.bold = bold
        run.font.color.rgb = color or c_charcoal
        return p

    def add_bullet(text, bold_prefix=None, size=9.5, color=None):
        p = doc.add_paragraph(style='List Bullet')
        p.paragraph_format.space_before = Pt(1.5)
        p.paragraph_format.space_after = Pt(2.5)
        p.paragraph_format.left_indent = Inches(0.25)
        p.paragraph_format.line_spacing = 1.15
        if bold_prefix:
            r1 = p.add_run(bold_prefix)
            r1.font.name = "Calibri"
            r1.font.size = Pt(size)
            r1.font.bold = True
            r1.font.color.rgb = c_navy
        r2 = p.add_run(text)
        r2.font.name = "Calibri"
        r2.font.size = Pt(size)
        r2.font.color.rgb = color or c_charcoal
        return p

    def add_callout(text, title=None, fill_hex="EFF6FF", border_hex="3B82F6", text_color=None):
        table = doc.add_table(rows=1, cols=1)
        table.alignment = WD_TABLE_ALIGNMENT.CENTER
        table.autofit = False
        table.columns[0].width = Inches(7.0)

        cell = table.cell(0, 0)
        set_cell_background(cell, fill_hex)
        set_cell_margins(cell, top=100, bottom=100, left=150, right=150)

        tcPr = cell._tc.get_or_add_tcPr()
        tcBorders = parse_xml(
            f'<w:tcBorders {nsdecls("w")}>'
            f'<w:left w:val="single" w:sz="24" w:space="0" w:color="{border_hex}"/>'
            f'<w:top w:val="none"/>'
            f'<w:right w:val="none"/>'
            f'<w:bottom w:val="none"/>'
            f'</w:tcBorders>'
        )
        tcPr.append(tcBorders)

        cp = cell.paragraphs[0]
        cp.paragraph_format.space_before = Pt(2)
        cp.paragraph_format.space_after = Pt(2)
        cp.paragraph_format.line_spacing = 1.15

        if title:
            r_title = cp.add_run(title + "\n")
            r_title.font.name = "Calibri"
            r_title.font.bold = True
            r_title.font.size = Pt(10)
            r_title.font.color.rgb = c_navy

        r_text = cp.add_run(text)
        r_text.font.name = "Calibri"
        r_text.font.size = Pt(9.5)
        r_text.font.color.rgb = text_color or c_charcoal

        doc.add_paragraph().paragraph_format.space_after = Pt(4)

    # ═══════════════════════════════════════════════════════════════════════════
    # 1. HEADER / METADATA BLOCK
    # ═══════════════════════════════════════════════════════════════════════════
    header_table = doc.add_table(rows=1, cols=1)
    header_table.alignment = WD_TABLE_ALIGNMENT.CENTER
    header_table.columns[0].width = Inches(7.0)
    h_cell = header_table.cell(0, 0)
    set_cell_background(h_cell, "0F274A")
    set_cell_margins(h_cell, top=160, bottom=160, left=180, right=180)

    hp = h_cell.paragraphs[0]
    hp.paragraph_format.space_before = Pt(4)
    hp.paragraph_format.space_after = Pt(2)

    r_org = hp.add_run("MST IMPORT AND EXPORT SDN. BHD. (镁嘉国际贸易有限公司)\n")
    r_org.font.name = "Calibri"
    r_org.font.size = Pt(11)
    r_org.font.bold = True
    r_org.font.color.rgb = RGBColor(147, 197, 253)

    r_title = hp.add_run("COMPREHENSIVE CODE AUDIT & SCOPE VARIANCE REPORT\n")
    r_title.font.name = "Calibri"
    r_title.font.size = Pt(15)
    r_title.font.bold = True
    r_title.font.color.rgb = RGBColor(255, 255, 255)

    r_sub = hp.add_run("Detailed Inventory of Production Features Implemented Beyond the Original Scope Specification (.txt)")
    r_sub.font.name = "Calibri"
    r_sub.font.size = Pt(10)
    r_sub.font.italic = True
    r_sub.font.color.rgb = RGBColor(224, 242, 254)

    doc.add_paragraph().paragraph_format.space_after = Pt(6)

    # Metadata Grid
    meta_table = doc.add_table(rows=2, cols=3)
    meta_table.alignment = WD_TABLE_ALIGNMENT.CENTER
    set_table_border(meta_table, "CBD5E1")
    col_widths = [Inches(2.33), Inches(2.33), Inches(2.34)]

    meta_data = [
        [("Document Reference:", "MST-EXT-SCOPE-2026-V1"), ("Date of Audit:", "October 2026"), ("Target Platform:", "Unified B2B & B2C Platform")],
        [("Framework Stack:", "Laravel 11 / PHP / MySQL"), ("Audit Scope:", "SRS (.txt) vs. Production Code"), ("Audit Status:", "Verified & Tested")]
    ]

    for r_idx, row in enumerate(meta_table.rows):
        for c_idx, cell in enumerate(row.cells):
            cell.width = col_widths[c_idx]
            set_cell_background(cell, "F8FAFC" if r_idx == 0 else "FFFFFF")
            set_cell_margins(cell, top=70, bottom=70, left=100, right=100)
            lbl, val = meta_data[r_idx][c_idx]
            p = cell.paragraphs[0]
            p.paragraph_format.space_before = Pt(0)
            p.paragraph_format.space_after = Pt(0)
            r1 = p.add_run(lbl + " ")
            r1.font.name = "Calibri"
            r1.font.size = Pt(8.5)
            r1.font.bold = True
            r1.font.color.rgb = c_slate
            r2 = p.add_run(val)
            r2.font.name = "Calibri"
            r2.font.size = Pt(8.5)
            r2.font.color.rgb = c_dark

    doc.add_paragraph().paragraph_format.space_after = Pt(10)

    # ═══════════════════════════════════════════════════════════════════════════
    # 2. EXECUTIVE SUMMARY
    # ═══════════════════════════════════════════════════════════════════════════
    add_heading("1. Executive Summary & Purpose of this Audit", level=1)
    add_body(
        "This technical document provides an exhaustive, forensic inventory of all software features, architectural subsystems, "
        "and operational capabilities that have been fully developed, tested, and integrated into the MST Seafood e-commerce platform "
        "which were NOT specified in the initial Software Requirements Specification & Project Scope Proposal (.txt)."
    )
    add_body(
        "While the original project specification proposed a standard four-tier e-commerce MVP (Retail, Walk-In, Wholesale, and Trading) "
        "with basic catalog filtering and pricing tiers, the current production codebase has been elevated into an enterprise-grade, "
        "cross-border cold-chain distribution platform. Over 25 substantial modules and operational subsystems were created beyond the contract text "
        "to satisfy real-world cold-chain logistics, regional compliance, fraud prevention, multi-currency trade, and trilingual business operations."
    )

    add_callout(
        "Key Finding: The original contract proposal accounted for standard retail/wholesale checkout mechanics. In contrast, "
        "the delivered codebase encompasses a complete Cold-Chain Logistics Engine (with exact postcodes & RM 150/RM 350 thresholds), "
        "Trilingual Language Switching & Translation CMS (EN/ZH/BM), Multi-Currency Live Exchange Rates (MYR/SGD/USD), "
        "Email OTP Anti-Brute-Force Verification, Catch-Weight Seafood Pricing, SSM & Corporate Deduplication, Media Asset Management, "
        "and Web-Based Database Administration.",
        title="Executive Summary Takeaway",
        fill_hex="F0FDF4",
        border_hex="16A34A"
    )

    # ═══════════════════════════════════════════════════════════════════════════
    # 3. DETAILED DOMAIN BREAKDOWN
    # ═══════════════════════════════════════════════════════════════════════════
    add_heading("2. Detailed Inventory of Implemented Features Beyond Scope", level=1)

    # Domain 1
    add_heading("2.1. Delivery Zone Management & Cold-Chain Logistics Routing", level=2)
    add_body(
        "The original scope document stated only: 'Flexible Fulfillment Rules: Option selection between Delivery and Self-Collection'. "
        "The actual implementation is a full logistics routing platform featuring:"
    )
    add_bullet("Dedicated MySQL storage managing geographic areas, postcodes, fee tiers, and customer group eligibility.", bold_prefix="Delivery Zones Architecture: ")
    add_bullet("Engineered in DeliveryService.php and DeliveryZone.php with exact match, wildcard prefix matching (e.g., '79*', '80*'), and area/state fallback matching.", bold_prefix="Postcode Wildcard Matching Engine: ")
    add_bullet("Strict segregation between local Zone A (Johor Bahru, Iskandar Puteri, Nusajaya: 79xxx, 80xxx), Zone B (Skudai 81300), and Outstation West Malaysia zones.", bold_prefix="Geographic Routing Rules: ")
    add_bullet("Each delivery zone has independent toggles for B2C Retail (is_b2c_enabled), B2B Wholesale (is_b2b_enabled), and Trading (is_trading_enabled).", bold_prefix="Tier-Level Zone Authorization: ")
    add_bullet("Dedicated back-office CRUD module (Admin/DeliveryZoneController.php) for fee management, sort ordering, threshold updates, and status toggling.", bold_prefix="Admin Delivery Zone Workbench: ")
    add_bullet("High-speed API endpoint (/api/calculate-delivery-fee) dynamically called by frontend checkout with debounced keystroke listeners.", bold_prefix="Live Recalculation API: ")
    add_bullet("Orders outside standard local coverage trigger an automated notice banner with a direct, pre-filled WhatsApp link (wa.me) for Styrofoam box sizing and freight confirmation.", bold_prefix="Outstation Cold-Chain WhatsApp Protocol: ")

    # Domain 2
    add_heading("2.2. Tiered Free Delivery Thresholds & Visual Cart Gamification", level=2)
    add_body("The .txt document made zero mention of delivery thresholds, order value minimums, or free delivery incentives.")
    add_bullet("System settings define independent thresholds: RM 150.00 for B2C Retail customers and RM 350.00 for B2B Wholesale accounts.", bold_prefix="Independent B2C & B2B Thresholds: ")
    add_bullet("Orders under the threshold are automatically assessed a below_threshold_fee (e.g., RM 15–20), while qualifying orders waive the fee entirely.", bold_prefix="Automatic Fee Exemption Logic: ")
    add_bullet("Dynamic progress tracker in cart/index.blade.php calculating remaining spend in real-time (e.g., 'Add RM 42.50 more for Free Standard Delivery!') with an animated percentage bar.", bold_prefix="Visual Cart Gamification Progress Bar: ")
    add_bullet("Store self-collection and Walk-in counter purchases are hard-coded to 100% free (RM 0.00), exempt from all delivery thresholds.", bold_prefix="Zero-Fee Self-Collection Guarantee: ")

    # Domain 3
    add_heading("2.3. Multi-Currency Engine & Automated Live Exchange Rates (MYR, SGD, USD)", level=2)
    add_body("The proposal only anticipated domestic Malaysian Ringgit (MYR). The production codebase is multi-currency ready for international trade:")
    add_bullet("Full multi-currency switching across Malaysian Ringgit (MYR), Singapore Dollar (SGD), and US Dollar (USD) via CurrencyService.php and CurrencyController.php.", bold_prefix="Trilingual Multi-Currency Architecture: ")
    add_bullet("Integrates with the Open Exchange Rates API (open.er-api.com) to automatically synchronize live FX rates against MYR with 6-hour caching.", bold_prefix="Automated Live API Rate Synchronization: ")
    add_bullet("Admins can override automated rates with fixed values (currency_manual_rate_sgd, currency_manual_rate_usd) or toggle between manual and auto-convert modes.", bold_prefix="Admin Manual Exchange Controls: ")
    add_bullet("Specific products can have fixed prices set in SGD and USD for Retail, Wholesale, and Trading tiers (e.g., wholesale_price_sgd, trading_price_usd) in Product.php.", bold_prefix="Product-Level Currency Overrides: ")
    add_bullet("Transparent notices informing Singapore and overseas clients that SGD/USD rates are indicative, and legal base settlement occurs in MYR.", bold_prefix="Base Settlement Currency Advisory: ")

    # Domain 4
    add_heading("2.4. Trilingual Localization (i18n) & Dynamic Translation CMS", level=2)
    add_body("The original specification document did not require multi-language capabilities.")
    add_bullet("Comprehensive support for English (EN), Simplified Chinese (ZH - 简体中文), and Bahasa Melayu (BM / MS).", bold_prefix="Three Fully Integrated Locales: ")
    add_bullet("Automated prefix routing (/en/..., /zh/..., /bm/...) with intelligent fallback, cookie/session negotiation, and zero-redirect root homepage logic.", bold_prefix="URL-Based Locale Negotiation: ")
    add_bullet("Database schemas feature multi-language fields for products (name_zh, name_bm, description_zh, description_bm, short_description_zh, etc.) and categories.", bold_prefix="Localized Model Attributes: ")
    add_bullet("Packaging units (kg -> 公斤 / kg, carton -> 箱 / karton, pack -> 包 / pek) and country origins (Norway -> 挪威, etc.) auto-translate based on the active locale.", bold_prefix="Automated Attribute Localization: ")
    add_bullet("Built into Admin/TranslationController.php allowing administrators to search, edit, create translation keys, scan source Blade files, and clear cache.", bold_prefix="Admin Translation Management CMS: ")

    # Domain 5
    add_heading("2.5. Email OTP (One-Time Password) Verification & Anti-Abuse Security", level=2)
    add_body("The proposal only required simple notification emails upon administrative account activation.")
    add_bullet("Cryptographically secure 6-digit email verification codes generated and dispatched upon customer registration via OtpVerificationController.php.", bold_prefix="6-Digit Email OTP Verification: ")
    add_bullet("10-minute expiry window, maximum 3 verification attempts per code, and a strict limit of 1 resend.", bold_prefix="Anti-Brute Force Protection: ")
    add_bullet("Users exceeding failed attempt limits have their accounts automatically locked (email_otp_blocked_at) to protect the platform from spam bots.", bold_prefix="Automatic Security Account Lockout: ")
    add_bullet("Admins can review blocked applicants and execute a 1-click unblock & manual verification action in the back-office.", bold_prefix="Admin Unblock & Verification Tool: ")
    add_bullet("Verification displays mask recipient email addresses (e.g., j***@example.com) to comply with data privacy standards.", bold_prefix="Masked Privacy Display: ")

    # Domain 6
    add_heading("2.6. Corporate Onboarding Intelligence & SSM Duplicate Prevention", level=2)
    add_body("The scope proposal outlined collecting basic business numbers. The production system adds intelligent fraud prevention:")
    add_bullet("CompanyVerificationService.php validates Malaysian SSM and Singapore UEN numbers to prevent duplicate enterprise registrations.", bold_prefix="Strict SSM / Registration Number Uniqueness: ")
    add_bullet("Strips corporate suffixes ('Sdn Bhd', 'Pte Ltd', 'Enterprise', 'Trading') and runs Levenshtein distance calculations to alert users if a sister account exists.", bold_prefix="Fuzzy Corporate Name Similarity Matching: ")
    add_bullet("Live AJAX endpoint (/api/verify-registration-field) alerts users in real time while typing if an SSM or company name is taken.", bold_prefix="Real-Time Registration Field Verification: ")
    add_bullet("Admin Customer dashboard includes automated algorithms flagging duplicate phone numbers, duplicate company names, and duplicate registration IDs with a quick filter.", bold_prefix="Admin Duplicate Customer Detector: ")

    # Domain 7
    add_heading("2.7. Specialized Seafood Catch-Weight / Variable-Weight Pricing", level=2)
    add_body("Frozen and fresh seafood often varies in weight per piece. The .txt only mentioned standard fixed specifications.")
    add_bullet("Items like live mud crabs, whole fish, or bulk fillets can be designated as variable_weight in Product.php.", bold_prefix="Variable-Weight Model Flag: ")
    add_bullet("Product records store reference_weight (e.g. ±800g), actual_weight_unit, and unit_price_per_weight.", bold_prefix="Reference vs. Actual Weight Schema: ")
    add_bullet("Clear customer disclaimers on Product details, Cart, and Checkout stating that displayed prices are based on reference weights, and final billing reflects Actual Final Weight upon weighing.", bold_prefix="Legal Billing & Weighing Notices: ")

    # Domain 8
    add_heading("2.8. Fulfillment Scheduling, Time Slots & Guest Order Reconciliation", level=2)
    add_body("The .txt briefly mentioned pickup scheduling. The production implementation is far more granular:")
    add_bullet("Self-collection customers select between 4 designated collection windows: Morning (8:30–10:30 AM), Midday (10:30 AM–12:30 PM), Afternoon (1:30–3:30 PM), and Late Afternoon (3:30–5:30 PM).", bold_prefix="Granular Collection Time Slots: ")
    add_bullet("Enforces minimum lead times (minimum 3 business days, with an advisory note explaining up to 7 working days for cold-chain sourcing and logistics).", bold_prefix="Delivery Lead-Time Scheduling: ")
    add_bullet("In Admin/OrderController.php, admins set confirmed delivery/collection dates/times, record notes, and trigger an automated OrderScheduleNotification email to the client.", bold_prefix="Admin Schedule Confirmation & Email Trigger: ")
    add_bullet("Order::linkGuestOrdersToUser() automatically scans past guest checkouts matching an email or mobile number when a customer registers or logs in, instantly associating past invoices.", bold_prefix="Past Guest Order Auto-Reconciliation: ")

    # Domain 9
    add_heading("2.9. Content Management Systems (CMS): Policies, Page SEO & Sitemap", level=2)
    add_body("The scope proposal did not encompass CMS capabilities for legal policies or SEO governance.")
    add_bullet("Dedicated database table (Policy.php) and management interface (Admin/PolicyController.php) for Privacy Policy, Terms & Conditions, Refund Policy (12-hour perishable food rule), Cold-Chain Shipping Policy, and Cookie Policy in 3 languages.", bold_prefix="Dynamic Legal Policies CMS: ")
    add_bullet("Built into PageSeo.php and Admin/PageSeoController.php to configure Meta Title, Meta Description, Meta Keywords, Open Graph Images, Canonical URLs, and JSON-LD Schema markup per page.", bold_prefix="Page-Level SEO & Open Graph CMS: ")
    add_bullet("Public dynamic sitemap (/sitemap.xml) featuring Google-compliant xhtml:link alternate hreflang tags for EN, ZH, and BM, with an admin manager to upload custom sitemaps or auto-generate.", bold_prefix="Multilingual XML Sitemap Engine: ")

    # Domain 10
    add_heading("2.10. Compliance, Marketing & Customer Engagement Subsystems", level=2)
    add_body("Several compliance and customer retention tools were built that were absent from the scope:")
    add_bullet("Complete interactive banner in cookie-banner.blade.php with a modal Preference Center allowing visitors to toggle Essential, Functional, and Analytics cookies.", bold_prefix="PDPA / GDPR Cookie Consent Center: ")
    add_bullet("Separate, unbundled marketing consent checkboxes on registration forms for WhatsApp broadcasts versus Email newsletters.", bold_prefix="Granular Marketing Consents: ")
    add_bullet("Complete moderation platform in Admin/ReviewController.php for customer star ratings, approval/rejection workflows, homepage featured flags, and avatar generation.", bold_prefix="Customer Reviews & Testimonials Module: ")
    add_bullet("Public subscription widget with rate limiting and an Admin Newsletter dashboard featuring subscriber tracking, active/unsubscribed toggling, and 1-click CSV export.", bold_prefix="Newsletter Subscription & CSV Export: ")
    add_bullet("Inquiries submitted via the Contact form are logged to ContactMessage.php with IP logging, unread badges, and an admin management dashboard.", bold_prefix="Contact Inquiries Inbox: ")

    # Domain 11
    add_heading("2.11. Digital Asset Management (DAM) & Media Gallery", level=2)
    add_body("The proposal only envisioned attaching photos to products. The system includes a full digital asset manager:")
    add_bullet("Full-featured media repository in Admin/GalleryController.php and Media.php.", bold_prefix="Central Media Library: ")
    add_bullet("Drag-and-drop multi-upload, folder hierarchy creation, folder renaming, folder ZIP downloads, and moving files across folders.", bold_prefix="Folder Hierarchies & Batch Downloads: ")
    add_bullet("ImageUploadService.php automatically generates modern WebP images, extracts image dimensions, and produces high-speed thumbnails.", bold_prefix="Automated WebP Conversion & Optimization: ")
    add_bullet("In-browser image cropping tool, media renaming, and a reusable modal API (/admin/gallery/api) integrated into product and category edit forms.", bold_prefix="In-Browser Cropping & Media Picker: ")

    # Domain 12
    add_heading("2.12. Database Maintenance, Infrastructure & Diagnostic Workbenches", level=2)
    add_body("Crucial operational tools were implemented for enterprise maintenance:")
    add_bullet("Admins can download .sql database dumps on-demand (mysqldump with pure PHP PDO fallback), upload backups, and restore snapshots from Admin/DatabaseController.php.", bold_prefix="1-Click Database Backup & Restore: ")
    add_bullet("Admins can trigger php artisan migrate and db:seed directly from the web GUI without requiring terminal SSH access.", bold_prefix="Web-Based Database Migrations & Seeders: ")
    add_bullet("Admin/EmailTemplateController.php renders all transactional emails (order receipts, approval alerts, schedules) with realistic mock data in the browser and sends test emails.", bold_prefix="Live Email Template Previewer: ")
    add_bullet("Settings interface includes real-time diagnostics to test outbound SMTP handshake and verify Stripe Publishable/Secret keys without placing real transactions.", bold_prefix="Outbound SMTP & Stripe Credential Testers: ")
    add_bullet("Custom routes in routes/web.php (/cdn-assets/css/..., /cdn-assets/js/..., /cdn-assets/img/...) deliver GZIP-compressed assets with immutable cache headers and ETags to achieve Pingdom 100 speed scores without third-party CDN costs.", bold_prefix="Cookie-Free High-Performance CDN Delivery: ")
    add_bullet("Interactive Leaflet.js maps on the Contact page proxy and cache OpenStreetMap tiles locally (/map-tile/{z}/{x}/{y}) to eliminate external API dependencies and ad-blocker blocks.", bold_prefix="Cached Local Map Tile Proxy: ")
    add_bullet("InvoiceHelper.php automatically converts numerical order totals into legal Malaysian Ringgit words (e.g. 'RINGGIT MALAYSIA TWO HUNDRED FIFTY AND CENTS EIGHTY ONLY') on printable invoices.", bold_prefix="Commercial Invoice Legal Amount in Words: ")

    # ═══════════════════════════════════════════════════════════════════════════
    # 4. MASTER COMPARISON TABLE
    # ═══════════════════════════════════════════════════════════════════════════
    add_heading("3. Master Scope Variance Matrix (SRS .txt vs. Codebase)", level=1)
    add_body("The table below compares the original scope specifications with the features implemented in the codebase:")

    table_data = [
        ("Feature / Capability", "In Scope .txt?", "Implemented in Code?", "Code Artifacts & Implementation Details"),
        ("Delivery Zones (Zone A, B, Outstation)", "NO", "YES", "DeliveryZone.php, DeliveryService.php, DeliveryZoneController.php"),
        ("Postcode Wildcard Matching Engine", "NO", "YES", "DeliveryZone::matchesLocation() (exact, '79*', '80*')"),
        ("Tiered Free Delivery Thresholds (RM 150/350)", "NO", "YES", "Setting.php, cart/index.blade.php, DeliveryService.php"),
        ("Visual Cart Delivery Progress Bar", "NO", "YES", "cart/index.blade.php (dynamic JS + threshold tracker)"),
        ("Multi-Currency (MYR, SGD, USD)", "NO", "YES", "CurrencyService.php, CurrencyController.php"),
        ("Automated Live FX Rate API Synchronization", "NO", "YES", "CurrencyService::getRates() via open.er-api.com"),
        ("Trilingual Localization (EN, ZH, BM)", "NO", "YES", "TranslationService.php, routes/web.php, TranslationController.php"),
        ("Admin Translation Management CMS", "NO", "YES", "Admin/TranslationController.php (database translations table)"),
        ("Email OTP Verification (6-digit)", "NO", "YES", "OtpVerificationController.php, SendEmailOtp.php"),
        ("OTP Brute-Force Lockout & Admin Unblock", "NO", "YES", "User::blockUserForOtpFailure(), Admin/CustomerController.php"),
        ("SSM / UEN Uniqueness Verification", "NO", "YES", "CompanyVerificationService::checkSsmUniqueness()"),
        ("Fuzzy Company Name Similarity Matching", "NO", "YES", "CompanyVerificationService::checkSimilarity() (Levenshtein)"),
        ("Variable-Weight / Catch-Weight Pricing", "NO", "YES", "Product.php (pricing_model, reference_weight, notices)"),
        ("Granular Self-Collection Time Slots (4 Slots)", "NO", "YES", "checkout/index.blade.php, walkin/checkout.blade.php"),
        ("Delivery Lead-Time Scheduling (7-Day Notice)", "NO", "YES", "checkout/index.blade.php, OrderController::notifySchedule()"),
        ("Admin Schedule Confirmation Email Trigger", "NO", "YES", "OrderScheduleNotification.php, Admin/OrderController.php"),
        ("Automatic Past Guest Order Linking", "NO", "YES", "Order::linkGuestOrdersToUser() (links email/phone)"),
        ("Dynamic Legal Policies CMS (5 Policies)", "NO", "YES", "Policy.php, Admin/PolicyController.php"),
        ("PDPA / GDPR Cookie Consent & Preference Modal", "NO", "YES", "partials/cookie-banner.blade.php"),
        ("Customer Reviews & Moderation Module", "NO", "YES", "Review.php, Admin/ReviewController.php"),
        ("Newsletter Subscription & CSV Export", "NO", "YES", "NewsletterSubscriber.php, Admin/NewsletterController.php"),
        ("Contact Inquiries Inbox & Status Tracking", "NO", "YES", "ContactMessage.php, Admin/MessageController.php"),
        ("Media Library (DAM) with Folders & Cropping", "NO", "YES", "Media.php, MediaFolder.php, Admin/GalleryController.php"),
        ("Web-Based Database Backup & Restore (.sql)", "NO", "YES", "DatabaseManagerService.php, Admin/DatabaseController.php"),
        ("Web-Based Artisan Migrate & Seed", "NO", "YES", "Admin/DatabaseController::runMigrations(), runSeeders()"),
        ("Dynamic Multilingual XML Sitemap (Hreflang)", "NO", "YES", "SitemapController.php, Admin/SitemapController.php"),
        ("Page-Level SEO & Social Graph (OG) CMS", "NO", "YES", "PageSeo.php, Admin/PageSeoController.php"),
        ("Live Email Template Previewer Workbench", "NO", "YES", "Admin/EmailTemplateController.php"),
        ("Live Outbound SMTP & Stripe Testers", "NO", "YES", "Admin/SettingController::testEmail(), testStripe()"),
        ("Cookie-Free High-Performance CDN Routes", "NO", "YES", "routes/web.php (/cdn-assets/css, js, img with GZIP)"),
        ("Local Cached OpenStreetMap Tile Proxy", "NO", "YES", "routes/web.php (/map-tile/{z}/{x}/{y} with Leaflet.js)"),
        ("Invoice Legal Amount in Words Generator", "NO", "YES", "InvoiceHelper::amountInWords() (Ringgit text)")
    ]

    comp_table = doc.add_table(rows=len(table_data), cols=4)
    comp_table.alignment = WD_TABLE_ALIGNMENT.CENTER
    set_table_border(comp_table, "CBD5E1")
    t_widths = [Inches(2.2), Inches(0.8), Inches(0.9), Inches(3.1)]

    for r_idx, row_values in enumerate(table_data):
        row = comp_table.rows[r_idx]
        is_head = (r_idx == 0)
        for c_idx, val in enumerate(row_values):
            cell = row.cells[c_idx]
            cell.width = t_widths[c_idx]
            cell.vertical_alignment = WD_ALIGN_VERTICAL.CENTER

            if is_head:
                set_cell_background(cell, "0F274A")
                set_cell_margins(cell, top=100, bottom=100, left=100, right=100)
            else:
                bg = "F8FAFC" if (r_idx % 2 == 1) else "FFFFFF"
                set_cell_background(cell, bg)
                set_cell_margins(cell, top=60, bottom=60, left=90, right=90)

            p = cell.paragraphs[0]
            p.paragraph_format.space_before = Pt(0)
            p.paragraph_format.space_after = Pt(0)
            p.paragraph_format.line_spacing = 1.1

            run = p.add_run(val)
            run.font.name = "Calibri"

            if is_head:
                run.font.bold = True
                run.font.size = Pt(8.5)
                run.font.color.rgb = RGBColor(255, 255, 255)
            else:
                run.font.size = Pt(8.0)
                if c_idx == 1:
                    run.font.bold = True
                    run.font.color.rgb = RGBColor(220, 38, 38) if val == "NO" else c_charcoal
                    p.alignment = WD_ALIGN_PARAGRAPH.CENTER
                elif c_idx == 2:
                    run.font.bold = True
                    run.font.color.rgb = RGBColor(22, 101, 52) if val == "YES" else c_charcoal
                    p.alignment = WD_ALIGN_PARAGRAPH.CENTER
                elif c_idx == 0:
                    run.font.bold = True
                    run.font.color.rgb = c_navy
                else:
                    run.font.color.rgb = c_charcoal

    doc.add_paragraph().paragraph_format.space_after = Pt(12)

    # ═══════════════════════════════════════════════════════════════════════════
    # 5. COMMERCIAL & STRATEGIC VALUE CONCLUSION
    # ═══════════════════════════════════════════════════════════════════════════
    add_heading("4. Strategic & Commercial Impact of Extended Features", level=1)
    add_body(
        "The inclusion of these extended features provides MST with immense commercial, operational, and technical advantages "
        "that far surpass the capabilities of a basic e-commerce website:"
    )

    add_bullet(
        "By enforcing Zone A boundaries and the RM 150 / RM 350 thresholds, MST prevents logistics losses on cold-chain packaging and freight, "
        "while the automated WhatsApp protocol ensures outstation orders are profitable before dispatch.",
        bold_prefix="Cold-Chain Cost Protection: "
    )
    add_bullet(
        "Multi-currency support (SGD/USD) combined with trilingual localization (EN/ZH/BM) positions MST as a credible regional food distributor, "
        "enabling seamless trade with restaurant chains, hotels, and wholesalers in Singapore and international markets.",
        bold_prefix="Cross-Border Regional Expansion: "
    )
    add_bullet(
        "SSM uniqueness verification, corporate fuzzy matching, and 6-digit Email OTP safeguard MST against competitor price-scraping, "
        "fake business accounts, and automated registration spam.",
        bold_prefix="Account Integrity & Margin Security: "
    )
    add_bullet(
        "Catch-weight pricing eliminates billing disputes on natural seafood variations, and the custom cutting disclaimer protects kitchen staff "
        "by ensuring preparation occurs only after payment confirmation.",
        bold_prefix="Commercial Dispute Elimination: "
    )
    add_bullet(
        "The embedded Media Library, Database Maintenance suite, Translation Editor, Legal Policy CMS, and Email Previewer allow MST's internal team "
        "to manage the platform autonomously without recurring external developer expenses.",
        bold_prefix="Zero-Dependency Self-Sufficiency: "
    )

    # Output file
    output_path = "f:\\My AI\\Sea Food\\seafood\\MST_Features_Implemented_Beyond_Scope_Specification_Report.docx"
    doc.save(output_path)
    print(f"Document successfully created at: {output_path}")

if __name__ == "__main__":
    create_report()
