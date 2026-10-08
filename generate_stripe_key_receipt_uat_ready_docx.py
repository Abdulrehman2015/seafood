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

def add_heading(doc, text, level=1, color=None, space_before=12, space_after=4):
    p = doc.add_paragraph()
    p.paragraph_format.space_before = Pt(space_before)
    p.paragraph_format.space_after = Pt(space_after)
    p.paragraph_format.keep_with_next = True
    run = p.add_run(text)
    run.font.name = "Calibri"
    run.font.bold = True
    if level == 1:
        run.font.size = Pt(12.5)
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
    
    p_after = doc.add_paragraph()
    p_after.paragraph_format.space_before = Pt(0)
    p_after.paragraph_format.space_after = Pt(3)

def generate_receipt_docx():
    doc = docx.Document()
    
    for s in doc.sections:
        s.top_margin = Inches(0.75)
        s.bottom_margin = Inches(0.75)
        s.left_margin = Inches(0.75)
        s.right_margin = Inches(0.75)
    
    c_primary = RGBColor(15, 118, 110)    # Deep Teal
    c_dark = RGBColor(15, 23, 42)         # Slate 900
    c_sub = RGBColor(51, 65, 85)          # Slate 700
    c_gray = RGBColor(100, 116, 139)      # Slate 500
    
    # ─── HEADER BANNER ───────────────────────────────────────────────────────
    header_tbl = doc.add_table(rows=1, cols=1)
    header_tbl.alignment = WD_TABLE_ALIGNMENT.CENTER
    header_tbl.autofit = False
    
    h_cell = header_tbl.cell(0, 0)
    h_cell.width = Inches(6.5)
    set_cell_background(h_cell, "0F172A")
    set_cell_margins(h_cell, top=140, bottom=140, left=160, right=160)
    
    hp = h_cell.paragraphs[0]
    hp.paragraph_format.space_before = Pt(0)
    hp.paragraph_format.space_after = Pt(2)
    
    r1 = hp.add_run("MST IMPORT & EXPORT SDN. BHD. — E-COMMERCE PLATFORM\n")
    r1.font.name = "Calibri"
    r1.font.size = Pt(9)
    r1.font.bold = True
    r1.font.color.rgb = RGBColor(204, 251, 241)
    
    r2 = hp.add_run("Stripe Restricted Test Key Integration & Handover Report\n")
    r2.font.name = "Calibri"
    r2.font.size = Pt(14)
    r2.font.bold = True
    r2.font.color.rgb = RGBColor(255, 255, 255)
    
    r3 = hp.add_run("Key Verification Completed · Test Sandbox Active · Ready for Variable-Weight & Store UAT")
    r3.font.name = "Calibri"
    r3.font.size = Pt(9)
    r3.font.italic = True
    r3.font.color.rgb = RGBColor(226, 232, 240)
    
    # Metadata Table
    doc.add_paragraph().paragraph_format.space_after = Pt(2)
    
    meta_tbl = doc.add_table(rows=2, cols=2)
    meta_tbl.alignment = WD_TABLE_ALIGNMENT.CENTER
    meta_tbl.autofit = False
    set_table_border(meta_tbl, "E2E8F0")
    
    meta_data = [
        [("TO:", " Wendy (Client Project Lead) & MST Management Team"),
         ("DATE:", " October 8, 2026")],
        [("FROM:", " Abdul (Lead Technical Solution Architect)"),
         ("STATUS:", " Key Installed & Tested Successfully · Sandbox Live for UAT")]
    ]
    
    for r_idx, row in enumerate(meta_data):
        for c_idx, (label, val) in enumerate(row):
            cell = meta_tbl.cell(r_idx, c_idx)
            cell.width = Inches(3.25)
            set_cell_background(cell, "F8FAFC" if r_idx == 0 else "FFFFFF")
            set_cell_margins(cell, top=50, bottom=50, left=80, right=80)
            p = cell.paragraphs[0]
            p.paragraph_format.space_before = Pt(0)
            p.paragraph_format.space_after = Pt(0)
            rl = p.add_run(label + " ")
            rl.font.name = "Calibri"
            rl.font.size = Pt(8.5)
            rl.font.bold = True
            rl.font.color.rgb = c_sub
            rv = p.add_run(val)
            rv.font.name = "Calibri"
            rv.font.size = Pt(8.5)
            rv.font.color.rgb = c_dark
            
    doc.add_paragraph().paragraph_format.space_after = Pt(4)
    
    add_body(doc, "Hi Wendy,", size=10, bold=True)
    add_body(doc, "Thank you very much for providing the new Stripe Test Mode Restricted API Key. We have successfully received, configured, and verified the key on the MST e-commerce integration platform.", size=9.5)
    
    # ─── SECTION 1: VERIFICATION SUMMARY ─────────────────────────────────────
    add_heading(doc, "1. Key Verification & API Handshake Results", level=1, color=c_primary)
    
    verify_tbl = doc.add_table(rows=5, cols=3)
    verify_tbl.alignment = WD_TABLE_ALIGNMENT.CENTER
    verify_tbl.autofit = False
    set_table_border(verify_tbl, "CBD5E1")
    
    v_headers = ["Test Item / Feature", "Stripe API Handshake Status", "Operational Verification Note"]
    for i, h in enumerate(v_headers):
        cell = verify_tbl.cell(0, i)
        set_cell_background(cell, "0F766E")
        set_cell_margins(cell, top=60, bottom=60, left=80, right=80)
        p = cell.paragraphs[0]
        r = p.add_run(h)
        r.font.name = "Calibri"
        r.font.size = Pt(8.5)
        r.font.bold = True
        r.font.color.rgb = RGBColor(255, 255, 255)
        
    v_rows = [
        ("Stripe Account Link", "VERIFIED (Account acct_1UO5gZJuOivXc24T)", "Correctly targeted to MST's official Stripe Test Mode environment."),
        ("Checkout Sessions API", "PASSED (Live test session generated)", "Backend successfully creates hosted payment sessions and redirects customers."),
        ("PaymentIntents API", "PASSED (Intent pi_3UOCUB... verified)", "Authorizations and balance capture execute seamlessly in sandbox mode."),
        ("Security Boundary Check", "PASSED (Zero Live Access Guaranteed)", "Account-level live operations and bank payouts are strictly inaccessible.")
    ]
    
    v_widths = [Inches(1.8), Inches(2.2), Inches(2.5)]
    for r_idx, (t1, t2, t3) in enumerate(v_rows, start=1):
        for c_idx, val in enumerate([t1, t2, t3]):
            cell = verify_tbl.cell(r_idx, c_idx)
            cell.width = v_widths[c_idx]
            set_cell_background(cell, "F0FDF4" if "PASSED" in t2 or "VERIFIED" in t2 else "FFFFFF")
            set_cell_margins(cell, top=50, bottom=50, left=80, right=80)
            p = cell.paragraphs[0]
            p.paragraph_format.space_before = Pt(0)
            p.paragraph_format.space_after = Pt(0)
            r = p.add_run(val)
            r.font.name = "Calibri"
            r.font.size = Pt(8)
            r.font.color.rgb = c_dark
            
    doc.add_paragraph().paragraph_format.space_after = Pt(3)
    
    add_callout(
        doc,
        "Zero Live Risk Certified: The installed key is strictly restricted to Stripe Test Mode. No actual funds, live credit card charges, or bank account linkages are exposed or touched during checkout.",
        title="Security & Isolation Guarantee",
        fill_hex="F0FDF4",
        border_hex="16A34A"
    )

    # ─── SECTION 2: HOW TO TEST CHECKOUT (UAT TESTING GUIDE) ─────────────────
    add_heading(doc, "2. User Acceptance Testing (UAT) Walkthrough Guide", level=1, color=c_primary)
    add_body(doc, "You and your team can now test the full shopping and payment workflow using Stripe's official Test Mode cards. No real money will be charged.", size=9.5)
    
    add_bullet(doc, "4242 • 4242 • 4242 • 4242  (Expiry: Any future date e.g. 12/28 | CVC: Any 3 digits e.g. 123)", bold_prefix="Standard Test Card (Visa): ")
    add_bullet(doc, "5555 • 5555 • 5555 • 4444  (Expiry: Any future date | CVC: Any 3 digits)", bold_prefix="Standard Test Card (Mastercard): ")
    add_bullet(doc, "Any 5 digits (e.g. 79100 for Iskandar Puteri / 81300 for Skudai Zone B).", bold_prefix="Billing Postcode: ")
    
    # ─── SECTION 3: PENDING UAT SCOPE ────────────────────────────────────────
    add_heading(doc, "3. Testing the Variable-Weight Flower Crab Workflow", level=1, color=c_primary)
    add_body(doc, "With Stripe Test Mode active, you can now execute the Pending UAT verification for Flower Crab:", size=9.5)
    
    add_bullet(doc, "Customer places an order for 1.00 kg Reference Weight (RM 68.00) and completes checkout using the test card above.", bold_prefix="Step 1 (Order Placement): ")
    add_bullet(doc, "Warehouse staff logs into Admin Panel → Orders → Show Order → Enters actual weight (e.g. 1.15 kg for overweight or 0.90 kg for underweight).", bold_prefix="Step 2 (Warehouse Scale Entry): ")
    add_bullet(doc, "System calculates the variance (+RM 10.20 or -RM 6.80) and provides a 1-Click WhatsApp template button.", bold_prefix="Step 3 (Variance Calculation): ")
    add_bullet(doc, "Admin sends WhatsApp update or processes adjustment, updating the order to 'Paid / Ready'.", bold_prefix="Step 4 (Completion): ")

    # ─── SECTION 4: NEXT STEPS ───────────────────────────────────────────────
    add_heading(doc, "4. Summary of Completed Alignments", level=1, color=c_primary)
    add_bullet(doc, "Retained existing working SMTP setup with admin@mst.my as official sender.", bold_prefix="1. Email / SMTP: ")
    add_bullet(doc, "Restricted key installed and verified with Checkout Sessions & PaymentIntents.", bold_prefix="2. Stripe Integration: ")
    add_bullet(doc, "Zone A (≥RM150 Free / <RM150 RM10) and Zone B (81300 Skudai custom quotation).", bold_prefix="3. Delivery Engine: ")
    add_bullet(doc, "Ready for your review and walkthrough.", bold_prefix="4. Variable-Weight Scope: ")
    
    doc.add_paragraph().paragraph_format.space_after = Pt(8)
    
    # Signature
    add_body(doc, "Thank you,\nAbdul\nLead Technical Solution Architect\nMST Import & Export Sdn. Bhd. E-Commerce Development Team", size=9.5, bold=True)
    
    output_filename = "MST_Stripe_Test_Key_Receipt_and_UAT_Ready_Report.docx"
    doc.save(output_filename)
    print(f"Successfully generated: {output_filename}")

if __name__ == "__main__":
    generate_receipt_docx()
