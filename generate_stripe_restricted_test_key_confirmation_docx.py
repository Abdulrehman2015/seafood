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
        run.font.size = Pt(13)
    elif level == 2:
        run.font.size = Pt(11)
    elif level == 3:
        run.font.size = Pt(10)
    if color:
        run.font.color.rgb = color
    return p

def add_body(doc, text, size=10, italic=False, bold=False, color=None, space_before=2, space_after=4):
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

def generate_confirmation_docx():
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
    
    r2 = hp.add_run("Restricted Stripe Test Key Confirmation & UAT Staging Memo\n")
    r2.font.name = "Calibri"
    r2.font.size = Pt(14)
    r2.font.bold = True
    r2.font.color.rgb = RGBColor(255, 255, 255)
    
    r3 = hp.add_run("Formal Security Certification: Restricted Key Sufficiency, Zero Live Access Guarantee & Pending UAT Scope")
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
         ("DATE:", " October 7, 2026")],
        [("FROM:", " Abdul (Lead Technical Solution Architect)"),
         ("STATUS:", " Confirmed Security Architecture · Awaiting Restricted Test Key")]
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
    add_body(doc, "Thank you for your confirmation. We are pleased that all key operational items (email retention via admin@mst.my, the RM150 Zone A threshold, 81300 Skudai classification, and the Pending UAT designation for Flower Crab) are now fully aligned.", size=10)
    
    # ─── SECTION 1: CONFIRMATION OF RESTRICTED TEST KEY ─────────────────────
    add_heading(doc, "1. Formal Confirmation on Restricted Stripe Test Key", level=1, color=c_primary)
    
    add_bullet(doc, "A Restricted Test Key (prefixed with rk_test_...) is 100% sufficient for all website checkout functions, webhook event handling, order status transitions, and sandbox testing.", bold_prefix="1. 100% Sufficient for Integration: ")
    add_bullet(doc, "The key will be stored solely inside the website backend's secure .env environment configuration and will only interact with the MST e-commerce website.", bold_prefix="2. Strictly Limited to MST Website: ")
    add_bullet(doc, "In Stripe's infrastructure, any key starting with rk_test_... is cryptographically isolated to Test Mode sandbox. It is technically impossible for a Test Key to process live transactions, debit real customer cards, touch bank accounts, or execute live financial charges.", bold_prefix="3. Zero Live Mode Access Guaranteed: ")
    
    add_callout(
        doc,
        "Security Certification: Providing a Restricted Test Key provides complete sandbox capabilities while guaranteeing zero exposure to live customer funds, payout schedules, or company banking information.",
        title="Zero-Financial-Risk Certification",
        fill_hex="F0FDF4",
        border_hex="16A34A"
    )

    # ─── SECTION 2: HOW TO GENERATE THE KEY ──────────────────────────────────
    add_heading(doc, "2. Quick Guide to Generate Your Restricted Test Key", level=1, color=c_primary)
    add_body(doc, "When generating the key in your Stripe Dashboard, please follow these exact steps:", size=9.5)
    
    add_bullet(doc, "Log into your Stripe Dashboard and ensure the 'Test Mode' toggle (orange badge) is switched ON.", bold_prefix="Step 1: Switch to Test Mode: ")
    add_bullet(doc, "Go to Developers → API keys → Click '+ Create restricted key'.", bold_prefix="Step 2: Restricted Keys Section: ")
    add_bullet(doc, "Set Key Name as: 'MST Website Test Connector'.", bold_prefix="Step 3: Key Name: ")
    add_bullet(doc, "Set the following 3 permissions to 'Write' (leave all others as 'None'):\n"
                    "  • Checkout Sessions → Write\n"
                    "  • Payment Intents & Charges → Write\n"
                    "  • Webhook Endpoints → Write", bold_prefix="Step 4: Minimal Permissions: ")
    add_bullet(doc, "Click 'Create key' and copy the generated Restricted Secret Key (rk_test_...) and Publishable Key (pk_test_...).", bold_prefix="Step 5: Copy Keys: ")

    # ─── SECTION 3: VARIABLE-WEIGHT UAT RECONFIRMATION ───────────────────────
    add_heading(doc, "3. Variable-Weight Flower Crab Workflow (Pending UAT)", level=1, color=c_primary)
    add_body(doc, "We reconfirm that the Variable-Weight Flower Crab workflow is officially cataloged as PENDING UAT. It will remain in this status until you have conducted your personal walkthrough covering:", size=9.5)
    
    add_bullet(doc, "Entering actual scale weight at the SILC warehouse.", bold_prefix="1. Scale Entry: ")
    add_bullet(doc, "System auto-recalculation of item subtotal and price variance.", bold_prefix="2. Recalculation: ")
    add_bullet(doc, "1-Click WhatsApp customer notification dispatch with formatted variance details.", bold_prefix="3. WhatsApp Alert: ")
    add_bullet(doc, "Collecting supplementary balance (+RM 10.20 for overweight catch).", bold_prefix="4. Overweight Settlement: ")
    add_bullet(doc, "Processing automated Stripe partial refund / store credit (-RM 6.80 for underweight catch).", bold_prefix="5. Underweight Refund: ")
    add_bullet(doc, "Final transition to 'Paid / Ready for Collection or Delivery'.", bold_prefix="6. Order Status Update: ")

    # ─── SECTION 4: NEXT STEPS ───────────────────────────────────────────────
    add_heading(doc, "4. Next Steps", level=1, color=c_primary)
    add_body(doc, "Whenever you are ready, please provide the generated Test Mode keys (pk_test_... and rk_test_...). We will connect the sandbox environment immediately and notify you so you can perform the full UAT walkthrough.", size=10)
    
    doc.add_paragraph().paragraph_format.space_after = Pt(8)
    
    # Signature
    add_body(doc, "Thank you,\nAbdul\nLead Technical Solution Architect\nMST Import & Export Sdn. Bhd. E-Commerce Development Team", size=10, bold=True)
    
    output_filename = "MST_Stripe_Restricted_Test_Key_Confirmation.docx"
    doc.save(output_filename)
    print(f"Successfully generated: {output_filename}")

if __name__ == "__main__":
    generate_confirmation_docx()
