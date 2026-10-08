import docx
from docx.shared import Inches, Pt, RGBColor
from docx.enum.text import WD_ALIGN_PARAGRAPH
from docx.enum.table import WD_TABLE_ALIGNMENT, WD_ALIGN_VERTICAL
from docx.oxml import parse_xml
from docx.oxml.ns import nsdecls, qn
from docx.oxml import OxmlElement
import os

# ─── Helper Functions ────────────────────────────────────────────────────────

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
        run.font.size = Pt(13.5)
    elif level == 2:
        run.font.size = Pt(11.5)
    elif level == 3:
        run.font.size = Pt(10.5)
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

def add_bullet(doc, text, size=10, color=None, bold_prefix=None):
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
    set_cell_margins(cell, top=120, bottom=120, left=160, right=160)
    
    # Left border highlight
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
        r_title.font.size = Pt(10.5)
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
    p_after.paragraph_format.space_after = Pt(4)

# ─── Main Document Generation ──────────────────────────────────────────────

def create_report():
    doc = docx.Document()
    
    # Page setup - Margins 0.75 in
    sections = doc.sections
    for s in sections:
        s.top_margin = Inches(0.75)
        s.bottom_margin = Inches(0.75)
        s.left_margin = Inches(0.75)
        s.right_margin = Inches(0.75)
    
    # Colors
    c_primary = RGBColor(15, 118, 110)    # Deep Teal
    c_dark = RGBColor(15, 23, 42)         # Slate 900
    c_sub = RGBColor(51, 65, 85)          # Slate 700
    c_gray = RGBColor(100, 116, 139)      # Slate 500
    c_blue = RGBColor(30, 64, 175)        # Blue 800
    c_green = RGBColor(22, 101, 52)       # Green 800
    
    # ─── HEADER TITLE BLOCK ──────────────────────────────────────────────────
    header_tbl = doc.add_table(rows=1, cols=1)
    header_tbl.alignment = WD_TABLE_ALIGNMENT.CENTER
    header_tbl.autofit = False
    
    h_cell = header_tbl.cell(0, 0)
    h_cell.width = Inches(6.5)
    set_cell_background(h_cell, "0F172A") # Deep slate/navy
    set_cell_margins(h_cell, top=160, bottom=160, left=180, right=180)
    
    hp = h_cell.paragraphs[0]
    hp.paragraph_format.space_before = Pt(0)
    hp.paragraph_format.space_after = Pt(2)
    hr1 = hp.add_run("MST IMPORT & EXPORT SDN. BHD. — E-COMMERCE PLATFORM\n")
    hr1.font.name = "Calibri"
    hr1.font.size = Pt(9.5)
    hr1.font.bold = True
    hr1.font.color.rgb = RGBColor(204, 251, 241) # Teal 100
    
    hr2 = hp.add_run("Technical Clarification & Final UAT Preparation Report\n")
    hr2.font.name = "Calibri"
    hr2.font.size = Pt(15)
    hr2.font.bold = True
    hr2.font.color.rgb = RGBColor(255, 255, 255)
    
    hr3 = hp.add_run("Formal Alignment: Email Retention (admin@mst.my), Stripe Test Permissions, Zone A/B Delivery Thresholds & Variable-Weight UAT Scope")
    hr3.font.name = "Calibri"
    hr3.font.size = Pt(9.5)
    hr3.font.italic = True
    hr3.font.color.rgb = RGBColor(226, 232, 240)
    
    # Metadata Block
    doc.add_paragraph().paragraph_format.space_after = Pt(2)
    
    meta_tbl = doc.add_table(rows=2, cols=2)
    meta_tbl.alignment = WD_TABLE_ALIGNMENT.CENTER
    meta_tbl.autofit = False
    set_table_border(meta_tbl, "E2E8F0")
    
    meta_data = [
        [("Prepared For:", " Wendy (Client Project Lead) & MST Management"),
         ("Date & Phase:", " October 2026 · Pre-UAT Alignment & Handover")],
        [("Prepared By:", " Abdul (Technical Lead / Solution Architect)"),
         ("Integration Status:", " Ready for Stripe Test Mode Configuration")]
    ]
    
    for r_idx, row in enumerate(meta_data):
        for c_idx, (label, val) in enumerate(row):
            cell = meta_tbl.cell(r_idx, c_idx)
            cell.width = Inches(3.25)
            set_cell_background(cell, "F8FAFC" if r_idx == 0 else "FFFFFF")
            set_cell_margins(cell, top=60, bottom=60, left=100, right=100)
            p = cell.paragraphs[0]
            p.paragraph_format.space_before = Pt(0)
            p.paragraph_format.space_after = Pt(0)
            rl = p.add_run(label)
            rl.font.name = "Calibri"
            rl.font.size = Pt(8.5)
            rl.font.bold = True
            rl.font.color.rgb = c_sub
            rv = p.add_run(val)
            rv.font.name = "Calibri"
            rv.font.size = Pt(8.5)
            rv.font.color.rgb = c_dark
    
    doc.add_paragraph().paragraph_format.space_after = Pt(4)
    
    # ─── EXECUTIVE SUMMARY ───────────────────────────────────────────────────
    add_heading(doc, "Executive Summary & Key Alignments", level=1, color=c_primary)
    add_body(doc, "Thank you for reviewing the integration specifications. We fully acknowledge and endorse all your directives. The development configuration has been aligned to retain existing working systems, maintain strict zero-password boundaries in Stripe Test Mode, adhere to the latest Zone A/B delivery fee schedule, and designate variable-weight seafood processing as Pending UAT.", size=10)
    
    add_callout(
        doc,
        "1. Email / SMTP: Retaining existing working configuration with admin@mst.my as the primary sender. Zero additional credentials required.\n"
        "2. Stripe Access: Sandbox Test Mode only with minimal restricted permissions (Checkout, PaymentIntents, Webhooks). Zero live access.\n"
        "3. Payment Methods: Displaying strictly enabled methods (Visa, Mastercard, FPX, and In-Store Cash for Walk-in).\n"
        "4. Delivery Rules: Zone A (≥RM150 Free / <RM150 RM10 Fee). Zone B / Outstation (No free delivery, custom quotation). 81300 Skudai locked in Zone B.\n"
        "5. Variable-Weight Flower Crab: Explicitly marked as 'Pending UAT' with both overweight adjustment and underweight refund scenarios ready for verification.\n"
        "6. Required Handover: Only Stripe Test Mode Keys (pk_test_... / sk_test_...) needed to start sandbox UAT.",
        title="Executive Summary of Confirmations",
        fill_hex="F0FDF4",
        border_hex="16A34A"
    )

    # ─── SECTION 1: EMAIL CONFIGURATION ──────────────────────────────────────
    add_heading(doc, "1. Email / SMTP Configuration — Retaining Existing Setup", level=1, color=c_primary)
    add_body(doc, "We completely agree with your preference to maintain the current, proven email setup rather than making unnecessary adjustments.", size=10)
    
    add_bullet(doc, "Because the test email was successfully received at admin@mst.my and the SMTP connection is active, no additional SMTP credentials, third-party mail accounts, or passwords are required.", bold_prefix="No Additional Credentials Required: ")
    add_bullet(doc, "All transactional order confirmations, itemized receipts, customer invoices, and collection tokens will continue to be sent from admin@mst.my under the sender brand 'MST Marine Foods'.", bold_prefix="Official Sender Address: ")
    add_bullet(doc, "You do not need to create or manage orders@mst.my. All customer replies to confirmation emails will route directly to your existing admin@mst.my inbox, avoiding fragmented mail management.", bold_prefix="Single Inbox Management: ")
    
    # ─── SECTION 2: STRIPE ACCESS PERMISSIONS ────────────────────────────────
    add_heading(doc, "2. Stripe Access & Permission Breakdown (Test Mode Only)", level=1, color=c_primary)
    add_body(doc, "In accordance with your security requirements, we will operate strictly in MST's Stripe Test Mode. Below is the precise breakdown of the three minimal permissions required for website checkout integration and why each is necessary:", size=10)
    
    perm_table = doc.add_table(rows=4, cols=3)
    perm_table.alignment = WD_TABLE_ALIGNMENT.CENTER
    perm_table.autofit = False
    set_table_border(perm_table, "CBD5E1")
    
    p_headers = ["Permission Area", "Access Level", "Specific Technical & Operational Purpose"]
    for i, h in enumerate(p_headers):
        cell = perm_table.cell(0, i)
        set_cell_background(cell, "0F766E")
        set_cell_margins(cell, top=70, bottom=70, left=100, right=100)
        p = cell.paragraphs[0]
        r = p.add_run(h)
        r.font.name = "Calibri"
        r.font.size = Pt(9)
        r.font.bold = True
        r.font.color.rgb = RGBColor(255, 255, 255)
        
    perm_rows = [
        ("Checkout Sessions", "Write", "Enables the website backend to initiate Stripe's official hosted checkout session and redirect the customer securely to pay."),
        ("Payment Intents & Charges", "Write / Read", "Creates the transaction intent, verifies payment authorization status, and supports variable-weight balance adjustments / refunds."),
        ("Webhook Endpoints", "Write", "Registers the website callback URL (/webhook/stripe) so Stripe instantly notifies our server when a customer completes payment, automatically updating the order status to 'Paid'.")
    ]
    
    p_widths = [Inches(1.8), Inches(1.2), Inches(3.5)]
    for r_idx, (t1, t2, t3) in enumerate(perm_rows, start=1):
        for c_idx, val in enumerate([t1, t2, t3]):
            cell = perm_table.cell(r_idx, c_idx)
            cell.width = p_widths[c_idx]
            set_cell_background(cell, "F8FAFC" if r_idx % 2 == 1 else "FFFFFF")
            set_cell_margins(cell, top=60, bottom=60, left=100, right=100)
            p = cell.paragraphs[0]
            p.paragraph_format.space_before = Pt(0)
            p.paragraph_format.space_after = Pt(0)
            r = p.add_run(val)
            r.font.name = "Calibri"
            r.font.size = Pt(8.5)
            r.font.color.rgb = c_dark
            
    doc.add_paragraph().paragraph_format.space_after = Pt(4)
    
    add_callout(
        doc,
        "Zero Sensitive Access: This restricted scope strictly blocks access to MST bank accounts, payout schedules, team member permissions, and sensitive financial logs. No Live Mode keys and no real-money transactions will be requested or used.",
        title="Zero-Password & Zero-Financial-Risk Guarantee",
        fill_hex="EFF6FF",
        border_hex="3B82F6"
    )

    # ─── SECTION 3: PAYMENT METHODS ALIGNMENT ────────────────────────────────
    add_heading(doc, "3. Payment Methods & Website UI Alignment", level=1, color=c_primary)
    add_body(doc, "We confirm that the payment badges and options displayed across the website will strictly reflect what is actively enabled on MST's own Stripe account:", size=10)
    
    add_bullet(doc, "Active on checkout for all major Malaysian & international cards (Visa, Mastercard).", bold_prefix="Credit & Debit Cards: ")
    add_bullet(doc, "Active on checkout for direct debit across Malaysian banks (Maybank2u, CIMB Clicks, Public Bank, Hong Leong, RHB, etc.).", bold_prefix="FPX Online Banking: ")
    add_bullet(doc, "Available exclusively on the Walk-in Self-Collection portal (/walkin/checkout) for in-person pickups at the SILC facility.", bold_prefix="In-Store Cash (Counter 2): ")
    add_bullet(doc, "No generic or unenabled payment logos will be displayed on the website.", bold_prefix="Strict UI Accuracy: ")

    # ─── SECTION 4: DELIVERY RULES ───────────────────────────────────────────
    add_heading(doc, "4. Updated Delivery Rules & Zone Configuration", level=1, color=c_primary)
    add_body(doc, "The delivery calculation engine and technical documentation have been strictly aligned with MST's confirmed rules:", size=10)
    
    deliv_table = doc.add_table(rows=4, cols=4)
    deliv_table.alignment = WD_TABLE_ALIGNMENT.CENTER
    deliv_table.autofit = False
    set_table_border(deliv_table, "CBD5E1")
    
    d_headers = ["Zone", "Geographic Coverage", "Order Threshold", "Delivery Fee"]
    for i, h in enumerate(d_headers):
        cell = deliv_table.cell(0, i)
        set_cell_background(cell, "0F766E")
        set_cell_margins(cell, top=70, bottom=70, left=100, right=100)
        p = cell.paragraphs[0]
        r = p.add_run(h)
        r.font.name = "Calibri"
        r.font.size = Pt(9)
        r.font.bold = True
        r.font.color.rgb = RGBColor(255, 255, 255)
        
    deliv_rows = [
        ("Zone A (Local)", "Iskandar Puteri, Nusajaya, Johor Bahru Central", "Subtotal ≥ RM 150.00", "RM 0.00 (Free Standard Delivery)"),
        ("Zone A (Local)", "Iskandar Puteri, Nusajaya, Johor Bahru Central", "Subtotal < RM 150.00", "RM 10.00 Standard Delivery Fee"),
        ("Zone B / Outstation", "Skudai (81300), Northern Johor & Outstation", "Any Order Amount", "No Free Delivery (Quoted Separately)")
    ]
    
    d_widths = [Inches(1.5), Inches(2.1), Inches(1.5), Inches(1.4)]
    for r_idx, (t1, t2, t3, t4) in enumerate(deliv_rows, start=1):
        for c_idx, val in enumerate([t1, t2, t3, t4]):
            cell = deliv_table.cell(r_idx, c_idx)
            cell.width = d_widths[c_idx]
            set_cell_background(cell, "F8FAFC" if r_idx % 2 == 1 else "FFFFFF")
            set_cell_margins(cell, top=60, bottom=60, left=100, right=100)
            p = cell.paragraphs[0]
            p.paragraph_format.space_before = Pt(0)
            p.paragraph_format.space_after = Pt(0)
            r = p.add_run(val)
            r.font.name = "Calibri"
            r.font.size = Pt(8.5)
            r.font.color.rgb = c_dark
            
    doc.add_paragraph().paragraph_format.space_after = Pt(4)
    
    add_bullet(doc, "RM 150.00 is strictly a free-delivery qualifying threshold, NOT a minimum order barrier. Customers are free to order any basket value (e.g. RM 40.00) by paying the RM 10.00 standard delivery fee.", bold_prefix="Threshold vs Minimum Order: ")
    add_bullet(doc, "81300 Skudai is permanently hardcoded under Zone B / Outstation. Orders with postcode 81300 will require a separate cold-chain transportation quotation.", bold_prefix="Skudai (81300) Classification: ")

    # ─── SECTION 5: VARIABLE-WEIGHT FLOWER CRAB WORKFLOW (PENDING UAT) ────────
    add_heading(doc, "5. Variable-Weight Flower Crab — Designated as 'Pending UAT'", level=1, color=c_primary)
    add_body(doc, "We acknowledge and agree that the variable-weight workflow remains marked as PENDING UAT until you have personally executed and approved the full end-to-end testing cycle.", size=10)
    
    add_heading(doc, "5.1 End-to-End UAT Test Scenarios Prepared for Verification", level=2, color=c_dark)
    
    add_callout(
        doc,
        "• Initial Order: Customer orders 1.00 kg Reference Weight @ RM 68.00/kg (Authorized: RM 68.00).\n"
        "• Warehouse Scale Entry: Staff weighs live catch at SILC facility and enters 1.15 kg into Admin Order detail.\n"
        "• Auto Recalculation: Subtotal updates to RM 78.20 (Variance: +RM 10.20 balance due).\n"
        "• 1-Click WhatsApp Dispatch: Admin clicks '💬 Send via WhatsApp' to notify customer with revised itemization and payment link.\n"
        "• Settlement: Customer pays supplementary +RM 10.20 online or at Counter 2 upon pickup → Status updates to 'Paid / Ready'.",
        title="Scenario A: Overweight Catch (+RM 10.20 Balance Due)",
        fill_hex="F0FDF4",
        border_hex="16A34A"
    )
    
    add_callout(
        doc,
        "• Initial Order: Customer orders 1.00 kg Reference Weight @ RM 68.00/kg (Paid: RM 68.00).\n"
        "• Warehouse Scale Entry: Staff weighs catch and enters actual 0.90 kg into Admin Order detail.\n"
        "• Auto Recalculation: Subtotal updates to RM 61.20 (Variance: -RM 6.80 credit due to customer).\n"
        "• 1-Click WhatsApp Dispatch: Admin notifies customer of exact weight and automatic refund/credit.\n"
        "• Refund / Credit Processing: System triggers Stripe partial refund of RM 6.80 to customer card / account credit → Status updates to 'Payment Adjusted / Ready'.",
        title="Scenario B: Underweight Catch (-RM 6.80 Refund / Credit)",
        fill_hex="FEF3C7",
        border_hex="D97706"
    )

    # ─── SECTION 6: MINIMAL REQUIRED ACCESS & NEXT STEPS ─────────────────────
    add_heading(doc, "6. Next Steps & Minimal Required Access", level=1, color=c_primary)
    add_body(doc, "To move immediately into sandbox configuration and conduct the UAT walkthrough with you, the ONLY required item from your side is:", size=10)
    
    add_callout(
        doc,
        "MST Stripe Account Test Mode Keys Only:\n"
        "1. Publishable Key: pk_test_...\n"
        "2. Secret Key: sk_test_...  (OR a Restricted Key rk_test_... with Checkout, PaymentIntents, and Webhooks permissions)\n\n"
        "• Zero email credentials or mail passwords required.\n"
        "• Zero Live Mode access or real money required.",
        title="Minimal Access Requirement for Test Mode Setup",
        fill_hex="F1F5F9",
        border_hex="475569"
    )
    
    add_body(doc, "Once you provide the Test Mode keys, we will connect the sandbox environment, verify the webhook listener, and deliver a comprehensive UAT testing link for your personal evaluation.", size=10)
    
    doc.add_paragraph().paragraph_format.space_after = Pt(8)
    
    # Sign-off box
    sign_tbl = doc.add_table(rows=1, cols=1)
    sign_tbl.alignment = WD_TABLE_ALIGNMENT.CENTER
    sign_tbl.autofit = False
    s_cell = sign_tbl.cell(0, 0)
    s_cell.width = Inches(6.5)
    set_cell_background(s_cell, "F8FAFC")
    set_cell_margins(s_cell, top=100, bottom=100, left=140, right=140)
    
    sp = s_cell.paragraphs[0]
    sp.paragraph_format.space_before = Pt(0)
    sp.paragraph_format.space_after = Pt(0)
    sr1 = sp.add_run("Document Prepared & Certified by:\n")
    sr1.font.name = "Calibri"
    sr1.font.size = Pt(8.5)
    sr1.font.italic = True
    sr1.font.color.rgb = c_gray
    
    sr2 = sp.add_run("Abdul — Lead Technical Solution Architect\n")
    sr2.font.name = "Calibri"
    sr2.font.size = Pt(10)
    sr2.font.bold = True
    sr2.font.color.rgb = c_dark
    
    sr3 = sp.add_run("MST Import & Export Sdn. Bhd. E-Commerce Development Team")
    sr3.font.name = "Calibri"
    sr3.font.size = Pt(9)
    sr3.font.color.rgb = c_sub
    
    # Save document
    output_filename = "MST_Client_Integration_Clarification_and_Workflow_Report.docx"
    doc.save(output_filename)
    print(f"Successfully generated updated report: {output_filename}")

if __name__ == "__main__":
    create_report()
