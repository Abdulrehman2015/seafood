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

    # Set page margins: 0.75 in top/bottom, 0.75 in left/right
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

    r_title = hp.add_run("CLIENT REQUIREMENTS CONSOLIDATION, ZONE A SPECIFICATION\n& COMPLETE BACKEND ADMINISTRATION MANUAL\n")
    r_title.font.name = "Calibri"
    r_title.font.size = Pt(14.5)
    r_title.font.bold = True
    r_title.font.color.rgb = RGBColor(255, 255, 255)

    r_sub = hp.add_run("Formal Technical Response, Postcode Directory (Postcode.my), Operational Manual & Handover Roadmap")
    r_sub.font.name = "Calibri"
    r_sub.font.size = Pt(10)
    r_sub.font.italic = True
    r_sub.font.color.rgb = RGBColor(224, 242, 254)

    doc.add_paragraph().paragraph_format.space_after = Pt(6)

    # Metadata Grid
    meta_table = doc.add_table(rows=3, cols=2)
    meta_table.alignment = WD_TABLE_ALIGNMENT.CENTER
    set_table_border(meta_table, "CBD5E1")
    col_widths = [Inches(3.5), Inches(3.5)]

    meta_data = [
        [("Addressed To:", "MST Management & Client Representative"), ("Prepared By:", "Abdul Rehman (Lead Full-Stack & Technical Architect)")],
        [("Document Reference:", "MST-UAT-CONSOLIDATION-2026-V1"), ("Date of Issuance:", "October 9, 2026")],
        [("Platform Version:", "Laravel Unified B2B & B2C Production Engine"), ("Status:", "Official Response & Action Plan Ready")]
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

    # Executive Greeting & Callout
    add_callout(
        "Thank you for your comprehensive consolidation message. We have carefully reviewed every requirement regarding "
        "Zone A delivery coverage, delivery fee rules, backend administration, promotions/discounts, and final handover. "
        "All code updates regarding Skudai (81300) and Kulai (Indahpura only) have been implemented and aligned. "
        "This document provides the compiled Postcode.my directory for your review, a complete step-by-step Admin Operation Manual, "
        "a transparent review of the coupon/marketing attribution architecture, and our final handover confirmation.",
        title="Executive Opening & Acknowledgment",
        fill_hex="F0FDF4",
        border_hex="16A34A"
    )

    # ═══════════════════════════════════════════════════════════════════════════
    # 2. SECTION 1: ZONE A DELIVERY COVERAGE & POSTCODE DIRECTORY
    # ═══════════════════════════════════════════════════════════════════════════
    add_heading("1. MST Zone A Delivery Coverage & Compiled Postcode Directory", level=1)
    add_body(
        "In accordance with your instructions, we have cross-referenced the official Johor postal directory on Postcode.my "
        "(https://postcode.my/location/johor/) for the 10 requested coverage areas. We have updated the platform's logistics rules "
        "to ensure strict compliance with your four business constraints:"
    )

    add_bullet("Postcode 81000 is included in Zone A ONLY when the delivery address or area specifies 'Indahpura' or 'Bandar Indahpura'. All general and outstation Kulai postcodes remain classified as Outstation / Zone B.", bold_prefix="1. Kulai Restricted to Indahpura Only: ")
    add_bullet("The matching engine enforces authoritative 5-digit postcode validation rather than relying solely on high-level prefixes.", bold_prefix="2. Exact Postcode Matching: ")
    add_bullet("The previous hardcoded exclusion of 81300 has been completely removed in DeliveryService.php and DeliveryZone.php. Postcode 81300 is now officially recognized as Zone A local delivery.", bold_prefix="3. Skudai (81300) Recognized in Zone A: ")
    add_bullet("The identical postcode and area evaluation rules execute across the shopping cart delivery calculation (/api/calculate-delivery-fee) and the live checkout order placement pipeline.", bold_prefix="4. 100% Cart & Checkout Consistency: ")

    add_heading("Compiled Zone A Postcode Directory for Review & Confirmation", level=2)
    add_body("Below is the compiled directory of postcodes and sub-localities for the 10 designated areas for your formal review:")

    # Postcode Table
    postcode_table_data = [
        ("Area #", "Target Area Name", "Official Postcode(s)", "Key Included Localities & Sub-Districts", "Coverage Status"),
        ("1", "Gelang Patah", "81550, 79200", "Gelang Patah Town, Nusajaya Industrial Park, Kampung Baru Gelang Patah, SILC Vicinity", "Zone A (Local)"),
        ("2", "Iskandar Puteri / Nusajaya", "79000, 79100, 79200, 79250, 79500", "Kota Iskandar, Medini, Puteri Harbour, East Ledang, Horizon Hills, Ledang Heights, Eco Botanic", "Zone A (Local)"),
        ("3", "Johor Bahru Core", "80000, 80050, 80100, 80150, 80200, 80250, 80300, 80350, 80400, 80500, 80550, 80600, 80650, 80700, 80710, 80720, 80730, 80800, 80990, 81200", "JB City Centre, Larkin, Tampoi, Bandar Baru Uda, Danga Bay, Stulang, Century, Majidee, Pelangi, Taman Sentosa, Perling, Bukit Indah", "Zone A (Local)"),
        ("4", "Kulai (Indahpura Only)", "81000", "Bandar Indahpura, AEON Mall Kulai vicinity, Diamond 1-3, Indahpura Industrial Park. (Strict rule: Rural/outstation Kulai excluded)", "Zone A (Indahpura Only)"),
        ("5", "Masai", "81750", "Masai Town, Bandar Seri Alam, Plentong, Kota Puteri, Taman Megah Ria", "Zone A (Local)"),
        ("6", "Senai", "81400", "Senai Town, Senai Airport City, Senai Industrial Park I-IV, Taman Senai Utama", "Zone A (Local)"),
        ("7", "Skudai (incl. 81300)", "81300", "Skudai Town, Taman Universiti, Mutiara Rini, Taman Ungku Tun Aminah (TUTA), Skudai Baru, Sutera Utama / Sutera Mall", "Zone A (Local)"),
        ("8", "Setia Eco Gardens", "81550", "Setia Eco Gardens, Eco Village, Gelang Patah Southern Corridor", "Zone A (Local)"),
        ("9", "Mount Austin", "81100", "Taman Mount Austin, Austin Heights, Austin Perdana, Austin Duta, Taman Daya, JP Perdana", "Zone A (Local)"),
        ("10", "Confirmed ICQ Area (Iskandar Puteri)", "79000, 79100, 79200, 79250", "Kompleks Sultan Abu Bakar (Second Link CIQ), Tanjung Kupang, Puteri Harbour Ferry Terminal ICQ, Kota Iskandar CIQ checkpoints", "Zone A (Local)")
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
    # 3. SECTION 2: DELIVERY FEE RULES CONFIRMATION
    # ═══════════════════════════════════════════════════════════════════════════
    add_heading("2. Delivery Fee Rules & Fulfillment Workflow Confirmation", level=1)
    add_body("We confirm that all six delivery fee rules specified in your message are strictly retained, active, and verified:")

    add_bullet("Orders below RM150 residing in Zone A are automatically assessed the RM10.00 local delivery fee. The delivery line item in Stripe reflects exactly RM10.00.", bold_prefix="• Zone A Orders Below RM150 (RM10 Fee): ")
    add_bullet("Orders of RM150.00 and above residing in Zone A qualify for Free Standard Delivery (RM0.00). Stripe charges only the product subtotal.", bold_prefix="• Zone A Orders of RM150 & Above (RM0 Free Delivery): ")
    add_bullet("The checkout and order confirmation display 'Outstation Transportation Fee — To Be Confirmed'. Exactly RM0.00 is added to the Stripe checkout amount, ensuring customers are never overcharged before logistics arrangements are finalized.", bold_prefix="• Outstation Orders (To Be Confirmed): ")
    add_bullet("Outstation orders trigger a dedicated notice banner with an integrated WhatsApp button (wa.me) allowing MST's operations team to coordinate Styrofoam box packaging sizing and cold-chain freight rates directly with the buyer.", bold_prefix="• WhatsApp Coordination Link: ")
    add_bullet("Store pickup at Counter 2, SILC Industrial Park, remains 100% free (RM0.00). Delivery address, city, and postcode fields are completely hidden, while collection date and collection time slot selection are mandatory.", bold_prefix="• Store Self-Collection (Free / Address Hidden): ")
    add_bullet("Checkout displays: 'Please allow up to 7 working days for order sourcing and cold-chain delivery arrangements. The available delivery date will be confirmed by MST based on product availability and delivery scheduling.'", bold_prefix="• 7 Working Days Delivery Lead-Time Notice: ")
    add_bullet("Orders of any value (e.g. RM25, RM50, RM80) are fully permitted to proceed to payment. RM150 functions strictly as the delivery waiver threshold, preserving retail sales volume.", bold_prefix="• RM150 Reference Threshold (Non-Blocking): ")

    # ═══════════════════════════════════════════════════════════════════════════
    # 4. SECTION 3: STEP-BY-STEP WEBSITE ADMIN & OPERATION MANUAL
    # ═══════════════════════════════════════════════════════════════════════════
    add_heading("3. Comprehensive Website Admin & Operation Manual", level=1)
    add_body(
        "Below is your complete step-by-step operational guide for managing routine website updates through the administrator backend "
        "without requiring developer assistance."
    )

    add_heading("3.1. Updating Company Information, Address, Phone, WhatsApp & Email", level=2)
    add_bullet("Navigate to: Admin Panel → Store Settings (URL: /admin/settings).", bold_prefix="Step 1: ")
    add_bullet("Locate the 'Store Legacy / Physical Details' card and 'SMTP Mail Settings' card.", bold_prefix="Step 2: ")
    add_bullet("Editable fields include: Store Name, Store Tagline, Store Physical Address, Phone Number, WhatsApp Number (format: 601112710260 without '+' or hyphens for WhatsApp API compatibility), Store Contact Email, and Business Operating Hours.", bold_prefix="Step 3: ")
    add_bullet("Click 'Save All Settings' at the bottom of the page. The system clears the settings cache immediately, and all footer, header, and contact page elements update site-wide.", bold_prefix="Step 4: ")

    add_heading("3.2. Updating Website Banners, Announcements & Page Content", level=2)
    add_bullet("Homepage Hero & Announcements: Managed via Admin Panel → Store Settings → Site Appearance & General Settings. Update the primary site title, meta description, and banner text.", bold_prefix="Banners & Announcements: ")
    add_bullet("Legal & Informational Pages: Navigate to Admin Panel → Policies & Dynamic Pages (/admin/policies). Click 'Edit' on Privacy Policy, Terms & Conditions, Refund Policy, Shipping Policy, or Cookie Policy to update text directly using the rich editor.", bold_prefix="Static Content Pages: ")
    add_bullet("Toggle 'Published / Draft' status to control page visibility.", bold_prefix="Status Control: ")

    add_heading("3.3. Managing Multilingual Content (English, Simplified Chinese, Bahasa Melayu)", level=2)
    add_bullet("Direct Product Localization: When editing any product (Admin → Products → Edit), you will find dedicated tabs/fields for English (Name, Description), Simplified Chinese (name_zh, description_zh), and Bahasa Melayu (name_bm, description_bm).", bold_prefix="Product Translations: ")
    add_bullet("Site-wide Interface Phrases: Navigate to Admin Panel → Multilingual Translations (/admin/translations). Use the search bar to locate any button label, header text, or banner string. Edit the translation for EN, ZH, or BM, then click 'Update Translations'.", bold_prefix="UI Strings & Labels: ")
    add_bullet("Click 'Clear Translation Cache' (/admin/translations/cache/clear) if updates do not appear immediately.", bold_prefix="Cache Purge: ")

    add_heading("3.4. Managing Products: Names, Descriptions, Images, Prices & Stock", level=2)
    add_bullet("Navigate to: Admin Panel → Product Management (/admin/products).", bold_prefix="Step 1: ")
    add_bullet("Adding Products: Click '+ Add New Product'. Fill in Name, SKU, Category, and Unit (e.g. pack, box, kg).", bold_prefix="Step 2: ")
    add_bullet("Pricing Management: Enter Retail Price, Walk-in Price, Wholesale Price, and Trading Price. (Optional: SGD and USD price fields are available if fixed foreign pricing is desired).", bold_prefix="Step 3: ")
    add_bullet("Stock Control: Enter 'Stock Quantity' and check 'Track Stock'. When enabled, the system automatically decrements stock upon order placement and displays low-stock badges.", bold_prefix="Step 4: ")
    add_bullet("Image Upload & Gallery: Upload a primary thumbnail and multiple product gallery photos. You can upload files directly from your computer or select existing photos from the Media Gallery.", bold_prefix="Step 5: ")
    add_bullet("Click 'Create Product' or 'Update Product'. Trashed products can be restored via the 'Archived' tab.", bold_prefix="Step 6: ")

    add_heading("3.5. Controlling Product Visibility & Customer-Tier Access", level=2)
    add_bullet("Active Status (is_active): Check or uncheck to show or hide the product across the entire digital storefront.", bold_prefix="Global Visibility: ")
    add_bullet("Walk-In Availability (is_walkin_available): Check this box to include the product in the Walk-In QR Storefront. If unchecked, the item is hidden from Walk-in customers.", bold_prefix="Walk-In QR Catalog: ")
    add_bullet("Featured Product (is_featured): Displays the product on the homepage 'Featured Products' showcase.", bold_prefix="Homepage Showcase: ")
    add_bullet("RFQ-Only Mode (is_rfq_only): Hides unit prices and displays 'Price upon request / Request Quotation', converting the purchase button to an RFQ inquiry.", bold_prefix="Trading RFQ Mode: ")
    add_bullet("MOQ Controls: Set item-level Minimum Order Quantities for Wholesale (moq_wholesale) and Trading (moq_trading) tiers.", bold_prefix="MOQ Constraints: ")

    add_heading("3.6. Managing Delivery Areas, Postcodes, Fees & Time Slots", level=2)
    add_bullet("Postcodes & Coverage: Navigate to Admin Panel → Delivery Zones (/admin/delivery-zones). Click 'Edit' on Zone A or Zone B to add or remove postcodes, areas, or modify base and below-threshold fees.", bold_prefix="Delivery Zones: ")
    add_bullet("Free Delivery Threshold: On the Delivery Zones page, update the 'B2C Free Delivery Threshold' field (default: RM150.00) and click 'Update Threshold'.", bold_prefix="Threshold Amount: ")
    add_bullet("Collection Time Slots: Hard-coded to the four standard operational shifts (8:30–10:30 AM, 10:30 AM–12:30 PM, 1:30–3:30 PM, 3:30–5:30 PM). To alter these hours, adjustments are made in the checkout template or store settings.", bold_prefix="Pickup Shift Hours: ")

    add_heading("3.7. Managing Orders, Customer Accounts, Invoices & Reports", level=2)
    add_bullet("Orders Dashboard (/admin/orders): Filter orders by status (Pending, Confirmed, Preparation, Shipped, Delivered, Collected), payment status (Paid, Unpaid), and fulfillment type. Click any order to view customer details, line items, and payment references.", bold_prefix="Order Tracking: ")
    add_bullet("Schedule Confirmation (/admin/orders/{id}): Set the confirmed delivery date, time, and dispatch notes. Click 'Notify Schedule' to automatically email the customer.", bold_prefix="Dispatch Scheduling: ")
    add_bullet("Formal Tax Invoices (/admin/orders/{id}/invoice): 1-click printable formal invoice with company header, buyer SSM details, tax breakdown, and Ringgit amount in words.", bold_prefix="Invoice Printing: ")
    add_bullet("Customer Accounts (/admin/customers): Review B2B Wholesale and Trading registration applications. One-click 'Approve', 'Reject', or 'Unblock' actions. View duplicate phone and SSM alerts.", bold_prefix="Customer Vetting: ")
    add_bullet("Newsletter Subscribers (/admin/newsletter): View subscriber list and click 'Export CSV' to download marketing contact lists.", bold_prefix="Subscriber Export: ")

    add_heading("3.8. Responsibility Matrix: Backend Self-Management vs. Developer Assistance", level=2)
    add_body("To clarify ongoing operational boundaries, the matrix below details what can be self-managed versus what requires technical code intervention:")

    # Matrix Table
    resp_table_data = [
        ("Administrative Function", "Self-Managed via Admin Backend?", "Requires Developer / Technical Code?"),
        ("Updating Business Address, Phone, WhatsApp, Email", "YES (Admin -> Settings)", "No"),
        ("Editing Product Prices, Descriptions, Photos & Stock", "YES (Admin -> Products)", "No"),
        ("Adding New Categories & Setting Sort Orders", "YES (Admin -> Categories)", "No"),
        ("Adding / Removing Postcodes in Delivery Zones", "YES (Admin -> Delivery Zones)", "No"),
        ("Changing Free Delivery Threshold (RM150)", "YES (Admin -> Delivery Zones)", "No"),
        ("Reviewing & Approving B2B Wholesale Applicants", "YES (Admin -> Customers)", "No"),
        ("Managing Orders & Setting Confirmed Delivery Dates", "YES (Admin -> Orders)", "No"),
        ("Translating Product & Website UI Strings (EN/ZH/BM)", "YES (Admin -> Translations & Products)", "No"),
        ("Downloading .SQL Database Backups", "YES (Admin -> Database -> Download)", "No"),
        ("Editing Legal Policies (Privacy, Terms, Refunds)", "YES (Admin -> Policies)", "No"),
        ("Adding New Payment Gateway (e.g. FPX direct, e-Wallets)", "No (Stripe is active)", "YES (Requires Gateway API Integration)"),
        ("Altering Core Database Architecture or Schemas", "No", "YES (Requires Laravel Migration)"),
        ("Redesigning Page Layouts or Header Navigation Bars", "No", "YES (Requires Blade Template Editing)")
    ]

    r_table = doc.add_table(rows=len(resp_table_data), cols=3)
    r_table.alignment = WD_TABLE_ALIGNMENT.CENTER
    set_table_border(r_table, "CBD5E1")
    r_widths = [Inches(3.2), Inches(2.2), Inches(1.6)]

    for r_idx, row in enumerate(r_table.rows):
        is_head = (r_idx == 0)
        for c_idx, val in enumerate(resp_table_data[r_idx]):
            cell = row.cells[c_idx]
            cell.width = r_widths[c_idx]
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
                if c_idx == 1:
                    run.font.bold = True
                    run.font.color.rgb = c_green if val.startswith("YES") else c_slate
                elif c_idx == 2:
                    run.font.bold = True
                    run.font.color.rgb = RGBColor(220, 38, 38) if val.startswith("YES") else c_slate
                else:
                    run.font.bold = True
                    run.font.color.rgb = c_navy

    doc.add_paragraph().paragraph_format.space_after = Pt(8)

    # ═══════════════════════════════════════════════════════════════════════════
    # 5. SECTION 4: PROMOTIONS, DISCOUNTS & COUPON CODES ARCHITECTURE
    # ═══════════════════════════════════════════════════════════════════════════
    add_heading("4. Promotions, Discounts & Coupon Codes: Technical Status & Proposal", level=1)

    add_heading("4.1. Current System Status (What is Active Now)", level=2)
    add_body("In the delivered system, promotional and discount pricing is currently managed through:")
    add_bullet("Administrators define distinct pricing per SKU across Retail, Walk-in, Wholesale, and Trading tiers.", bold_prefix="Tiered Pricing Schedules: ")
    add_bullet("Automatic cart qualification for free standard delivery when reaching the RM150 / RM350 threshold.", bold_prefix="Free Delivery Incentives: ")
    add_bullet("Products can be placed in 'Featured' sections with promotional banner callouts.", bold_prefix="Featured Spotlights: ")

    add_body(
        "Clarification: A standalone Coupon / Promo Code Engine (allowing shoppers to enter codes like FBMST10, WAMST10, TIKTOK10, or REFMST10 "
        "at checkout, calculating percentage or fixed-amount discounts, enforcing usage limits and dates, and tracking marketing attribution) "
        "was NOT part of the original project proposal (.txt). However, because this is an exceptionally valuable marketing tool for MST, "
        "we have fully architected the implementation plan below."
    )

    add_heading("4.2. Proposed Coupon Code & Marketing Attribution Architecture", level=2)
    add_body("To implement full coupon code management exactly as you requested, the following components will be built:")
    add_bullet("New coupons table storing: code, discount_type ('percentage' vs 'fixed'), discount_amount, min_spend, max_spend, usage_limit_total, usage_limit_per_user, usage_count, valid_from, valid_until, customer_group, marketing_channel ('facebook', 'whatsapp', 'tiktok', 'referral'), referral_partner_name, and is_active.", bold_prefix="1. Database Schema: ")
    add_bullet("Interactive 'Have a coupon code?' field in cart and checkout with real-time AJAX validation checking expiry, minimum spend, and tier constraints before applying the discount to the subtotal.", bold_prefix="2. Frontend Checkout Field: ")
    add_bullet("Discount deductions are calculated server-side, passed as itemized negative lines to Stripe Hosted Checkout, and recorded in Order records and printable tax invoices.", bold_prefix="3. Stripe & Invoice Integration: ")
    add_bullet("Every order records coupon_code, marketing_source (Facebook, WhatsApp, TikTok), and referral_partner. Orders index displays attribution badges.", bold_prefix="4. Marketing Source & Referral Tracking: ")
    add_bullet("A dedicated back-office interface (/admin/coupons) to create promo codes, set start/end dates, activate/deactivate promotions, view redemption counts, and analyze marketing channel performance.", bold_prefix="5. Admin Promotions Workbench: ")

    add_heading("4.3. Development Timeline & Additional Investment Advice", level=2)
    add_body(
        "Because this module requires building new database migrations, cart calculation refactoring, Stripe payload updates, "
        "checkout validation UI, and a dedicated back-office coupon workbench, the implementation scope is estimated as follows:"
    )

    add_bullet("3 to 5 business days from approval.", bold_prefix="Estimated Development Duration: ")
    add_bullet("RM 650.00 (One-time investment covering complete backend CRUD, checkout integration, Stripe metadata sync, marketing referral tracking, testing, and operation manual updates).", bold_prefix="Additional Investment Quote: ")
    add_bullet("If approved, this can be executed immediately as a supplementary milestone or post-handover enhancement without delaying the primary platform UAT sign-off.", bold_prefix="Execution Path: ")

    # ═══════════════════════════════════════════════════════════════════════════
    # 6. SECTION 5: FINAL HANDOVER & DOCUMENTATION
    # ═══════════════════════════════════════════════════════════════════════════
    add_heading("5. Final Handover, Credentials & Acceptance Roadmap", level=1)

    add_heading("5.1. System Ownership & Credentials Handover", level=2)
    add_bullet("Primary Administrator Credentials: Username: admin@mst.my | Password will be provided securely via private channel upon final sign-off.", bold_prefix="Admin Account: ")
    add_bullet("Database Ownership: The complete MySQL database (oceanfresh) and codebase reside 100% on your infrastructure with zero vendor locks.", bold_prefix="Full Code & Database IP: ")
    add_bullet("Admins can download a full, self-contained .sql database dump at any moment via Admin Panel → Database Management → Download Backup.", bold_prefix="1-Click SQL Backup Routine: ")

    add_heading("5.2. Video Tutorial & Training Assistance", level=2)
    add_body(
        "In addition to the comprehensive written manual in Section 3, we are happy to conduct a live interactive walkthrough (Google Meet / Zoom) "
        "or record concise screen-capture video tutorials demonstrating routine product updates, price adjustments, and order processing."
    )

    add_heading("5.3. Final Acceptance Checklist & Next Steps", level=2)
    add_body("To conclude final project acceptance smoothly:")
    add_bullet("Confirm whether the compiled Postcode.my Zone A directory (Table in Section 1) is approved as presented.", bold_prefix="Step 1 — Zone A Confirmation: ")
    add_bullet("Advise whether you wish to proceed with the Coupon & Referral Partner Engine (Section 4) as an immediate add-on or post-launch enhancement.", bold_prefix="Step 2 — Promotion Module Decision: ")
    add_bullet("Verify that all delivery rules (Section 2) and administration workflows (Section 3) meet your operational expectations.", bold_prefix="Step 3 — Final UAT Sign-Off: ")
    add_bullet("Release the final project milestone upon formal sign-off.", bold_prefix="Step 4 — Handover & Warranty Activation: ")

    # Closing Signature Block
    doc.add_paragraph().paragraph_format.space_after = Pt(8)
    sig_p = doc.add_paragraph()
    sig_p.paragraph_format.space_before = Pt(10)
    sig_p.paragraph_format.space_after = Pt(2)
    sig_p.paragraph_format.line_spacing = 1.15
    r_sig1 = sig_p.add_run("Sincerely,\n")
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
    output_filename = "f:\\My AI\\Sea Food\\seafood\\MST_Client_Consolidation_Response_and_Handover_Report.docx"
    doc.save(output_filename)
    print(f"Document successfully created at: {output_filename}")

if __name__ == "__main__":
    create_document()
