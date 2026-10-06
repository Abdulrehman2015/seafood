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

def set_cell_margins(cell, top=90, bottom=90, left=130, right=130):
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

def set_table_border(table, color="D1D5DB"):
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
        run.font.size = Pt(14)
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
    p.paragraph_format.space_before = Pt(1.5)
    p.paragraph_format.space_after = Pt(2.5)
    p.paragraph_format.left_indent = Inches(0.25)
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

def add_callout(doc, text, title="SECURITY & BEST PRACTICE NOTICE", bg_hex="EFF6FF", border_hex="3B82F6", title_color_hex="1E40AF"):
    tbl = doc.add_table(rows=1, cols=1)
    tbl.alignment = WD_TABLE_ALIGNMENT.LEFT
    cell = tbl.rows[0].cells[0]
    set_cell_background(cell, bg_hex)
    set_cell_margins(cell, 100, 100, 140, 140)
    
    tcPr = cell._tc.get_or_add_tcPr()
    tcBorders = parse_xml(
        f'<w:tcBorders {nsdecls("w")}>'
        f'<w:top w:val="none"/>'
        f'<w:left w:val="single" w:sz="24" w:space="0" w:color="{border_hex}"/>'
        f'<w:bottom w:val="none"/>'
        f'<w:right w:val="none"/>'
        f'</w:tcBorders>'
    )
    tcPr.append(tcBorders)
    
    p = cell.paragraphs[0]
    p.paragraph_format.space_before = Pt(2)
    p.paragraph_format.space_after = Pt(2)
    r_t = p.add_run(f"🔒 {title}\n")
    r_t.font.name = "Calibri"
    r_t.font.size = Pt(10)
    r_t.font.bold = True
    r_t.font.color.rgb = RGBColor(int(title_color_hex[0:2], 16), int(title_color_hex[2:4], 16), int(title_color_hex[4:6], 16))
    
    r_b = p.add_run(text)
    r_b.font.name = "Calibri"
    r_b.font.size = Pt(9.5)
    r_b.font.color.rgb = RGBColor(0x1F, 0x29, 0x37)
    
    p_space = doc.add_paragraph()
    p_space.paragraph_format.space_before = Pt(2)
    p_space.paragraph_format.space_after = Pt(4)

def add_divider(doc):
    p = doc.add_paragraph()
    p.paragraph_format.space_before = Pt(4)
    p.paragraph_format.space_after = Pt(4)
    pPr = p._p.get_or_add_pPr()
    pBdr = OxmlElement('w:pBdr')
    bottom = OxmlElement('w:bottom')
    bottom.set(qn('w:val'), 'single')
    bottom.set(qn('w:sz'), '4')
    bottom.set(qn('w:space'), '1')
    bottom.set(qn('w:color'), 'CBD5E1')
    pBdr.append(bottom)
    pPr.append(pBdr)

# ─── MAIN BUILDER ────────────────────────────────────────────────────────────

