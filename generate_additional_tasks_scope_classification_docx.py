import os
import sys
import docx
from docx.shared import Inches, Pt, RGBColor
from docx.enum.text import WD_ALIGN_PARAGRAPH
from docx.enum.table import WD_TABLE_ALIGNMENT, WD_ALIGN_VERTICAL
from docx.oxml import parse_xml, OxmlElement
from docx.oxml.ns import nsdecls, qn

def create_document():
    doc = docx.Document()

    # Page Margins: 0.75 in top/bottom, 0.75 in left/right
    for section in doc.sections:
        section.top_margin = Inches(0.75)
        section.bottom_margin = Inches(0.75)
        section.left_margin = Inches(0.75)
        section.right_margin = Inches(0.75)

    # Color Palette
    c_navy      = RGBColor(15, 39, 74)     # #0F274A (Primary Brand)
    c_blue      = RGBColor(29, 78, 216)    # #1D4ED8 (Accent Blue)
    c_teal      = RGBColor(15, 118, 110)   # #0F766E (Ocean Teal)
    c_slate     = RGBColor(71, 85, 105)    # #475569 (Secondary Text)
    c_charcoal  = RGBColor(30, 41, 59)     # #1E293B (Body Text)
    c_dark      = RGBColor(15, 23, 42)     # #0F172A (Headings)
    c_green     = RGBColor(22, 101, 52)    # #166534 (Success / Free Value-Add)
    c_amber     = RGBColor(180, 83, 9)     # #B45309 (Warning / Notice)
    c_purple    = RGBColor(109, 40, 217)   # #6D28D9 (Quoted / Optional Enhancements)

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

    r_title = hp.add_run("COMPREHENSIVE ADDITIONAL TASKS CLASSIFICATION,\nVALUE-ADDED DELIVERABLES & FINAL HANDOVER ROADMAP\n")
    r_title.font.name = "Calibri"
    r_title.font.size = Pt(14)
    r_title.font.bold = True
    r_title.font.color.rgb = RGBColor(255, 255, 255)

    r_sub = hp.add_run("Formal Categorization of Free Value-Added Features vs. Optional Quoted Enhancements, Zone A Status & Final UAT Alignment")
    r_sub.font.name = "Calibri"
    r_sub.font.size = Pt(9.5)
    r_sub.font.italic = True
    r_sub.font.color.rgb = RGBColor(224, 242, 254)

    doc.add_paragraph().paragraph_format.space_after = Pt(6)

    # Metadata Grid
    meta_table = doc.add_table(rows=3, cols=2)
    meta_table.alignment = WD_TABLE_ALIGNMENT.CENTER
    set_table_border(meta_table, "CBD5E1")
    col_widths = [Inches(3.5), Inches(3.5)]

    meta_data = [
        [("Addressed To:", "Wendy (Client Project Lead) & MST Management"), ("Prepared By:", "Abdul Rehman (Lead Full-Stack Developer & Technical Architect)")],
        [("Document Reference:", "MST-UAT-TASK-CLASSIFICATION-2026-V1"), ("Date of Issuance:", "October 9, 2026")],
        [("Platform Engine:", "Laravel Unified B2B & B2C Cold-Chain E-Commerce"), ("Handover Phase:", "Final Acceptance & UAT Readiness")]
    ]

    for r_idx, row in enumerate(meta_table.rows):
        for c_idx, cell in enumerate(row.cells):
            cell.width = col_widths[c_idx]
            set_cell_background(cell, "F8FAFC" if (r_idx % 2 == 0) else "FFFFFF")
            set_cell_margins(cell, top=65, bottom=65, left=100, right=100)
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

    # Executive Opening Callout
    add_callout(
        "Dear Wendy,\n\n"
        "Thank you very much for your encouraging message, your kind acknowledgment of the effort and extra time invested into the MST platform, "
        "and your clear guidance regarding our immediate priorities.\n\n"
        "I completely agree with your suggested approach: our immediate focus is 100% on completing the agreed website corrections, verifying the Zone A "
        "postcode coverage, and ensuring that your administration manual and handover documentation are thorough and complete.\n\n"
        "In direct response to your request, this document provides the complete, itemized breakdown distinguishing between:\n"
        "1. Agreed Scope & System Corrections (Fully completed within the agreed contract);\n"
        "2. Value-Added Extended Features delivered at NO ADDITIONAL CHARGE (RM 0.00 complimentary value-add);\n"
        "3. Optional Future Enhancements requiring separate development fees (such as the coupon code and marketing attribution module, currently deferred for post-launch review).\n\n"
        "I deeply appreciate your thoughtfulness regarding the appreciation tip upon successful UAT testing and final review.",
        title="Executive Opening & Sincere Acknowledgment",
        fill_hex="F0FDF4",
        border_hex="16A34A"
    )

    # ═══════════════════════════════════════════════════════════════════════════
    # 2. IMMEDIATE FOCUS: CURRENT WORK STATUS & UAT VERIFICATION
    # ═══════════════════════════════════════════════════════════════════════════
    add_heading("1. Immediate Focus: Status of Agreed Corrections & Zone A Verification", level=1)
    add_body(
        "Per your instructions ('For now, let's focus on completing the agreed website corrections, verifying the Zone A postcode list, "
        "and ensuring that the admin manual and handover documentation are complete'), here is the verified status of each priority item:"
    )

    add_bullet("All 10 target coverage areas (Gelang Patah, Iskandar Puteri, JB Core, Kulai Indahpura only, Masai, Senai, Skudai 81300, Setia Eco Gardens, Mount Austin, and ICQ Second Link) have been compiled from Postcode.my, configured in the database, and deployed to the live server.", bold_prefix="1. Zone A Postcode List Alignment: ")
    add_bullet("Postcode 81000 is strictly evaluated: only addresses specifying 'Indahpura' qualify for Zone A local delivery. General/rural Kulai is systematically routed to Outstation / Zone B cold-chain logistics.", bold_prefix="2. Kulai (Indahpura Only) Rule: ")
    add_bullet("The previous code restriction on postcode 81300 has been completely removed in both DeliveryService.php and DeliveryZone.php. Postcode 81300 is now officially recognized as Zone A.", bold_prefix="3. Skudai (81300) Integration: ")
    add_bullet("Zone A orders below RM150 carry the RM10 fee; orders of RM150 and above receive RM0 free delivery. Outstation orders display 'Outstation Transportation Fee — To Be Confirmed' with zero pre-billing to Stripe. Self-collection is 100% free with address fields hidden.", bold_prefix="4. Delivery Fee Rule Integrity: ")
    add_bullet("The comprehensive, illustrated step-by-step Admin and Operations Manual has been fully written, reviewed, and supplied in your documentation package.", bold_prefix="5. Admin Manual & Handover Documentation: ")

    # Postcode Verification Summary Table
    add_heading("Zone A Verified Postcode Summary Table (Ready for Your Review)", level=2)
    add_body("Below is the concise directory of postcodes and sub-localities configured for Zone A based on Postcode.my:")

    postcode_table_data = [
        ("Area #", "Target Area Name", "Official Postcode(s)", "Key Included Localities & Sub-Districts", "Verification Status"),
        ("1", "Gelang Patah", "81550, 79200", "Gelang Patah Town, Nusajaya Industrial Park, SILC Vicinity", "Verified & Deployed"),
        ("2", "Iskandar Puteri / Nusajaya", "79000, 79100, 79200, 79250, 79500", "Kota Iskandar, Medini, Puteri Harbour, East Ledang, Horizon Hills, Eco Botanic", "Verified & Deployed"),
        ("3", "Johor Bahru Core", "80000, 80050, 80100, 80150, 80200, 80250, 80300, 80350, 80400, 80500, 80550, 80600, 80650, 80700, 80710, 80720, 80730, 80800, 80990, 81200", "JB City Centre, Larkin, Tampoi, Danga Bay, Pelangi, Sentosa, Perling, Bukit Indah", "Verified & Deployed"),
        ("4", "Kulai (Indahpura Only)", "81000", "Bandar Indahpura, AEON Kulai vicinity, Indahpura Industrial Park (Rural Kulai excluded)", "Verified & Deployed (Strict Check)"),
        ("5", "Masai", "81750", "Masai Town, Bandar Seri Alam, Plentong, Kota Puteri, Taman Megah Ria", "Verified & Deployed"),
        ("6", "Senai", "81400", "Senai Town, Senai Airport City, Senai Industrial Park I-IV", "Verified & Deployed"),
        ("7", "Skudai (incl. 81300)", "81300", "Skudai Town, Taman Universiti, Mutiara Rini, Tun Aminah, Sutera Utama", "Verified & Deployed"),
        ("8", "Setia Eco Gardens", "81550", "Setia Eco Gardens, Eco Village, Gelang Patah Southern Corridor", "Verified & Deployed"),
        ("9", "Mount Austin", "81100", "Taman Mount Austin, Austin Heights, Austin Perdana, Austin Duta, Taman Daya", "Verified & Deployed"),
        ("10", "Confirmed ICQ Area (Iskandar Puteri)", "79000, 79100, 79200, 79250, 79500", "Kompleks Sultan Abu Bakar (Second Link CIQ), Tanjung Kupang, Puteri Harbour Ferry Terminal", "Verified & Deployed")
    ]

    p_table = doc.add_table(rows=len(postcode_table_data), cols=5)
    p_table.alignment = WD_TABLE_ALIGNMENT.CENTER
    set_table_border(p_table, "CBD5E1")
    p_widths = [Inches(0.6), Inches(1.5), Inches(1.4), Inches(2.3), Inches(1.2)]

    for r_idx, row in enumerate(p_table.rows):
        is_head = (r_idx == 0)
        for c_idx, val in enumerate(postcode_table_data[r_idx]):
            cell = row.cells[c_idx]
            cell.width = p_widths[c_idx]
            cell.vertical_alignment = WD_ALIGN_VERTICAL.CENTER

            if is_head:
                set_cell_background(cell, "0F274A")
                set_cell_margins(cell, top=80, bottom=80, left=80, right=80)
            else:
                bg = "F8FAFC" if (r_idx % 2 == 1) else "FFFFFF"
                set_cell_background(cell, bg)
                set_cell_margins(cell, top=50, bottom=50, left=70, right=70)

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
                if c_idx == 0 or c_idx == 4:
                    p.alignment = WD_ALIGN_PARAGRAPH.CENTER
                    if c_idx == 4:
                        run.font.bold = True
                        run.font.color.rgb = c_green
                elif c_idx == 1:
                    run.font.bold = True
                    run.font.color.rgb = c_navy
                else:
                    run.font.color.rgb = c_charcoal

    doc.add_paragraph().paragraph_format.space_after = Pt(8)

    # ═══════════════════════════════════════════════════════════════════════════
    # 3. COMPLETE TASK CLASSIFICATION (CATEGORIES A, B, C)
    # ═══════════════════════════════════════════════════════════════════════════
    add_heading("2. Comprehensive Task Classification & Commercial Transparency", level=1)
    add_body(
        "To provide 100% clarity as requested, all project features and additional tasks are organized into three clear categories:"
    )

    # ───────────────────────────────────────────────────────────────────────────
    # CATEGORY A
    # ───────────────────────────────────────────────────────────────────────────
    add_heading("Category A: Baseline Contract Scope & UAT Corrections (100% Completed — Included in Agreed Project)", level=2, color=c_navy)
    add_body("These deliverables constitute the core platform architecture agreed in our initial contract, fully implemented and tested:")
    add_bullet("Complete customer role segregation across Retail, Walk-In, Wholesale, and Trading tiers with zero price leakage across tiers.", bold_prefix="1. 4-Tier Customer Roles & Access: ")
    add_bullet("Dedicated walk-in catalog filtering with instant collection token generation for storefront counter pickup.", bold_prefix="2. Walk-In Store QR Code Flow: ")
    add_bullet("Dedicated B2B application forms for Wholesale and Trading tiers with back-office Approve/Reject workbench.", bold_prefix="3. Customer Vetting & Approval Workflow: ")
    add_bullet("Item-level and category-level minimum purchase quantities strictly enforced during cart progression for Wholesale and Trading.", bold_prefix="4. Tier-Based MOQ Enforcement: ")
    add_bullet("Quotation cart, sales workbench for custom pricing and quote-to-order conversion for high-volume trading accounts.", bold_prefix="5. RFQ Quotation System: ")
    add_bullet("Official Stripe hosted checkout integration for card payments, plus instant cash order processing for counter walk-ins.", bold_prefix="6. Secure Payment Gateway: ")
    add_bullet("Fulfillment status tracking (Pending, Confirmed, Shipped, Delivered, Collected), historical invoices, and 1-click re-order.", bold_prefix="7. Orders & Customer Dashboard: ")
    add_bullet("Correction of all feedback items from UAT testing, ensuring seamless checkout operation.", bold_prefix="8. UAT Corrections & Bug-Fixing: ")

    # ───────────────────────────────────────────────────────────────────────────
    # CATEGORY B
    # ───────────────────────────────────────────────────────────────────────────
    add_heading("Category B: Additional Tasks Completed at NO EXTRA CHARGE (Complimentary Value-Add — RM 0.00)", level=2, color=c_green)
    add_body(
        "To ensure the MST platform operates at an enterprise, cross-border standard, the following 10 significant subsystems and tasks "
        "were implemented beyond the baseline proposal. These have been provided to you at NO ADDITIONAL DEVELOPMENT CHARGE:"
    )

    add_bullet("Granular 5-digit postal routing, strict Indahpura validation for Kulai, removal of the Skudai 81300 restriction, and unified cart/checkout API parity.", bold_prefix="1. Postcode.my Johor Postal Directory Integration: ")
    add_bullet("Full trilingual switching across English, Simplified Chinese (简体中文), and Bahasa Melayu (BM), URL locale prefixing (/en, /zh, /bm), and a dedicated back-office Translation CMS (/admin/translations).", bold_prefix="2. Trilingual Localization Engine (EN / ZH / BM): ")
    add_bullet("Multi-currency conversion across MYR, SGD, and USD with automated live Open Exchange Rates API synchronization, plus manual admin override controls.", bold_prefix="3. Multi-Currency Live FX Engine (MYR / SGD / USD): ")
    add_bullet("Cryptographically secure 6-digit email verification codes with anti-brute-force lockout and administrative unblock controls.", bold_prefix="4. Email OTP Anti-Abuse Security System: ")
    add_bullet("Database schemas and UI mechanisms supporting random-weight seafood items priced per kilogram or specified weight bands.", bold_prefix="5. Catch-Weight / Variable Seafood Pricing Support: ")
    add_bullet("SSM business registration number format validation and real-time duplicate phone/SSM alerts during B2B account onboarding.", bold_prefix="6. Corporate SSM Verification & Duplicate Detection: ")
    add_bullet("Centralized media library (/admin/gallery) supporting folder organization, image optimization, and direct media picker during product creation.", bold_prefix="7. Visual Media Asset Gallery Manager: ")
    add_bullet("Back-office workbench (/admin/database) allowing administrators to download complete, self-contained .sql database dumps in 1 click.", bold_prefix="8. 1-Click Database Backup Manager: ")
    add_bullet("Administrative feature to record confirmed delivery dates and time slots, automatically dispatching branded schedule confirmation emails to customers.", bold_prefix="9. Delivery Schedule Dispatch Notification System: ")
    add_bullet("Exhaustive, step-by-step documentation with screenshots covering all administrative tasks and operations.", bold_prefix="10. Complete Illustrated Admin & Operations Manual: ")

    add_callout(
        "Commercial Clarification: The 10 subsystems in Category B represent significant architectural enhancements. "
        "As part of my commitment to delivering a truly exceptional, robust platform for MST, these features are provided "
        "to you at RM 0.00 additional development cost.",
        title="Zero Extra Charge Confirmation (Category B)",
        fill_hex="F0FDF4",
        border_hex="16A34A"
    )

    # ───────────────────────────────────────────────────────────────────────────
    # CATEGORY C
    # ───────────────────────────────────────────────────────────────────────────
    add_heading("Category C: Optional Future Features Requiring Separate Development Fee (Quoted for Future Consideration)", level=2, color=c_purple)
    add_body(
        "As requested, the items below are features that would require separate development fees. These are completely optional "
        "and will be held for future consideration after primary UAT sign-off:"
    )

    add_bullet("Allows customers to enter coupon codes at checkout (e.g., FBMST10, WAMST10, TIKTOK10, REFMST10). Features include percentage/fixed discounts, minimum spend thresholds, usage limits, date windows, and tracking marketing attribution (Facebook, WhatsApp, TikTok, Referral Partner) in order logs and admin reports. Quoted Investment: RM 650.00 (Timeline: 3–5 days). Status: On hold per Wendy's instructions to focus on final UAT first.", bold_prefix="1. Coupon Code & Marketing Source Tracking Module: ")
    add_bullet("Direct integration with Malaysian online banking gateways (e.g. ToyyibPay, SenangPay, or Curlec FPX) if MST wishes to offer direct FPX payment alongside Stripe credit/debit card processing. Quoted separately upon request.", bold_prefix="2. Direct Local FPX Payment Gateway Integration: ")
    add_bullet("Automated dispatch of delivery updates and quotation receipts via official WhatsApp Cloud API without manual wa.me links. Quoted separately upon request.", bold_prefix="3. Automated WhatsApp Business Cloud API Integration: ")

    # Summary Comparison Table
    add_heading("Task Categorization & Financial Overview", level=2)
    summary_data = [
        ("Task / Module Name", "Category", "Development Fee", "Current Status"),
        ("4-Tier Roles & Multi-Tier Pricing (Retail/Walk-in/B2B/Trading)", "Category A: Contract Scope", "Included in Project", "Completed & Live"),
        ("Walk-in QR Code Engine & Express Counter Pickup", "Category A: Contract Scope", "Included in Project", "Completed & Live"),
        ("B2B Customer Registration & Approval Workbench", "Category A: Contract Scope", "Included in Project", "Completed & Live"),
        ("Tier-Based MOQ Enforcement & Dynamic Cart Rules", "Category A: Contract Scope", "Included in Project", "Completed & Live"),
        ("RFQ Quotation Workbench & Quote-to-Order Conversion", "Category A: Contract Scope", "Included in Project", "Completed & Live"),
        ("Stripe Hosted Checkout & Immediate Cash Payment", "Category A: Contract Scope", "Included in Project", "Completed & Live"),
        ("Order Fulfillment & Historical Invoicing Dashboard", "Category A: Contract Scope", "Included in Project", "Completed & Live"),
        ("UAT Delivery & Checkout Corrections", "Category A: Contract Scope", "Included in Project", "Completed & Live"),
        ("Postcode.my Johor Postal Directory Integration (10 Areas)", "Category B: Free Value-Add", "RM 0.00 (No Charge)", "Completed & Live"),
        ("Kulai (Indahpura Only) Strict Locality Validation", "Category B: Free Value-Add", "RM 0.00 (No Charge)", "Completed & Live"),
        ("Skudai (81300) Zone A Local Recognition", "Category B: Free Value-Add", "RM 0.00 (No Charge)", "Completed & Live"),
        ("Trilingual Localization Engine (EN / ZH 简体中文 / BM)", "Category B: Free Value-Add", "RM 0.00 (No Charge)", "Completed & Live"),
        ("Multi-Currency Live FX Engine (MYR / SGD / USD)", "Category B: Free Value-Add", "RM 0.00 (No Charge)", "Completed & Live"),
        ("Email OTP Verification & Anti-Brute-Force Security", "Category B: Free Value-Add", "RM 0.00 (No Charge)", "Completed & Live"),
        ("Catch-Weight / Variable Seafood Pricing Support", "Category B: Free Value-Add", "RM 0.00 (No Charge)", "Completed & Live"),
        ("Corporate SSM Verification & Duplicate Detection", "Category B: Free Value-Add", "RM 0.00 (No Charge)", "Completed & Live"),
        ("Visual Media Asset Gallery Manager (/admin/gallery)", "Category B: Free Value-Add", "RM 0.00 (No Charge)", "Completed & Live"),
        ("1-Click Database Backup Manager (/admin/database)", "Category B: Free Value-Add", "RM 0.00 (No Charge)", "Completed & Live"),
        ("Delivery Schedule Email Notification Dispatch", "Category B: Free Value-Add", "RM 0.00 (No Charge)", "Completed & Live"),
        ("Complete Illustrated Admin & Operations Manual", "Category B: Free Value-Add", "RM 0.00 (No Charge)", "Completed & Supplied"),
        ("Coupon Code & Marketing Source Tracking Module", "Category C: Quoted Add-On", "RM 650.00 (Optional)", "Deferred for Post-Launch Review")
    ]

    s_table = doc.add_table(rows=len(summary_data), cols=4)
    s_table.alignment = WD_TABLE_ALIGNMENT.CENTER
    set_table_border(s_table, "CBD5E1")
    s_widths = [Inches(2.8), Inches(1.8), Inches(1.3), Inches(1.1)]

    for r_idx, row in enumerate(s_table.rows):
        is_head = (r_idx == 0)
        for c_idx, val in enumerate(summary_data[r_idx]):
            cell = row.cells[c_idx]
            cell.width = s_widths[c_idx]
            cell.vertical_alignment = WD_ALIGN_VERTICAL.CENTER

            if is_head:
                set_cell_background(cell, "0F274A")
                set_cell_margins(cell, top=80, bottom=80, left=80, right=80)
            else:
                bg = "F8FAFC" if (r_idx % 2 == 1) else "FFFFFF"
                set_cell_background(cell, bg)
                set_cell_margins(cell, top=45, bottom=45, left=60, right=60)

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
                if c_idx == 2:
                    run.font.bold = True
                    if "No Charge" in val or "Included" in val:
                        run.font.color.rgb = c_green
                    else:
                        run.font.color.rgb = c_purple
                elif c_idx == 3:
                    run.font.bold = True
                    run.font.color.rgb = c_green if "Completed" in val else c_slate
                elif c_idx == 1:
                    run.font.color.rgb = c_blue if "Category B" in val else (c_navy if "Category A" in val else c_purple)
                else:
                    run.font.color.rgb = c_dark

    doc.add_paragraph().paragraph_format.space_after = Pt(8)

    # ═══════════════════════════════════════════════════════════════════════════
    # 4. STEP-BY-STEP UAT TESTING PROTOCOL FOR WENDY
    # ═══════════════════════════════════════════════════════════════════════════
    add_heading("3. Recommended UAT Testing Protocol for Final Acceptance", level=1)
    add_body(
        "To facilitate your final testing, here is a concise testing guide to verify the primary operational scenarios:"
    )

    add_bullet("Add products totaling below RM150 (e.g. RM90) to the cart. Enter postcode 79100 (Iskandar Puteri) or 81300 (Skudai). Result: Zone A is recognized, and an itemized RM10 delivery fee is applied.", bold_prefix="Test Case 1 — Zone A Delivery (< RM150): ")
    add_bullet("Increase cart total to RM150 or above (e.g. RM160). Result: The system unlocks Free Standard Delivery (RM0.00) automatically.", bold_prefix="Test Case 2 — Free Delivery Waiver (≥ RM150): ")
    add_bullet("Enter postcode 81000 with city 'Indahpura'. Result: Matched to Zone A local delivery. Enter postcode 81000 with general city 'Kulai'. Result: Correctly classified as Outstation cold-chain delivery.", bold_prefix="Test Case 3 — Kulai Indahpura vs. Rural Kulai: ")
    add_bullet("Enter an Outstation postcode (e.g. 83000 Batu Pahat or 84000 Muar). Result: Banner displays 'Outstation Transportation Fee — To Be Confirmed' with WhatsApp coordination link; exactly RM0.00 added to Stripe.", bold_prefix="Test Case 4 — Outstation Cold-Chain Handling: ")
    add_bullet("Select 'Store Self-Collection'. Result: Street address fields hide automatically; collection date and shift time slot selection are required; fee displays as Free (RM0.00).", bold_prefix="Test Case 5 — Store Self-Collection: ")
    add_bullet("Log in to /admin with administrator credentials. Test updating store contact details (/admin/settings), reviewing orders (/admin/orders), updating translations (/admin/translations), and downloading an SQL database backup (/admin/database).", bold_prefix="Test Case 6 — Admin Backend Functions: ")

    # ═══════════════════════════════════════════════════════════════════════════
    # 5. HANDOVER & APPRECIATION ACKNOWLEDGMENT
    # ═══════════════════════════════════════════════════════════════════════════
    add_heading("4. Handover Roadmap & Closing Acknowledgment", level=1)
    add_body(
        "Everything requested for final handover has been prepared and verified:"
    )
    add_bullet("The full system manual is provided in your handover documentation package, detailing all operational procedures step by step.", bold_prefix="• Admin & Operations Manual: ")
    add_bullet("Root administrator access (admin@mst.my) will be formally confirmed upon conclusion of your final testing.", bold_prefix="• Administrator Access & Permissions: ")
    add_bullet("The latest production code is deployed on the live server, synchronized via GitHub Actions, and 1-click database backup downloads are operational.", bold_prefix="• Live Version & Backup Safeguards: ")
    add_bullet("A 30-day post-handover warranty begins upon formal sign-off, covering critical bug resolution and operational stability.", bold_prefix="• 30-Day Post-Launch Warranty: ")

    add_callout(
        "Thank you once again for your professionalism, fair partnership, and for recognizing the additional work and dedication poured "
        "into the MST platform. I look forward to your testing feedback and to successfully completing the final handover together.",
        title="Appreciation & Closing Note",
        fill_hex="F0FDF4",
        border_hex="16A34A"
    )

    # Closing Signature Block
    doc.add_paragraph().paragraph_format.space_after = Pt(8)
    sig_p = doc.add_paragraph()
    sig_p.paragraph_format.space_before = Pt(10)
    sig_p.paragraph_format.space_after = Pt(2)
    sig_p.paragraph_format.line_spacing = 1.15
    r_sig1 = sig_p.add_run("Sincerely,\n\n")
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
    output_filename = "f:\\My AI\\Sea Food\\seafood\\MST_Additional_Tasks_Scope_Classification_and_Handover_Report.docx"
    doc.save(output_filename)
    print(f"Document successfully created at: {output_filename}")

if __name__ == "__main__":
    create_document()
