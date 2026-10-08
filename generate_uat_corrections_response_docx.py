import docx
from docx.shared import Inches, Pt, RGBColor
from docx.enum.text import WD_ALIGN_PARAGRAPH
from docx.enum.table import WD_TABLE_ALIGNMENT, WD_ALIGN_VERTICAL
from docx.oxml import parse_xml
from docx.oxml.ns import nsdecls, qn
from docx.oxml import OxmlElement

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

def add_heading(doc, text, level=1, color=None, space_before=14, space_after=4):
    p = doc.add_paragraph()
    p.paragraph_format.space_before = Pt(space_before)
    p.paragraph_format.space_after = Pt(space_after)
    p.paragraph_format.keep_with_next = True
    run = p.add_run(text)
    run.font.name = "Calibri"
    run.font.bold = True
    if level == 1:
        run.font.size = Pt(13)
    elif level == 2:
        run.font.size = Pt(11)
    elif level == 3:
        run.font.size = Pt(10)
    if color:
        run.font.color.rgb = color
    return p

def add_body(doc, text, size=9.5, italic=False, bold=False, color=None, space_before=2, space_after=4):
    p = doc.add_paragraph()
    p.paragraph_format.space_before = Pt(space_before)
    p.paragraph_format.space_after = Pt(space_after)
    p.paragraph_format.line_spacing = 1.15
    run = p.add_run(text)
    run.font.name = "Calibri"
    run.font.size = Pt(size)
    run.font.italic = italic
    run.font.bold = bold
    if color:
        run.font.color.rgb = color
    return p

def add_bullet(doc, text, size=9.5, color=None, bold_prefix=None):
    p = doc.add_paragraph(style='List Bullet')
    p.paragraph_format.space_before = Pt(2)
    p.paragraph_format.space_after = Pt(2.5)
    p.paragraph_format.left_indent = Inches(0.25)
    p.paragraph_format.line_spacing = 1.15
    if bold_prefix:
        r1 = p.add_run(bold_prefix)
        r1.font.name = "Calibri"
        r1.font.size = Pt(size)
        r1.font.bold = True
        if color:
            r1.font.color.rgb = color
        r2 = p.add_run(text)
        r2.font.name = "Calibri"
        r2.font.size = Pt(size)
        if color:
            r2.font.color.rgb = color
    else:
        r = p.add_run(text)
        r.font.name = "Calibri"
        r.font.size = Pt(size)
        if color:
            r.font.color.rgb = color
    return p

def add_callout(doc, text, title=None, fill_hex="F0FDFA", border_hex="0F766E", text_color=None):
    tbl = doc.add_table(rows=1, cols=1)
    tbl.alignment = WD_TABLE_ALIGNMENT.CENTER
    tbl.autofit = False
    
    cell = tbl.cell(0, 0)
    cell.width = Inches(6.5)
    set_cell_background(cell, fill_hex)
    set_cell_margins(cell, top=100, bottom=100, left=140, right=140)
    
    tcPr = cell._tc.get_or_add_tcPr()
    tblBorders = parse_xml(
        f'<w:tcBorders {nsdecls("w")}>'
        f'<w:top w:val="none"/>'
        f'<w:left w:val="single" w:sz="24" w:space="0" w:color="{border_hex}"/>'
        f'<w:bottom w:val="none"/>'
        f'<w:right w:val="none"/>'
        f'</w:tcBorders>'
    )
    tcPr.append(tblBorders)
    
    p = cell.paragraphs[0]
    p.paragraph_format.space_before = Pt(0)
    p.paragraph_format.space_after = Pt(2)
    p.paragraph_format.line_spacing = 1.15
    
    if title:
        r_title = p.add_run(f"{title}\n")
        r_title.font.name = "Calibri"
        r_title.font.size = Pt(10)
        r_title.font.bold = True
        r_title.font.color.rgb = RGBColor(15, 118, 110)
    
    r_text = p.add_run(text)
    r_text.font.name = "Calibri"
    r_text.font.size = Pt(9.5)
    if text_color:
        r_text.font.color.rgb = text_color
    else:
        r_text.font.color.rgb = RGBColor(30, 41, 59)
    
    doc.add_paragraph().paragraph_format.space_after = Pt(4)

