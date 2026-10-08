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

def generate_letter_docx():
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
    
    r2 = hp.add_run("Formal Technical Response & UAT Alignment Document\n")
    r2.font.name = "Calibri"
    r2.font.size = Pt(14)
    r2.font.bold = True
    r2.font.color.rgb = RGBColor(255, 255, 255)
    
    r3 = hp.add_run("Ref: Email Retention, Stripe Test Permissions, Delivery Rules & Variable-Weight UAT Protocol")
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
         ("STATUS:", " Confirmed Alignment · Ready for Test Mode Handover")]
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
    add_body(doc, "Thank you for the clear feedback and instructions. We have reviewed all your requirements and aligned the platform configuration accordingly. Below is our formal response and technical breakdown addressing each point in detail:", size=10)
    
    # ─── SECTION 1 ───────────────────────────────────────────────────────────
    add_heading(doc, "1. Email / SMTP Configuration — Retaining Existing Setup", level=1, color=c_primary)
    add_bullet(doc, "Since the test email was successfully received at admin@mst.my and the current SMTP configuration is working reliably, we will keep the existing setup exactly as it is without unnecessary modifications.", bold_prefix="No Additional Credentials Required: ")
    add_bullet(doc, "All transactional order confirmations, invoices, receipts, and collection token notices will continue to be sent from admin@mst.my under the sender display name 'MST Marine Foods'.", bold_prefix="Official Sender Address: ")
    add_bullet(doc, "You do not need to create or manage orders@mst.my. All customer replies to confirmation emails will route directly into your active admin@mst.my inbox.", bold_prefix="Unified Inbox: ")
    
    # ─── SECTION 2 ───────────────────────────────────────────────────────────
    add_heading(doc, "2. Stripe Access & Permission Breakdown (Test Mode Only)", level=1, color=c_primary)
    add_body(doc, "We confirm that only Stripe Test Mode will be configured at this stage. No Live Mode keys and no real-money transactions will be used.", size=9.5)
    
    perm_tbl = doc.add_table(rows=4, cols=3)
    perm_tbl.alignment = WD_TABLE_ALIGNMENT.CENTER
    perm_tbl.autofit = False
    set_table_border(perm_tbl, "CBD5E1")
    
    p_headers = ["Permission Area", "Access Level", "Specific Technical & Operational Purpose"]
    for i, h in enumerate(p_headers):
        cell = perm_tbl.cell(0, i)
        set_cell_background(cell, "0F766E")
        set_cell_margins(cell, top=60, bottom=60, left=80, right=80)
        p = cell.paragraphs[0]
        r = p.add_run(h)
        r.font.name = "Calibri"
        r.font.size = Pt(8.5)
        r.font.bold = True
        r.font.color.rgb = RGBColor(255, 255, 255)
        
    p_rows = [
        ("Checkout Sessions", "Write", "Enables website to initiate Stripe's secure hosted checkout page for customers."),
        ("Payment Intents / Charges", "Write / Read", "Creates transaction intents, verifies payment status, and supports variable-weight adjustments."),
        ("Webhook Endpoints", "Write", "Connects Stripe to the website (/webhook/stripe) to automatically update order status to 'Paid' instantly upon payment completion.")
    ]
    
    p_widths = [Inches(1.8), Inches(1.2), Inches(3.5)]
    for r_idx, (t1, t2, t3) in enumerate(p_rows, start=1):
        for c_idx, val in enumerate([t1, t2, t3]):
            cell = perm_tbl.cell(r_idx, c_idx)
            cell.width = p_widths[c_idx]
            set_cell_background(cell, "F8FAFC" if r_idx % 2 == 1 else "FFFFFF")
            set_cell_margins(cell, top=50, bottom=50, left=80, right=80)
            p = cell.paragraphs[0]
            p.paragraph_format.space_before = Pt(0)
            p.paragraph_format.space_after = Pt(0)
            r = p.add_run(val)
            r.font.name = "Calibri"
            r.font.size = Pt(8)
            r.font.color.rgb = c_dark
            
    doc.add_paragraph().paragraph_format.space_after = Pt(3)
    add_bullet(doc, "This restricted permission set strictly blocks access to MST bank accounts, payout schedules, team member permissions, and sensitive financial logs.", bold_prefix="Zero-Risk Guarantee: ")

    # ─── SECTION 3 ───────────────────────────────────────────────────────────
    add_heading(doc, "3. Payment Methods & Website UI Alignment", level=1, color=c_primary)
    add_bullet(doc, "The website checkout and UI will display only the payment methods enabled on MST's own Stripe account (Credit/Debit Card: Visa & Mastercard, FPX Online Banking, and in-store Cash at Counter 2 for Walk-in pickup).", bold_prefix="Strict Alignment: ")
    add_bullet(doc, "No unsupported or generic payment logos will be displayed.", bold_prefix="Accuracy: ")

    # ─── SECTION 4 ───────────────────────────────────────────────────────────
    add_heading(doc, "4. Updated Delivery Rules (Confirmed & Aligned)", level=1, color=c_primary)
    add_body(doc, "The delivery calculation engine has been updated to match MST's latest rules:", size=9.5)
    
    add_bullet(doc, "Orders ≥ RM 150.00 = Free Standard Delivery (RM 0.00). Orders < RM 150.00 = RM 10.00 Standard Delivery Fee.", bold_prefix="Zone A (Local JB / Iskandar Puteri / Nusajaya): ")
    add_bullet(doc, "Confirmed that RM 150.00 is the free-delivery qualifying threshold, NOT a minimum order requirement. Customers can place orders of any amount by paying the standard RM 10 fee.", bold_prefix="Threshold Clarification: ")
    add_bullet(doc, "No free delivery. Cold-chain transportation and packaging are quoted separately based on destination.", bold_prefix="Zone B / Outstation: ")
    add_bullet(doc, "Postcode 81300 Skudai is permanently classified under Zone B / Outstation.", bold_prefix="81300 Skudai: ")

    # ─── SECTION 5 ───────────────────────────────────────────────────────────
    add_heading(doc, "5. Variable-Weight Flower Crab (Designated as 'Pending UAT')", level=1, color=c_primary)
    add_body(doc, "We have noted this workflow as PENDING UAT. It will not be marked completed until you have personally tested and verified both operational cases:", size=9.5)
    
    add_callout(
        doc,
        "• Reference Weight: 1.00 kg (RM 68.00) → Scaled Weight entered: 1.15 kg (RM 78.20).\n"
        "• System recalculates variance (+RM 10.20) → Generates 1-click WhatsApp customer alert with payment link → Collects supplementary amount → Updates order status to 'Paid / Ready'.",
        title="UAT Scenario A: Overweight Catch (+RM 10.20 Balance Due)",
        fill_hex="F0FDF4",
        border_hex="16A34A"
    )
    
    add_callout(
        doc,
        "• Reference Weight: 1.00 kg (RM 68.00) → Scaled Weight entered: 0.90 kg (RM 61.20).\n"
        "• System recalculates variance (-RM 6.80) → Generates 1-click WhatsApp refund notice → Triggers Stripe partial refund / account credit → Updates order status to 'Payment Adjusted / Ready'.",
        title="UAT Scenario B: Underweight Catch (-RM 6.80 Refund / Credit)",
        fill_hex="FEF3C7",
        border_hex="D97706"
    )

    # ─── SECTION 6 ───────────────────────────────────────────────────────────
    add_heading(doc, "6. Next Steps & Minimal Required Handover", level=1, color=c_primary)
    add_body(doc, "To proceed with the Test Mode setup, the ONLY item required from your side is:", size=10)
    
    add_bullet(doc, "Publishable Key (pk_test_...) and Secret Key (sk_test_...) OR a Restricted Key (rk_test_... with the 3 permissions listed above).", bold_prefix="MST Stripe Test Mode Keys: ")
    add_bullet(doc, "No email credentials, mail passwords, or live Stripe keys are required.", bold_prefix="Zero Extra Credentials: ")
    
    add_body(doc, "Once you supply the Test Mode keys, we will connect the sandbox environment and provide you with the test links and test cards for your UAT walkthrough.", size=10)
    
    doc.add_paragraph().paragraph_format.space_after = Pt(8)
    
    # Signature
    add_body(doc, "Thank you,\nAbdul\nLead Technical Solution Architect\nMST Import & Export Sdn. Bhd. E-Commerce Development Team", size=10, bold=True)
    
    output_filename = "MST_Formal_Client_Response_Letter.docx"
    doc.save(output_filename)
    print(f"Successfully generated: {output_filename}")

if __name__ == "__main__":
    generate_letter_docx()