def build_credentials_guide():
    doc = docx.Document()

    for section in doc.sections:
        section.top_margin = Inches(0.7)
        section.bottom_margin = Inches(0.7)
        section.left_margin = Inches(0.8)
        section.right_margin = Inches(0.8)

    primary_navy   = RGBColor(9, 26, 54)     # #091a36
    primary_teal   = RGBColor(15, 118, 110)  # #0f766e
    accent_blue    = RGBColor(2, 132, 199)   # #0284c7
    dark_slate     = RGBColor(15, 23, 42)    # #0f172a
    body_slate     = RGBColor(51, 65, 85)    # #334155
    success_green  = RGBColor(22, 101, 52)   # #166534
    alert_red      = RGBColor(153, 27, 27)   # #991b1b

    # ─── HEADER ───────────────────────────────────────────────────────────────
    p_hdr = doc.add_paragraph()
    p_hdr.paragraph_format.space_after = Pt(2)
    r1 = p_hdr.add_run("MST IMPORT & EXPORT SDN. BHD. — E-COMMERCE PLATFORM\n")
    r1.font.name = "Calibri"
    r1.font.size = Pt(9)
    r1.font.bold = True
    r1.font.color.rgb = primary_teal

    r2 = p_hdr.add_run("OFFICIAL INTEGRATION & CREDENTIAL HANDOVER GUIDE\n")
    r2.font.name = "Calibri"
    r2.font.size = Pt(16.5)
    r2.font.bold = True
    r2.font.color.rgb = primary_navy

    r3 = p_hdr.add_run("Step-by-Step Instructions for Gmail SMTP App Password Generation, Stripe Live Payment Gateway Setup, and Production Activation Protocol")
    r3.font.name = "Calibri"
    r3.font.size = Pt(10)
    r3.font.italic = True
    r3.font.color.rgb = body_slate

    doc.add_paragraph()

    # ─── METADATA TABLE ───────────────────────────────────────────────────────
    meta_tbl = doc.add_table(rows=5, cols=2)
    meta_tbl.alignment = WD_TABLE_ALIGNMENT.LEFT
    set_table_border(meta_tbl, "CBD5E1")
    
    meta_entries = [
        ("Prepared For:", "Wendy / MST Executive Management"),
        ("Prepared By:", "Abdul Rehman / Lead Engineering Team"),
        ("Date of Guide:", "October 2026"),
        ("Scope:", "Gmail SMTP Email Setup, Stripe Live API Keys, and Payment Rails (FPX/Cards/Wallets)"),
        ("Security Guarantee:", "✓ 100% Secure — Primary Account Passwords Are Never Requested or Shared")
    ]

    for i, (k, v) in enumerate(meta_entries):
        row = meta_tbl.rows[i]
        c1, c2 = row.cells[0], row.cells[1]
        c1.width = Inches(2.2)
        c2.width = Inches(5.0)
        bg = "F8FAFC" if i % 2 == 0 else "FFFFFF"
        set_cell_background(c1, bg); set_cell_background(c2, bg)
        set_cell_margins(c1, 60, 60, 100, 80); set_cell_margins(c2, 60, 60, 100, 80)
        
        p1 = c1.paragraphs[0]; r_k = p1.add_run(k)
        r_k.font.name = "Calibri"; r_k.font.size = Pt(9); r_k.font.bold = True; r_k.font.color.rgb = dark_slate
        
        p2 = c2.paragraphs[0]; r_v = p2.add_run(v)
        r_v.font.name = "Calibri"; r_v.font.size = Pt(9)
        if "100% Secure" in v:
            r_v.font.bold = True
            r_v.font.color.rgb = success_green
        else:
            r_v.font.color.rgb = body_slate

    doc.add_paragraph()

    # ─── SECTION 1: SECURITY PROTOCOL ─────────────────────────────────────────
    add_heading(doc, "1. Executive Summary & Security Policy", 1, primary_navy, 8, 4)
    add_body(doc,
        "Dear Wendy, you are completely correct: under no circumstances should you ever share your primary Google or Stripe login passwords. "
        "Modern enterprise cloud architectures use scoped credentials (Google App Passwords and Stripe Restricted API Keys) that allow our application "
        "to deliver order receipts and process customer payments securely without compromising your master administrative access.",
        10, color=body_slate, space_after=6)

    add_callout(
        doc,
        "You retain 100% administrative control. The credentials requested below can be regenerated, paused, or revoked by you at any time "
        "directly from your Google Account or Stripe Dashboard without affecting your personal logins.",
        "ZERO-TRUST CREDENTIAL ISOLATION",
        "ECFDF5", "10B981", "065F46"
    )

    # ─── SECTION 2: GMAIL SMTP ────────────────────────────────────────────────
    add_heading(doc, "2. Gmail SMTP Setup (Order Receipts & Customer Invoices)", 1, primary_navy, 10, 4)
    add_body(doc,
        "To enable the platform to send order confirmations, PDF invoices, and dispatch notifications directly from your official company email address, "
        "Google requires a dedicated 16-character App Password.",
        10, color=body_slate, space_after=4)

    add_heading(doc, "2.1 Required Credentials for Gmail SMTP", 2, accent_blue, 6, 2)
    
    gmail_tbl = doc.add_table(rows=4, cols=3)
    gmail_tbl.alignment = WD_TABLE_ALIGNMENT.LEFT
    set_table_border(gmail_tbl, "CBD5E1")

    g_headers = ["Parameter", "Example Value", "Purpose & Description"]
    g_widths = [Inches(2.0), Inches(2.2), Inches(2.8)]

    g_hdr_row = gmail_tbl.rows[0]
    for idx, h in enumerate(g_headers):
        cell = g_hdr_row.cells[idx]
        cell.width = g_widths[idx]
        set_cell_background(cell, "091A36")
        set_cell_margins(cell, 80, 80, 100, 80)
        p = cell.paragraphs[0]; r = p.add_run(h)
        r.font.name = "Calibri"; r.font.size = Pt(9); r.font.bold = True; r.font.color.rgb = RGBColor(0xFF, 0xFF, 0xFF)

    g_data = [
        ("Gmail Address", "orders@mst.my (or your Gmail)", "The official email address customers will see as the sender."),
        ("Google App Password", "abcd efgh ijkl mnop", "A 16-character secure code generated specifically for the website."),
        ("Sender Display Name", "MST Import & Export Sdn. Bhd.", "The friendly brand name displayed in the customer's email inbox.")
    ]

    for row_idx, row_data in enumerate(g_data, start=1):
        row = gmail_tbl.rows[row_idx]
        bg_color = "F8FAFC" if row_idx % 2 == 1 else "FFFFFF"
        for col_idx, text in enumerate(row_data):
            cell = row.cells[col_idx]
            cell.width = g_widths[col_idx]
            set_cell_background(cell, bg_color)
            set_cell_margins(cell, 60, 60, 100, 80)
            p = cell.paragraphs[0]; r = p.add_run(text)
            r.font.name = "Calibri"; r.font.size = Pt(8.5)
            if col_idx == 0:
                r.font.bold = True; r.font.color.rgb = dark_slate
            elif col_idx == 1:
                r.font.color.rgb = primary_teal
            else:
                r.font.color.rgb = body_slate

    add_heading(doc, "2.2 Step-by-Step Guide: How to Generate Your Google App Password", 2, accent_blue, 8, 2)
    add_bullet(doc, "Open your browser and navigate to your Google Account management portal at: https://myaccount.google.com/", 9.5, body_slate, "Step 1 (Open Account): ")
    add_bullet(doc, "Click on 'Security' in the left-hand navigation panel.", 9.5, body_slate, "Step 2 (Navigate to Security): ")
    add_bullet(doc, "Under 'How you sign in to Google', ensure 2-Step Verification is switched ON (Google requires 2FA before issuing app passwords).", 9.5, body_slate, "Step 3 (Verify 2FA): ")
    add_bullet(doc, "In the top search bar of the Google Account page, type 'App Passwords' and select it, or go directly to: https://myaccount.google.com/apppasswords", 9.5, body_slate, "Step 4 (Open App Passwords): ")
    add_bullet(doc, "Enter an App Name (e.g. 'MST Seafood Website') and click 'Create'.", 9.5, body_slate, "Step 5 (Create Name): ")
    add_bullet(doc, "Google will display a 16-character code in a yellow modal box (e.g. 'xxxx xxxx xxxx xxxx'). Copy this 16-character passcode and share it with our engineering team.", 9.5, body_slate, "Step 6 (Copy & Provide): ")

    add_divider(doc)

    # ─── SECTION 3: STRIPE LIVE SETUP ─────────────────────────────────────────
    add_heading(doc, "3. Stripe Payment Gateway Setup (Live Mode vs. Test Mode)", 1, primary_navy, 10, 4)
    
    add_callout(
        doc,
        "RECOMMENDATION: Connect LIVE MODE credentials (pk_live_... and sk_live_...). "
        "Because customer-side functional checkout, cart calculation, delivery threshold logic, and outstation quotation flows have already passed 100% "
        "in our sandbox environment, connecting your live Stripe account allows us to verify your active Malaysian merchant payment rails (FPX, Apple Pay, Google Pay, Cards) "
        "and complete a live nominal test transaction before opening to the public.",
        "LIVE MODE VS. TEST MODE GUIDANCE",
        "EFF6FF", "3B82F6", "1E40AF"
    )

    add_heading(doc, "3.1 Required Credentials for Stripe Integration", 2, accent_blue, 6, 2)

    stripe_tbl = doc.add_table(rows=3, cols=3)
    stripe_tbl.alignment = WD_TABLE_ALIGNMENT.LEFT
    set_table_border(stripe_tbl, "CBD5E1")

    s_headers = ["Key Name", "Prefix / Format", "Role & Security Classification"]
    s_widths = [Inches(2.0), Inches(2.2), Inches(2.8)]

    s_hdr_row = stripe_tbl.rows[0]
    for idx, h in enumerate(s_headers):
        cell = s_hdr_row.cells[idx]
        cell.width = s_widths[idx]
        set_cell_background(cell, "091A36")
        set_cell_margins(cell, 80, 80, 100, 80)
        p = cell.paragraphs[0]; r = p.add_run(h)
        r.font.name = "Calibri"; r.font.size = Pt(9); r.font.bold = True; r.font.color.rgb = RGBColor(0xFF, 0xFF, 0xFF)

    s_data = [
        ("Publishable Key", "pk_live_... (or pk_test_...)", "Client-side identifier used to render Stripe Hosted Checkout securely."),
        ("Secret Key", "sk_live_... (or sk_test_...)", "Server-side authorization key used to create checkout sessions and verify transactions.")
    ]

    for row_idx, row_data in enumerate(s_data, start=1):
        row = stripe_tbl.rows[row_idx]
        bg_color = "F8FAFC" if row_idx % 2 == 1 else "FFFFFF"
        for col_idx, text in enumerate(row_data):
            cell = row.cells[col_idx]
            cell.width = s_widths[col_idx]
            set_cell_background(cell, bg_color)
            set_cell_margins(cell, 60, 60, 100, 80)
            p = cell.paragraphs[0]; r = p.add_run(text)
            r.font.name = "Calibri"; r.font.size = Pt(8.5)
            if col_idx == 0:
                r.font.bold = True; r.font.color.rgb = dark_slate
            elif col_idx == 1:
                r.font.color.rgb = primary_teal
            else:
                r.font.color.rgb = body_slate

    add_heading(doc, "3.2 Step-by-Step Guide: How to Retrieve Stripe API Keys", 2, accent_blue, 8, 2)
    add_bullet(doc, "Log into your official Stripe Dashboard at: https://dashboard.stripe.com/", 9.5, body_slate, "Step 1 (Log In): ")
    add_bullet(doc, "Ensure the mode toggle in the top-left/top-right header is set to 'Live Mode' (or Test Mode if you prefer initial sandbox validation).", 9.5, body_slate, "Step 2 (Select Mode): ")
    add_bullet(doc, "In the left navigation sidebar, click 'Developers', then select 'API keys' (Direct link: https://dashboard.stripe.com/apikeys).", 9.5, body_slate, "Step 3 (API Keys Menu): ")
    add_bullet(doc, "Under the 'Standard keys' section, click to copy the 'Publishable key' (starts with pk_live_...).", 9.5, body_slate, "Step 4 (Copy Publishable Key): ")
    add_bullet(doc, "Under 'Secret key', click 'Reveal live key' (or 'Create secret key') and copy the token (starts with sk_live_...).", 9.5, body_slate, "Step 5 (Copy Secret Key): ")

    add_heading(doc, "3.3 How to Activate FPX, Apple Pay, Google Pay & Cards in Stripe", 2, accent_blue, 8, 2)
    add_body(doc,
        "Stripe Hosted Checkout automatically displays payment options based on what is switched ON inside your Stripe Merchant Dashboard:",
        10, color=body_slate, space_after=4)

    add_bullet(doc, "In your Stripe Dashboard, go to Settings > Payment Methods (Direct link: https://dashboard.stripe.com/settings/payment_methods).", 9.5, body_slate, "Step 1: ")
    add_bullet(doc, "Under 'Cards', ensure Credit & Debit Cards (Visa, Mastercard, American Express) are set to 'Active'.", 9.5, body_slate, "Step 2: ")
    add_bullet(doc, "Under 'Real-time payments', click on 'FPX (Online Banking)' and ensure it is set to 'Active'.", 9.5, body_slate, "Step 3: ")
    add_bullet(doc, "Under 'Wallets', ensure 'Apple Pay' and 'Google Pay' are set to 'Active'.", 9.5, body_slate, "Step 4: ")
    add_bullet(doc, "Once activated in your dashboard, our platform will automatically reflect these payment methods at checkout without requiring extra code changes.", 9.5, body_slate, "Step 5: ")

    add_divider(doc)

    # ─── SECTION 4: VERIFICATION PLAN ─────────────────────────────────────────
    add_heading(doc, "4. Post-Handover Verification & Live Testing Protocol", 1, primary_navy, 10, 4)
    add_body(doc,
        "Once you provide the credentials, our engineering team will execute the following 3-step verification:",
        10, color=body_slate, space_after=6)

    # Verification Table
    ver_tbl = doc.add_table(rows=4, cols=3)
    ver_tbl.alignment = WD_TABLE_ALIGNMENT.LEFT
    set_table_border(ver_tbl, "CBD5E1")

    v_headers = ["Phase", "Action Executed by Engineering", "Expected Live Outcome"]
    v_widths = [Inches(1.5), Inches(2.7), Inches(2.8)]

    v_hdr_row = ver_tbl.rows[0]
    for idx, h in enumerate(v_headers):
        cell = v_hdr_row.cells[idx]
        cell.width = v_widths[idx]
        set_cell_background(cell, "091A36")
        set_cell_margins(cell, 80, 80, 100, 80)
        p = cell.paragraphs[0]; r = p.add_run(h)
        r.font.name = "Calibri"; r.font.size = Pt(9); r.font.bold = True; r.font.color.rgb = RGBColor(0xFF, 0xFF, 0xFF)

    v_data = [
        (
            "Phase 1:\nSMTP Email Test",
            "Send a live test order confirmation email using `/admin/email-templates` directly to your company inbox.",
            "You receive a beautifully branded HTML receipt in English, BM, and Chinese with zero spam folder flags."
        ),
        (
            "Phase 2:\nStripe Rails Check",
            "Initialize a test checkout session using your live API keys.",
            "Stripe Hosted Checkout opens displaying FPX (Maybank2u, CIMB Clicks, Public Bank, etc.), Cards, and Apple/Google Pay."
        ),
        (
            "Phase 3:\nLive Nominal Order",
            "Execute a small live order (e.g. RM10 or test item) to verify bank debiting and automated order creation.",
            "Order is confirmed, inventory adjusted, receipt dispatched, and funds settle into your Malaysian bank account."
        )
    ]

    for row_idx, row_data in enumerate(v_data, start=1):
        row = ver_tbl.rows[row_idx]
        bg_color = "F8FAFC" if row_idx % 2 == 1 else "FFFFFF"
        for col_idx, text in enumerate(row_data):
            cell = row.cells[col_idx]
            cell.width = v_widths[col_idx]
            set_cell_background(cell, bg_color)
            set_cell_margins(cell, 60, 60, 100, 80)
            p = cell.paragraphs[0]; r = p.add_run(text)
            r.font.name = "Calibri"; r.font.size = Pt(8.5)
            if col_idx == 0:
                r.font.bold = True; r.font.color.rgb = primary_navy
            else:
                r.font.color.rgb = body_slate

    add_divider(doc)

    # ─── SECTION 5: CREDENTIAL RETURN FORM ────────────────────────────────────
    add_heading(doc, "5. Credential Return Submission Form", 1, primary_navy, 10, 4)
    add_body(doc,
        "Please copy, complete, and send back the structured template below via WhatsApp or email:",
        10, color=body_slate, space_after=6)

    # Form Box
    form_tbl = doc.add_table(rows=1, cols=1)
    form_tbl.alignment = WD_TABLE_ALIGNMENT.LEFT
    f_cell = form_tbl.rows[0].cells[0]
    set_cell_background(f_cell, "F8FAFC")
    set_cell_margins(f_cell, 120, 120, 160, 160)
    
    fp = f_cell.paragraphs[0]
    fp.paragraph_format.space_before = Pt(4)
    fp.paragraph_format.space_after = Pt(4)
    
    form_text = (
        "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n"
        "MST E-COMMERCE LIVE CREDENTIAL SUBMISSION FORM\n"
        "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n"
        "1. Gmail Address for Outgoing Mail:\n"
        "   [ e.g., orders@mst.my or mst.sales@gmail.com ]\n\n"
        "2. Google 16-Character App Password:\n"
        "   [ e.g., abcd efgh ijkl mnop ]\n\n"
        "3. Stripe Environment (Live / Test):\n"
        "   [ Live Mode (Recommended) ]\n\n"
        "4. Stripe Publishable Key:\n"
        "   [ pk_live_... or pk_test_... ]\n\n"
        "5. Stripe Secret Key:\n"
        "   [ sk_live_... or sk_test_... ]\n\n"
        "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
    )
    r_form = fp.add_run(form_text)
    r_form.font.name = "Courier New"
    r_form.font.size = Pt(9)
    r_form.font.bold = True
    r_form.font.color.rgb = dark_slate

    doc.add_paragraph()

    # ─── SECTION 6: CONCLUSION & SIGN-OFF ─────────────────────────────────────
    add_heading(doc, "6. Sign-off & Next Steps", 1, primary_navy, 10, 4)
    add_body(doc,
        "Once these credentials are received, our team will configure them within 2 hours, run the live tests, "
        "and invite you for final sign-off and public launch.",
        10, color=body_slate, space_after=8)

    sig_tbl = doc.add_table(rows=2, cols=2)
    sig_tbl.alignment = WD_TABLE_ALIGNMENT.LEFT
    set_table_border(sig_tbl, "CBD5E1")

    sig_cells = [
        ("Prepared & Verified by Engineering Lead:", "Abdul Rehman\nLead Software Engineer / Architect\nMST E-Commerce Project"),
        ("Approved & Acknowledged by Client Lead:", "Wendy\nExecutive Management & Business Operations\nMST Import and Export Sdn. Bhd.")
    ]

    for idx, (label, val) in enumerate(sig_cells):
        c = sig_tbl.rows[0].cells[idx]
        c.width = Inches(3.6)
        set_cell_background(c, "F1F5F9")
        set_cell_margins(c, 60, 60, 100, 80)
        p = c.paragraphs[0]; r = p.add_run(label)
        r.font.name = "Calibri"; r.font.size = Pt(8.5); r.font.bold = True; r.font.color.rgb = dark_slate

        c2 = sig_tbl.rows[1].cells[idx]
        c2.width = Inches(3.6)
        set_cell_background(c2, "FFFFFF")
        set_cell_margins(c2, 80, 80, 100, 80)
        p2 = c2.paragraphs[0]; r2 = p2.add_run(val)
        r2.font.name = "Calibri"; r2.font.size = Pt(9); r2.font.color.rgb = body_slate

    output_filename = "f:/My AI/Sea Food/seafood/MST_Live_Credentials_and_Payment_Gateway_Onboarding_Guide.docx"
    doc.save(output_filename)
    print(f"Successfully generated credentials guide at: {output_filename}")

if __name__ == "__main__":
    build_credentials_guide()