def generate_report():
    doc = docx.Document()
    
    # Page setup
    for section in doc.sections:
        section.top_margin = Inches(0.75)
        section.bottom_margin = Inches(0.75)
        section.left_margin = Inches(0.8)
        section.right_margin = Inches(0.8)
    
    # Color palette
    c_navy = RGBColor(15, 23, 42)        # #0F172A
    c_teal = RGBColor(15, 118, 110)      # #0F766E
    c_slate = RGBColor(51, 65, 85)       # #334155
    c_blue = RGBColor(30, 64, 175)       # #1E40AF
    c_green = RGBColor(22, 101, 52)      # #166534
    
    # Title Block
    title_p = doc.add_paragraph()
    title_p.paragraph_format.space_before = Pt(0)
    title_p.paragraph_format.space_after = Pt(2)
    run_title = title_p.add_run("MST GOLF SEAFOOD E-COMMERCE PLATFORM")
    run_title.font.name = "Calibri"
    run_title.font.size = Pt(16)
    run_title.font.bold = True
    run_title.font.color.rgb = c_navy
    
    sub_p = doc.add_paragraph()
    sub_p.paragraph_format.space_before = Pt(0)
    sub_p.paragraph_format.space_after = Pt(12)
    run_sub = sub_p.add_run("UAT Delivery & Checkout Verification Report & Implementation Response")
    run_sub.font.name = "Calibri"
    run_sub.font.size = Pt(11.5)
    run_sub.font.color.rgb = c_teal
    run_sub.font.bold = True
    
    # Metadata Table
    meta_table = doc.add_table(rows=4, cols=2)
    meta_table.alignment = WD_TABLE_ALIGNMENT.CENTER
    meta_table.autofit = False
    
    meta_data = [
        ("Recipient / Stakeholder:", "MST Golf Management & QA Testing Team"),
        ("Prepared By:", "Abdul Rehman (Lead Technical & Full-Stack Architect)"),
        ("Date & Version:", "October 8, 2026 | Version 2.4 — UAT Checkout Final Polish"),
        ("Status & Git Reference:", "All Corrections Applied & Pushed to Git (`origin/main`)"),
    ]
    
    for i, (k, v) in enumerate(meta_data):
        row = meta_table.rows[i]
        
        c0 = row.cells[0]
        c0.width = Inches(2.2)
        set_cell_background(c0, "F8FAFC")
        set_cell_margins(c0, 60, 60, 100, 100)
        p0 = c0.paragraphs[0]
        p0.paragraph_format.space_before = Pt(0)
        p0.paragraph_format.space_after = Pt(0)
        r0 = p0.add_run(k)
        r0.font.name = "Calibri"
        r0.font.size = Pt(9)
        r0.font.bold = True
        r0.font.color.rgb = c_slate
        
        c1 = row.cells[1]
        c1.width = Inches(4.3)
        set_cell_background(c1, "FFFFFF")
        set_cell_margins(c1, 60, 60, 100, 100)
        p1 = c1.paragraphs[0]
        p1.paragraph_format.space_before = Pt(0)
        p1.paragraph_format.space_after = Pt(0)
        r1 = p1.add_run(v)
        r1.font.name = "Calibri"
        r1.font.size = Pt(9)
        r1.font.color.rgb = c_navy
    
    set_table_border(meta_table, "E2E8F0")
    doc.add_paragraph().paragraph_format.space_after = Pt(6)
    
    # Callout Banner
    add_callout(
        doc,
        "Thank you for conducting the comprehensive UAT testing on the checkout and delivery flows. All 4 core passed scenarios (Delivery Reference Threshold, Store Self-Collection, Zone A Delivery Calculation, and Outstation To-Be-Confirmed flow) have been locked and preserved. The 2 requested corrections regarding postcode-authoritative zone matching and Zone A vs Outstation customer notice separation have been fully implemented, verified, and committed to the main repository.",
        title="Executive Summary & UAT Outcome Status",
        fill_hex="F0FDF4",
        border_hex="16A34A",
        text_color=c_green
    )
    
    # Section 1: Passed UAT Items Preserved
    add_heading(doc, "1. Preservation of Passed UAT Features (Locked Logic)", level=1, color=c_navy)
    add_body(doc, "As confirmed in your UAT report, the following core business logic rules have passed verification and are strictly preserved without modification:", size=9.5, color=c_slate)
    
    add_bullet(doc, "Orders below RM150 are fully allowed to proceed to checkout and payment without any minimum-order restriction. RM150 serves strictly as the Free Delivery Reference Threshold for Zone A local deliveries.", size=9.5, color=c_slate, bold_prefix="1. Delivery Reference Threshold (RM150): ")
    add_bullet(doc, "RM0 collection fee is charged. Address and postcode input fields are cleanly hidden. Customer is required to specify collection date and time. Stripe charges only the product subtotal.", size=9.5, color=c_slate, bold_prefix="2. Store Self-Collection: ")
    add_bullet(doc, "For local postcodes (e.g. 79100 Johor Bahru / Iskandar Puteri), orders below RM150 are charged RM10 delivery fee, while orders RM150 and above qualify for Free Delivery (RM0.00).", size=9.5, color=c_slate, bold_prefix="3. Zone A Delivery Calculation: ")
    add_bullet(doc, "Outstation orders proceed without fixed transportation charges. The line item displays 'Outstation Transportation Fee — To Be Confirmed' (RM0.00 in Stripe) with final logistics and Styrofoam cold-chain packaging costs confirmed via WhatsApp by MST.", size=9.5, color=c_slate, bold_prefix="4. Outstation Cold-Chain Delivery: ")
    
    # Section 2: Implementation of Requested Corrections
    add_heading(doc, "2. Detailed Implementation of Corrections", level=1, color=c_navy)
    
    add_heading(doc, "Correction A: Postcode Must Be Authoritative in Zone Calculation (Point 5)", level=2, color=c_blue)
    add_body(doc, "In Malaysian logistics, general municipal labels (such as 'Johor Bahru') cover a vast geographic territory that includes both local Zone A zones and extended Outstation / Zone B districts (such as Skudai 81300 or Kulai 81400).", size=9.5, color=c_slate)
    add_body(doc, "Previous Behavior: If a customer entered City: 'Johor Bahru' with an outstation postcode (e.g., 81300 or 81800), the city matching rule would match Zone A even if the postcode was outside Zone A's approved list.", size=9.5, color=c_slate, italic=True)
    add_body(doc, "Resolved Implementation: `DeliveryZone::matchesLocation()` has been refactored with strict postcode primacy:", size=9.5, color=c_navy, bold=True)
    
    add_bullet(doc, "When a customer provides a postcode, the system strictly matches against the zone's configured postcodes (79xxx, 80xxx for Zone A).", size=9.5, color=c_slate, bold_prefix="• Postcode Primacy: ")
    add_bullet(doc, "If the provided postcode does NOT match Zone A's postcodes, the system immediately rejects Zone A and DOES NOT fall back to city or state matching. It continues evaluating Zone B / Outstation.", size=9.5, color=c_slate, bold_prefix="• Fallback Guard: ")
    add_bullet(doc, "Johor Bahru + 79100 → Zone A (RM10 below RM150, RM0 at/above RM150). Johor Bahru + 81300 → Outstation / Zone B (Transportation Fee To Be Confirmed via WhatsApp).", size=9.5, color=c_slate, bold_prefix="• Exact Match Validation: ")
    
    add_heading(doc, "Correction B: Clean Customer-Facing Notice Separation (Point 6)", level=2, color=c_blue)
    add_body(doc, "The notice banners in both the server-side Blade template (`checkout/index.blade.php`) and the dynamic live AJAX recalculation script have been updated to eliminate notice crossover:", size=9.5, color=c_slate)
    
    add_bullet(doc, "For orders below RM150, shows 'Zone A Delivery Fee (RM 10.00): Applicable local delivery fee applied for Zone A (Orders RM 150 and above qualify for Free Standard Delivery)'. For orders RM150+, shows 'Free Standard Delivery (Zone A): RM 0.00'. Under NO circumstances will the Outstation Cold-Chain notice be shown.", size=9.5, color=c_slate, bold_prefix="• Zone A / Normal Delivery: ")
    add_bullet(doc, "Shows 'Outstation Transportation Fee — To Be Confirmed' and displays the dedicated notice explaining that final transportation cost is confirmed by MST via WhatsApp based on cold-chain packaging (Styrofoam box size & quantity). No automatic delivery fee is added.", size=9.5, color=c_slate, bold_prefix="• Outstation Delivery: ")
    add_bullet(doc, "Displays 'Store Self-Collection (Free): Collect your confirmed order directly from MST SILC Cold-Chain Facility (RM 0.00 collection fee)'.", size=9.5, color=c_slate, bold_prefix="• Self-Collection: ")
    
    add_heading(doc, "Correction C: 7 Working Days Delivery Sourcing Notice Maintained (Point 7)", level=2, color=c_blue)
    add_body(doc, "The dedicated delivery lead-time notice banner is prominently displayed at the top of the Delivery Address & Scheduling section:", size=9.5, color=c_slate)
    add_bullet(doc, "Notice Text: '🚚 Delivery Lead Time: Please allow up to 7 working days for order sourcing and cold-chain delivery arrangements. The available delivery date will be provided or confirmed by MST based on product availability and delivery scheduling.'", size=9.5, color=c_slate)
    
    # Section 3: Summary Matrix
    add_heading(doc, "3. Summary Checkout & Stripe Payment Matrix (Point 8)", level=1, color=c_navy)
    add_body(doc, "The table below summarizes the exact behavior and financial charges for each customer checkout scenario:", size=9.5, color=c_slate)
    
    matrix_table = doc.add_table(rows=5, cols=5)
    matrix_table.alignment = WD_TABLE_ALIGNMENT.CENTER
    matrix_table.autofit = False
    
    headers = ["Scenario", "Location / Postcode", "Cart Subtotal", "Delivery Fee (Stripe)", "Stripe Total Amount"]
    col_widths = [Inches(1.5), Inches(1.5), Inches(1.0), Inches(1.2), Inches(1.3)]
    
    # Header row
    hdr_row = matrix_table.rows[0]
    for idx, text in enumerate(headers):
        cell = hdr_row.cells[idx]
        cell.width = col_widths[idx]
        set_cell_background(cell, "0F172A")
        set_cell_margins(cell, 80, 80, 100, 100)
        p = cell.paragraphs[0]
        p.paragraph_format.space_before = Pt(0)
        p.paragraph_format.space_after = Pt(0)
        run = p.add_run(text)
        run.font.name = "Calibri"
        run.font.size = Pt(8.5)
        run.font.bold = True
        run.font.color.rgb = RGBColor(255, 255, 255)
    
    rows_data = [
        ("Zone A (< RM150)", "Johor Bahru (79100)", "RM 90.00", "RM 10.00 (Zone A Fee)", "RM 100.00"),
        ("Zone A (≥ RM150)", "Johor Bahru (79100)", "RM 175.20", "RM 0.00 (Free Local)", "RM 175.20"),
        ("Self-Collection", "Counter 2 (SILC)", "RM 120.00", "RM 0.00 (Self-Collect)", "RM 120.00"),
        ("Outstation Order", "Skudai / Batu Pahat (81300)", "RM 175.20", "RM 0.00 (To Be Confirmed)", "RM 175.20 (Subtotal only)"),
    ]
    
    for r_idx, row_values in enumerate(rows_data, start=1):
        row = matrix_table.rows[r_idx]
        bg_color = "F8FAFC" if r_idx % 2 == 1 else "FFFFFF"
        for c_idx, val in enumerate(row_values):
            cell = row.cells[c_idx]
            cell.width = col_widths[c_idx]
            set_cell_background(cell, bg_color)
            set_cell_margins(cell, 60, 60, 100, 100)
            p = cell.paragraphs[0]
            p.paragraph_format.space_before = Pt(0)
            p.paragraph_format.space_after = Pt(0)
            run = p.add_run(val)
            run.font.name = "Calibri"
            run.font.size = Pt(8.5)
            if c_idx == 4:
                run.font.bold = True
                run.font.color.rgb = c_navy
            else:
                run.font.color.rgb = c_slate
    
    set_table_border(matrix_table, "CBD5E1")
    doc.add_paragraph().paragraph_format.space_after = Pt(8)
    
    # Section 4: Git Deployment & Regression Testing
    add_heading(doc, "4. Git Deployment & Regression Testing Readiness", level=1, color=c_navy)
    add_body(doc, "All modifications have been committed to source control and pushed to the live remote repository:", size=9.5, color=c_slate)
    
    add_bullet(doc, "Commit `2db60505`: Fixed `$currentAppLocale` undefined variable error prior to Stripe metadata construction.", size=9.5, color=c_slate, bold_prefix="• Locale Bug Fix: ")
    add_bullet(doc, "Commit `bf5e6b56`: Implemented postcode-authoritative delivery zone resolution and separated Zone A from Outstation checkout notice rendering.", size=9.5, color=c_slate, bold_prefix="• Delivery Zone & UI Notices: ")
    add_bullet(doc, "Repository: `https://github.com/Abdulrehman2015/seafood.git` on branch `main`.", size=9.5, color=c_slate, bold_prefix="• Remote Branch: ")
    
    add_callout(
        doc,
        "The system is now completely ready for your final regression test across the delivery scenarios (Zone A Delivery ≥ RM150, Zone A Delivery < RM150, Store Self-Collection, and Outstation Delivery). All Stripe Metadata fields, Order records, and Admin Panel fulfillment views will accurately reflect the selected delivery parameters.",
        title="Ready for Final Regression Testing",
        fill_hex="EFF6FF",
        border_hex="2563EB",
        text_color=c_blue
    )
    
    # Output file
    output_path = "f:\\My AI\\Sea Food\\seafood\\MST_Delivery_UAT_Corrections_and_Regression_Readiness_Report.docx"
    doc.save(output_path)
    print(f"Successfully generated: {output_path}")

if __name__ == "__main__":
    generate_report()
