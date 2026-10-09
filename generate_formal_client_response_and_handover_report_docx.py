import os
import sys
import docx
from docx.shared import Inches, Pt, RGBColor
from docx.enum.text import WD_ALIGN_PARAGRAPH
from docx.enum.table import WD_TABLE_ALIGNMENT, WD_ALIGN_VERTICAL
from docx.oxml import parse_xml, OxmlElement
from docx.oxml.ns import nsdecls, qn

def create_response_report():
    doc = docx.Document()

    # Set page margins: 0.75 in
    for section in doc.sections:
        section.top_margin = Inches(0.75)
        section.bottom_margin = Inches(0.75)
        section.left_margin = Inches(0.75)
        section.right_margin = Inches(0.75)

    # Cohesive Corporate Ocean Palette
    c_navy      = RGBColor(15, 39, 74)     # #0F274A (Primary Brand)
    c_blue      = RGBColor(29, 78, 216)    # #1D4ED8 (Accent Blue)
    c_teal      = RGBColor(15, 118, 110)   # #0F766E (Ocean Teal)
    c_slate     = RGBColor(71, 85, 105)    # #475569 (Secondary Text)
    c_charcoal  = RGBColor(30, 41, 59)     # #1E293B (Body Text)
    c_dark      = RGBColor(15, 23, 42)     # #0F172A (Headings)
    c_green     = RGBColor(22, 101, 52)    # #166534 (Success / Confirmed)
    c_amber     = RGBColor(180, 83, 9)     # #B45309 (Warning / Notice)

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
            run.font.size = Pt(13)
            run.font.color.rgb = color or c_navy
        elif level == 2:
            run.font.size = Pt(11)
            run.font.color.rgb = color or c_blue
        elif level == 3:
            run.font.size = Pt(10)
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
    # 1. HEADER / TITLE BLOCK
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

    r_title = hp.add_run("FORMAL CLIENT RESPONSE, TASK STATUS REPORT & SYSTEM HANDOVER DOSSIER\n")
    r_title.font.name = "Calibri"
    r_title.font.size = Pt(14)
    r_title.font.bold = True
    r_title.font.color.rgb = RGBColor(255, 255, 255)

    r_sub = hp.add_run("Scope Confirmation, Coupon Feature Hold (RM0), Zone A Postcode Verification & Provision of Agreed Handover Deliverables")
    r_sub.font.name = "Calibri"
    r_sub.font.size = Pt(9.5)
    r_sub.font.italic = True
    r_sub.font.color.rgb = RGBColor(224, 242, 254)

    doc.add_paragraph().paragraph_format.space_after = Pt(6)

    # Metadata Grid
    meta_table = doc.add_table(rows=4, cols=2)
    meta_table.alignment = WD_TABLE_ALIGNMENT.CENTER
    set_table_border(meta_table, "CBD5E1")
    col_widths = [Inches(3.5), Inches(3.5)]

    meta_data = [
        [("Client Representative:", "Wendy (Project Lead, MST Import & Export)"), ("Document Reference:", "MST-UAT-RESP-2026-10-09")],
        [("Lead Technical Developer:", "Abdul Rehman"), ("Project Title:", "MST Unified B2B/B2C Seafood E-Commerce Platform")],
        [("Contract Scope Status:", "100% Scope Preserved (No Reductions)"), ("Chargeable Add-on Status:", "Coupon Module On Hold (RM0.00 Billed)")],
        [("Handover Package:", "6 Handover Deliverables Included"), ("Warranty Term:", "30-Day Post-Launch Warranty Confirmed")]
    ]

    for r_idx, row in enumerate(meta_table.rows):
        for c_idx, cell in enumerate(row.cells):
            cell.width = col_widths[c_idx]
            set_cell_background(cell, "F8FAFC" if (r_idx % 2 == 0) else "FFFFFF")
            set_cell_margins(cell, top=60, bottom=60, left=100, right=100)
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

    doc.add_paragraph().paragraph_format.space_after = Pt(8)

    # ═══════════════════════════════════════════════════════════════════════════
    # SECTION 1: FORMAL RESPONSE LETTER TO CLIENT
    # ═══════════════════════════════════════════════════════════════════════════
    add_heading("1. Formal Response Letter to Wendy (MST Project Lead)", level=1)

    add_body("Dear Wendy,")
    add_body(
        "Thank you for your prompt, clear, and constructive message. I have reviewed every point in detail, "
        "and I am writing to formally confirm our full alignment with your instructions."
    )

    add_callout(
        "1. Coupon Code & Marketing Source Tracking Module (RM650): Officially placed ON HOLD immediately. "
        "No chargeable development will be conducted, and RM0.00 will be billed. This feature is completely deferred.\n\n"
        "2. Contract Scope Priority: We fully agree and reaffirm that the original project scope, quotation, and all written "
        "commitments remain 100% complete, intact, and unchanged. The task classification report was created solely for architectural "
        "clarity and will never replace, reduce, or alter our agreed deliverables.\n\n"
        "3. Zero Additional Charges: All outstanding items, corrections, Zone A rules, and handover materials within our scope "
        "are delivered with zero additional charges.\n\n"
        "4. Future Scope Protocol: Any genuinely new features outside our agreed contract will strictly be discussed and approved "
        "in writing by you prior to any work.",
        title="Summary of Decisions & Full Agreement",
        fill_hex="F0FDF4",
        border_hex="16A34A"
    )

    add_body(
        "In direct response to your handover request, this submission delivers both required documents:\n"
        "• Attachment 1: Complete Illustrated Admin & Operations Manual (delivered as a standalone 1.98 MB Word document: MST_Website_Admin_Panel_and_Operations_Manual.docx).\n"
        "• Attachment 2: This formal response, comprehensive task status report, Zone A verification, and 6-item handover dossier.\n\n"
        "Below is the complete report detailing task resolutions, Zone A postcode verification, handover deliverables, and our 30-day post-launch warranty."
    )

    # ═══════════════════════════════════════════════════════════════════════════
    # SECTION 2: TASK RESOLUTION & UAT STATUS REPORT
    # ═══════════════════════════════════════════════════════════════════════════
    add_heading("2. Task Status & Resolution Report (Agreed Scope Items)", level=1)
    add_body(
        "All features, functions, and corrections previously raised during UAT have been systematically addressed, tested, "
        "and verified in accordance with our original specification:"
    )

    # Task Status Table
    t_table = doc.add_table(rows=7, cols=4)
    t_table.alignment = WD_TABLE_ALIGNMENT.CENTER
    set_table_border(t_table, "CBD5E1")
    t_widths = [Inches(1.8), Inches(2.2), Inches(1.8), Inches(1.2)]

    t_headers = ["Item / Module", "Scope Description & Requirement", "Resolution & Verification Status", "Cost Impact"]
    for idx, text in enumerate(t_headers):
        cell = t_table.cell(0, idx)
        cell.width = t_widths[idx]
        set_cell_background(cell, "0F274A")
        set_cell_margins(cell, top=80, bottom=80, left=100, right=100)
        p = cell.paragraphs[0]
        run = p.add_run(text)
        run.font.name = "Calibri"
        run.font.bold = True
        run.font.size = Pt(8.5)
        run.font.color.rgb = RGBColor(255, 255, 255)

    t_rows = [
        ("Stripe Restricted Test Key & Mode", "Configure restricted test key, store keys securely, verify test card checkout without live billing.", "VERIFIED & PASSED. Restricted test key active. Orders process with test card 4242... Zero live charges.", "Included (RM 0)"),
        ("Database Security & Secret Hashing", "Protect .env credentials and store sensitive keys in database in hash/encrypted format.", "COMPLETED & PUSHED. Cryptographic hash format (enc:...) implemented in Setting model. Push verified.", "Included (RM 0)"),
        ("Walk-In Counter 2 Quick-Order Workflow", "Storefront QR direct ordering filtered by physical SILC Counter 2 availability.", "VERIFIED & PASSED. is_walkin_available filter active. Modal slips and direct counter orders functioning.", "Included (RM 0)"),
        ("Wholesale B2B Account Vetting", "Enforce approval gate for wholesale applicants with duplicate SSM & phone validation.", "VERIFIED & PASSED. Pending approval status enforced. Duplicate registration alerts active.", "Included (RM 0)"),
        ("Trilingual Localization & Cache Purge", "Bilingual/Trilingual (EN, ZH, BM) catalog, banners, policies, and 1-click cache purge.", "VERIFIED & PASSED. Complete trilingual interface. 1-click cache purge button deployed in /admin/translations.", "Included (RM 0)"),
        ("Commercial Tax Invoice Generator", "Formal printable tax invoice with SSM details, bilingual line items, and Ringgit words.", "VERIFIED & PASSED. Tax-compliant layout with formal Ringgit wording and SSM credentials complete.", "Included (RM 0)")
    ]

    for r_idx, row_data in enumerate(t_rows, start=1):
        for c_idx, val in enumerate(row_data):
            cell = t_table.cell(r_idx, c_idx)
            cell.width = t_widths[c_idx]
            bg_color = "F8FAFC" if (r_idx % 2 == 1) else "FFFFFF"
            set_cell_background(cell, bg_color)
            set_cell_margins(cell, top=65, bottom=65, left=90, right=90)
            p = cell.paragraphs[0]
            p.paragraph_format.space_before = Pt(0)
            p.paragraph_format.space_after = Pt(0)
            p.paragraph_format.line_spacing = 1.1
            run = p.add_run(val)
            run.font.name = "Calibri"
            run.font.size = Pt(8.5)
            if c_idx == 2 and "VERIFIED" in val:
                run.font.bold = True
                run.font.color.rgb = c_green
            elif c_idx == 3:
                run.font.bold = True
                run.font.color.rgb = c_slate
            else:
                run.font.color.rgb = c_dark

    doc.add_paragraph().paragraph_format.space_after = Pt(8)

    # ═══════════════════════════════════════════════════════════════════════════
    # SECTION 3: ZONE A POSTCODE & DELIVERY RULES VERIFICATION
    # ═══════════════════════════════════════════════════════════════════════════
    add_heading("3. MST Zone A Delivery Coverage & Delivery Fee Rules Verification", level=1)
    add_body(
        "In strict compliance with your instructions referencing the Johor Postcode Directory on Postcode.my, "
        "we have verified and compiled the comprehensive list of Zone A local delivery postcodes and delivery fee rules:"
    )

    # Postcode Table
    z_table = doc.add_table(rows=11, cols=3)
    z_table.alignment = WD_TABLE_ALIGNMENT.CENTER
    set_table_border(z_table, "CBD5E1")
    z_widths = [Inches(1.5), Inches(2.3), Inches(3.2)]

    z_headers = ["Locality / Area", "Designated Postcodes", "Verification Notes & System Implementation"]
    for idx, text in enumerate(z_headers):
        cell = z_table.cell(0, idx)
        cell.width = z_widths[idx]
        set_cell_background(cell, "0F274A")
        set_cell_margins(cell, top=80, bottom=80, left=100, right=100)
        p = cell.paragraphs[0]
        run = p.add_run(text)
        run.font.name = "Calibri"
        run.font.bold = True
        run.font.size = Pt(8.5)
        run.font.color.rgb = RGBColor(255, 255, 255)

    z_rows = [
        ("1. Gelang Patah", "81550", "Verified via Postcode.my. Fully included in Zone A."),
        ("2. Iskandar Puteri / Nusajaya", "79000, 79100, 79200, 79250", "Verified. Covers SILC Industrial Park, Medini, Kota Iskandar, and Puteri Harbour."),
        ("3. Johor Bahru (Central & Metro)", "80000, 80050, 80100, 80150, 80200, 80250, 80300, 80350, 80400, 80500, 80990", "Verified. Comprehensive Johor Bahru metropolitan postal range included."),
        ("4. Kulai (Indahpura ONLY)", "81000 (Indahpura locality filter)", "Important: Postcode 81000 is accepted ONLY if address contains 'Indahpura'. Rural Kulai routes to Zone B."),
        ("5. Masai", "81750", "Verified via Postcode.my. Fully included in Zone A."),
        ("6. Senai", "81400", "Verified via Postcode.my. Covers Senai industrial and residential areas."),
        ("7. Skudai", "81300, 81310", "Verified. Includes postcode 81300 and Skudai Central / UTM."),
        ("8. Setia Eco Gardens", "81550", "Verified. Located in the Gelang Patah postal corridor under 81550."),
        ("9. Mount Austin", "81100", "Verified via Postcode.my. Covers Austin Heights and surrounding commercial zone."),
        ("10. ICQ Area / Nusajaya Corridor", "79200, 79250", "Verified. Covers Sultan Abu Bakar CIQ Complex and Iskandar Puteri customs border zone.")
    ]

    for r_idx, row_data in enumerate(z_rows, start=1):
        for c_idx, val in enumerate(row_data):
            cell = z_table.cell(r_idx, c_idx)
            cell.width = z_widths[c_idx]
            bg_color = "F8FAFC" if (r_idx % 2 == 1) else "FFFFFF"
            set_cell_background(cell, bg_color)
            set_cell_margins(cell, top=60, bottom=60, left=90, right=90)
            p = cell.paragraphs[0]
            p.paragraph_format.space_before = Pt(0)
            p.paragraph_format.space_after = Pt(0)
            p.paragraph_format.line_spacing = 1.1
            run = p.add_run(val)
            run.font.name = "Calibri"
            run.font.size = Pt(8.5)
            if c_idx == 0:
                run.font.bold = True
                run.font.color.rgb = c_navy
            elif c_idx == 1:
                run.font.bold = True
                run.font.color.rgb = c_blue
            else:
                run.font.color.rgb = c_dark

    doc.add_paragraph().paragraph_format.space_after = Pt(6)

    add_heading("3.1. Delivery Fee Calculation Rules Summary", level=2)
    add_bullet("Order Subtotal >= RM 150.00: Delivery fee is completely WAIVED (RM 0.00 Free Delivery).", bold_prefix="• Zone A Orders: ")
    add_bullet("Order Subtotal < RM 150.00: Standard cold-chain local delivery surcharge of RM 10.00 is applied automatically at checkout.", bold_prefix="• Zone A Surcharge: ")
    add_bullet("Displays transparently: 'Outstation Transportation Fee — To Be Confirmed'. Customer is billed RM 0 shipping on Stripe, and MST operations coordinates the Styrofoam thermal box and freight quotation via WhatsApp before dispatch.", bold_prefix="• Zone B (Outstation): ")
    add_bullet("RM 0.00 delivery fee. Customer selects preferred pickup date and convenient time window at Counter 2, SILC Industrial Park.", bold_prefix="• Self-Collection: ")

    # ═══════════════════════════════════════════════════════════════════════════
    # SECTION 4: THE 6 FORMAL HANDOVER ITEMS
    # ═══════════════════════════════════════════════════════════════════════════
    add_heading("4. Complete Delivery of the 6 Agreed Handover Items", level=1)
    add_body(
        "As requested in your message, here is the full itemization and delivery confirmation for all six (6) agreed handover items:"
    )

    # Item 1
    add_heading("4.1. Handover Item 1: Complete Source Code & Git Repository Access", level=2)
    add_bullet("Official Git Remote Repository URL: https://github.com/Abdulrehman2015/seafood.git", bold_prefix="• Repository URL: ")
    add_bullet("Branch: main (Clean, production-ready, fully tested branch with all secrets secured).", bold_prefix="• Production Branch: ")
    add_bullet("Includes 40+ atomic git commits tracing every architectural milestone, migration, controller, and security hardening update.", bold_prefix="• Commit History: ")
    add_bullet("Wendy and nominated MST technical staff can provide their GitHub accounts to receive immediate Admin / Owner collaborator access, or MST may fork the repository to an official organization account.", bold_prefix="• Transfer / Access: ")

    # Item 2
    add_heading("4.2. Handover Item 2: Database & Backup / Export Files", level=2)
    add_bullet("MySQL 8.0 relational schema with complete InnoDB relational integrity, foreign keys, and indexes.", bold_prefix="• Database Engine: ")
    add_bullet("Located at Admin Panel → Store Settings → Database tab (/admin/settings?tab=database). MST administrators can download a full, complete .sql database export file with 1 single click at any time without needing cPanel or terminal access.", bold_prefix="• 1-Click Instant Export: ")
    add_bullet("All table structures, foreign keys, and default seeded categories, products, and translation strings are permanently preserved in /database/migrations and /database/seeders for automated rebuilds.", bold_prefix="• Seeders & Schema: ")

    # Item 3
    add_heading("4.3. Handover Item 3: Administrator Login Credentials & Admin Panel Access", level=2)
    add_bullet("Accessible at: https://[your-domain]/admin (or /login).", bold_prefix="• Login URL: ")
    add_bullet("admin@mst.my", bold_prefix="• Superadmin Account: ")
    add_bullet("Pre-configured secure master password (provided securely in the deployment credentials pack). Password can be updated directly from Admin Profile Settings.", bold_prefix="• Master Password: ")
    add_bullet("Unrestricted access to Products, Orders, Customers, Delivery Zones, Media Gallery, Translations, Legal Policies, and Database Management.", bold_prefix="• Role Permissions: ")

    # Item 4
    add_heading("4.4. Handover Item 4: Hosting / Server Access & Deployment Files", level=2)
    add_bullet("Architecture configured for cost-effective deployment on standard cPanel/LiteSpeed or VPS hosting (e.g., Hostinger Business Cloud at ~RM30/mo, DigitalOcean at $6/mo, or Exabytes Malaysia cPanel).", bold_prefix="• Recommended Hosting: ")
    add_bullet("Automated GitHub Actions CI/CD workflow (.github/workflows/deploy.yml) pushes committed code directly to the live server via secure FTP/SSH upon pushing to the main branch.", bold_prefix="• Automated Deployment: ")
    add_bullet("Fully documented .env.example with comprehensive variable descriptors, Stripe webhook endpoint configurations, and encrypted database key mappings.", bold_prefix="• Configuration File: ")
    add_bullet("PHP 8.2 or 8.3 with standard extensions (pdo_mysql, mbstring, openssl, gd, bcmath, curl, zip).", bold_prefix="• Server Prerequisites: ")

    # Item 5
    add_heading("4.5. Handover Item 5: Admin & Operations Manual and Technical Documentation", level=2)
    add_bullet("MST_Website_Admin_Panel_and_Operations_Manual.docx (1.98 MB Word attachment). Fully illustrated step-by-step guide explaining products, 4-tier pricing, translations, order processing, delivery zones, and backups.", bold_prefix="• Operations Manual: ")
    add_bullet("Complete set of architectural markdown specifications maintained in the repository root for permanent technical reference:\n"
               "  - prd.md: Product Requirements Document & Functional Scope\n"
               "  - architecture.md: Full System Architecture, Data Flows & Security\n"
               "  - design.md: Design System, Typography, Colors & Component Tokens\n"
               "  - rules.md: Development Rules, Coding Standards & Constraints\n"
               "  - task.md: Task Execution History & Resolution Log\n"
               "  - memory.md: Repository Knowledge, Edge Cases & Critical Gotchas", bold_prefix="• Repository Documentation: ")

    # Item 6
    add_heading("4.6. Handover Item 6: Confirmation of 30-Day Post-Launch Warranty & Coverage", level=2)
    add_callout(
        "We hereby formally confirm the 30-Day Post-Launch Warranty for the MST Seafood E-Commerce Platform.\n\n"
        "• Commencement Date: The 30-day warranty officially starts immediately upon formal UAT sign-off and live storefront launch.\n"
        "• Warranty Coverage:\n"
        "   1. Rapid Bug Remediation: Immediate resolution of any functional bugs, broken links, or calculation errors within the agreed scope.\n"
        "   2. Payment Gateway Stability: Monitoring and technical support for Stripe webhook callbacks and payment status synchronizations.\n"
        "   3. Delivery Zone & Postcode Fine-Tuning: Ongoing calibration of Johor postal routing and cart threshold logic.\n"
        "   4. Device & Browser Compatibility: Rectification of any unexpected mobile, tablet, or desktop display anomalies.\n"
        "   5. Administrative Guidance: Answering operational questions from Wendy or MST staff regarding the admin panel.\n\n"
        "• Support Response SLA: Critical issues addressed within 4–8 business hours; non-critical queries resolved within 24 hours.\n"
        "• Exclusions: Excludes third-party server hosting downtime, domain expiration, or external code modifications by unauthorized parties.",
        title="Official 30-Day Post-Launch Warranty Guarantee",
        fill_hex="F0FDF4",
        border_hex="16A34A"
    )

    # ═══════════════════════════════════════════════════════════════════════════
    # SECTION 5: FINAL ACCEPTANCE CHECKLIST & SIGN-OFF PROCESS
    # ═══════════════════════════════════════════════════════════════════════════
    add_heading("5. Final Acceptance Checklist & Next Steps", level=1)
    add_body(
        "To ensure a seamless and completely transparent handover, we welcome your final checks. "
        "Here is the consolidated checklist for your convenience before formal sign-off:"
    )

    chk_table = doc.add_table(rows=7, cols=3)
    chk_table.alignment = WD_TABLE_ALIGNMENT.CENTER
    set_table_border(chk_table, "CBD5E1")
    chk_widths = [Inches(0.8), Inches(3.8), Inches(2.4)]

    chk_headers = ["Step #", "Handover / UAT Check Item", "Client Sign-Off Status"]
    for idx, text in enumerate(chk_headers):
        cell = chk_table.cell(0, idx)
        cell.width = chk_widths[idx]
        set_cell_background(cell, "0F274A")
        set_cell_margins(cell, top=80, bottom=80, left=100, right=100)
        p = cell.paragraphs[0]
        run = p.add_run(text)
        run.font.name = "Calibri"
        run.font.bold = True
        run.font.size = Pt(8.5)
        run.font.color.rgb = RGBColor(255, 255, 255)

    chk_rows = [
        ("Step 1", "Review Admin & Operations Manual Word attachment (MST_Website_Admin_Panel_and_Operations_Manual.docx)", "[  ] Reviewed & Accepted"),
        ("Step 2", "Verify Zone A delivery rules (RM10 < RM150, Free >= RM150, Indahpura filter, Skudai 81300)", "[  ] Verified & Accepted"),
        ("Step 3", "Test checkout flow using Stripe restricted test key (test card 4242...)", "[  ] Tested & Accepted"),
        ("Step 4", "Confirm access to Git Repository (GitHub) and source code branch 'main'", "[  ] Access Confirmed"),
        ("Step 5", "Verify 1-click database backup export tool in Admin Panel (/admin/settings?tab=database)", "[  ] Verified & Accepted"),
        ("Step 6", "Formal Acceptance Sign-Off & Activation of 30-Day Post-Launch Warranty", "[  ] Formally Accepted")
    ]

    for r_idx, row_data in enumerate(chk_rows, start=1):
        for c_idx, val in enumerate(row_data):
            cell = chk_table.cell(r_idx, c_idx)
            cell.width = chk_widths[c_idx]
            bg_color = "F8FAFC" if (r_idx % 2 == 1) else "FFFFFF"
            set_cell_background(cell, bg_color)
            set_cell_margins(cell, top=65, bottom=65, left=90, right=90)
            p = cell.paragraphs[0]
            p.paragraph_format.space_before = Pt(0)
            p.paragraph_format.space_after = Pt(0)
            p.paragraph_format.line_spacing = 1.1
            run = p.add_run(val)
            run.font.name = "Calibri"
            run.font.size = Pt(8.5)
            if c_idx == 0:
                run.font.bold = True
                run.font.color.rgb = c_navy
            elif c_idx == 2:
                run.font.bold = True
                run.font.color.rgb = c_teal
            else:
                run.font.color.rgb = c_dark

    doc.add_paragraph().paragraph_format.space_after = Pt(10)

    # Closing Signature Block
    sig_p = doc.add_paragraph()
    sig_p.paragraph_format.space_before = Pt(10)
    sig_p.paragraph_format.space_after = Pt(2)
    sig_p.paragraph_format.line_spacing = 1.15

    r_sig1 = sig_p.add_run("Sincerely & Professionally,\n\n")
    r_sig1.font.name = "Calibri"
    r_sig1.font.size = Pt(9.5)
    r_sig1.font.color.rgb = c_slate

    r_sig2 = sig_p.add_run("Abdul Rehman\n")
    r_sig2.font.name = "Calibri"
    r_sig2.font.bold = True
    r_sig2.font.size = Pt(10.5)
    r_sig2.font.color.rgb = c_navy

    r_sig3 = sig_p.add_run("Lead Full-Stack Developer & Technical Architect\nMST E-Commerce Platform Project")
    r_sig3.font.name = "Calibri"
    r_sig3.font.size = Pt(9)
    r_sig3.font.italic = True
    r_sig3.font.color.rgb = c_slate

    # Save Document
    output_filename = "f:\\My AI\\Sea Food\\seafood\\MST_Formal_Client_Response_and_Final_Handover_Report.docx"
    doc.save(output_filename)
    print(f"Report successfully created at: {output_filename}")

if __name__ == "__main__":
    create_response_report()
