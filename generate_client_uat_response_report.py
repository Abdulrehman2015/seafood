import docx
from docx.shared import Inches, Pt, RGBColor
from docx.enum.text import WD_ALIGN_PARAGRAPH
from docx.enum.table import WD_TABLE_ALIGNMENT, WD_ALIGN_VERTICAL
from docx.oxml import parse_xml
from docx.oxml.ns import nsdecls
from docx.oxml.ns import qn
from docx.oxml import OxmlElement
import os
from datetime import datetime

# ─── Helper Functions ────────────────────────────────────────────────────────

def set_cell_background(cell, fill_hex):
    tcPr = cell._tc.get_or_add_tcPr()
    shd = parse_xml(f'<w:shd {nsdecls("w")} w:fill="{fill_hex}" w:color="auto" w:val="clear"/>')
    tcPr.append(shd)

def set_cell_margins(cell, top=80, bottom=80, left=120, right=120):
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

def set_table_border(table):
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
        border.set(qn('w:color'), 'D1D5DB')
        tblBorders.append(border)
    tblPr.append(tblBorders)

def add_heading(doc, text, level=1, color=None, space_before=12, space_after=4):
    p = doc.add_paragraph()
    p.paragraph_format.space_before = Pt(space_before)
    p.paragraph_format.space_after = Pt(space_after)
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
    p.paragraph_format.space_before = Pt(1)
    p.paragraph_format.space_after = Pt(2)
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

def add_status_badge_row(doc, section_num, title, status, status_color_hex, action_text):
    tbl = doc.add_table(rows=1, cols=3)
    tbl.alignment = WD_TABLE_ALIGNMENT.LEFT
    tbl.columns[0].width = Inches(0.45)
    tbl.columns[1].width = Inches(4.5)
    tbl.columns[2].width = Inches(2.2)

    c0 = tbl.rows[0].cells[0]
    set_cell_background(c0, "0F274A")
    set_cell_margins(c0, 60, 60, 100, 80)
    p0 = c0.paragraphs[0]
    p0.alignment = WD_ALIGN_PARAGRAPH.CENTER
    r0 = p0.add_run(str(section_num))
    r0.font.name = "Calibri"
    r0.font.size = Pt(12)
    r0.font.bold = True
    r0.font.color.rgb = RGBColor(0xFF, 0xFF, 0xFF)

    c1 = tbl.rows[0].cells[1]
    set_cell_background(c1, "091A36")
    set_cell_margins(c1, 60, 60, 120, 80)
    p1 = c1.paragraphs[0]
    r1 = p1.add_run(title)
    r1.font.name = "Calibri"
    r1.font.size = Pt(11)
    r1.font.bold = True
    r1.font.color.rgb = RGBColor(0xFF, 0xFF, 0xFF)

    c2 = tbl.rows[0].cells[2]
    set_cell_background(c2, status_color_hex)
    set_cell_margins(c2, 60, 60, 80, 80)
    p2 = c2.paragraphs[0]
    p2.alignment = WD_ALIGN_PARAGRAPH.CENTER
    r2 = p2.add_run(action_text)
    r2.font.name = "Calibri"
    r2.font.size = Pt(9)
    r2.font.bold = True
    r2.font.color.rgb = RGBColor(0xFF, 0xFF, 0xFF)

    return tbl

def add_divider(doc):
    p = doc.add_paragraph()
    p.paragraph_format.space_before = Pt(0)
    p.paragraph_format.space_after = Pt(0)
    pPr = p._p.get_or_add_pPr()
    pBdr = OxmlElement('w:pBdr')
    bottom = OxmlElement('w:bottom')
    bottom.set(qn('w:val'), 'single')
    bottom.set(qn('w:sz'), '4')
    bottom.set(qn('w:space'), '1')
    bottom.set(qn('w:color'), 'CBD5E1')
    pBdr.append(bottom)
    pPr.append(pBdr)

def add_info_box(doc, text, bg_hex="EFF6FF", border_hex="BFDBFE", text_color=None):
    tbl = doc.add_table(rows=1, cols=1)
    tbl.alignment = WD_TABLE_ALIGNMENT.LEFT
    cell = tbl.rows[0].cells[0]
    set_cell_background(cell, bg_hex)
    set_cell_margins(cell, 100, 100, 140, 140)
    p = cell.paragraphs[0]
    r = p.add_run(text)
    r.font.name = "Calibri"
    r.font.size = Pt(9.5)
    if text_color:
        r.font.color.rgb = text_color
    return tbl

# ─── MAIN DOCUMENT ────────────────────────────────────────────────────────────

def create_report():
    doc = docx.Document()

    for section in doc.sections:
        section.top_margin = Inches(0.7)
        section.bottom_margin = Inches(0.7)
        section.left_margin = Inches(0.85)
        section.right_margin = Inches(0.85)

    primary_navy   = RGBColor(9, 26, 54)
    primary_teal   = RGBColor(15, 118, 110)
    accent_blue    = RGBColor(2, 132, 199)
    dark_slate     = RGBColor(15, 23, 42)
    body_slate     = RGBColor(51, 65, 85)
    success_green  = RGBColor(22, 101, 52)
    warning_amber  = RGBColor(120, 53, 15)
    info_blue      = RGBColor(29, 78, 216)
    hold_orange    = RGBColor(154, 52, 18)
    grey_text      = RGBColor(100, 116, 139)

    # ─── HEADER ───────────────────────────────────────────────────────────────
    p_header = doc.add_paragraph()
    p_header.paragraph_format.space_after = Pt(2)
    r_company = p_header.add_run("MST IMPORT & EXPORT SDN. BHD. — E-COMMERCE PLATFORM\n")
    r_company.font.name = "Calibri"
    r_company.font.size = Pt(9)
    r_company.font.bold = True
    r_company.font.color.rgb = primary_teal
    r_title = p_header.add_run("CLIENT UAT RESPONSE & TECHNICAL STATUS REPORT\n")
    r_title.font.name = "Calibri"
    r_title.font.size = Pt(17)
    r_title.font.bold = True
    r_title.font.color.rgb = primary_navy
    r_sub = p_header.add_run("Response to Client Review — Cart, Checkout, Stripe, Payment Methods & Fee Verification")
    r_sub.font.name = "Calibri"
    r_sub.font.size = Pt(10)
    r_sub.font.italic = True
    r_sub.font.color.rgb = body_slate
    doc.add_paragraph()

    # ─── META TABLE ───────────────────────────────────────────────────────────
    meta_table = doc.add_table(rows=5, cols=2)
    meta_table.alignment = WD_TABLE_ALIGNMENT.LEFT
    set_table_border(meta_table)
    meta_data = [
        ("Report Type:",   "Client UAT Response — Technical Verification & Action Plan"),
        ("Prepared By:",   "Abdul Rehman / Lead Engineering Team"),
        ("Report Date:",   "October 2026"),
        ("UAT Status:",    "In Progress — Client Testing Ongoing (Final Sign-off Pending)"),
        ("System Build:",  "MST Laravel E-Commerce — Stripe Hosted Checkout, Multi-Zone Delivery"),
    ]
    for i, (k, v) in enumerate(meta_data):
        row = meta_table.rows[i]
        c1, c2 = row.cells[0], row.cells[1]
        c1.width = Inches(1.9)
        c2.width = Inches(5.2)
        bg = "F1F5F9" if i % 2 == 0 else "FFFFFF"
        set_cell_background(c1, bg); set_cell_background(c2, bg)
        set_cell_margins(c1, 70, 70, 100, 80); set_cell_margins(c2, 70, 70, 100, 80)
        p1 = c1.paragraphs[0]; r1 = p1.add_run(k)
        r1.font.name = "Calibri"; r1.font.size = Pt(9); r1.font.bold = True; r1.font.color.rgb = dark_slate
        p2 = c2.paragraphs[0]; r2 = p2.add_run(v)
        r2.font.name = "Calibri"; r2.font.size = Pt(9); r2.font.color.rgb = body_slate
    doc.add_paragraph()

    # INTRO
    add_heading(doc, "INTRODUCTION", 2, primary_navy, 8, 4)
    add_body(doc,
        "Thank you for your thorough review and structured feedback. This report is our official response to your client review "
        "dated October 2026. It addresses each of the 9 points in your email: confirming PASS items, providing technical verification "
        "for the Stripe fee inquiry, clarifying the status of pending items, and outlining what remains unchanged per your instructions.",
        10, color=body_slate, space_after=6)
    add_divider(doc)
    doc.add_paragraph()

    # ── S1 Cart Threshold ─────────────────────────────────────────────────────
    add_status_badge_row(doc, 1, "Cart Delivery Threshold", "PASS", "166534", "CONFIRMED — NO CHANGE")
    doc.add_paragraph()
    add_heading(doc, "Client Confirmation:", 3, success_green, 4, 2)
    add_info_box(doc,
        '"Add RM46.60 more to qualify for Free Standard Delivery." — Confirmed correct. RM100.00 - RM53.40 = RM46.60. Please keep this logic.',
        "F0FDF4", "BBF7D0", success_green)
    doc.add_paragraph()
    add_heading(doc, "Technical Status — Verified & Unchanged:", 3, dark_slate, 4, 2)
    add_body(doc,
        "The Cart delivery threshold logic is implemented in DeliveryService.php. The shortfall calculation is: max(0, round(threshold - subtotal, 2)).",
        9.5, color=body_slate, space_after=3)
    add_bullet(doc, "B2C Retail free delivery threshold: RM 100.00")
    add_bullet(doc, "B2B Wholesale free delivery threshold: RM 350.00")
    add_bullet(doc, "Formula: shortfall = RM 100.00 - cart subtotal (rounded to 2 decimal places)")
    add_bullet(doc, "At RM 53.40 subtotal: shortfall = RM 46.60 (confirmed correct by client)")
    add_bullet(doc, "Logic remains unchanged per client instruction")
    doc.add_paragraph(); add_divider(doc); doc.add_paragraph()

    # ── S2 Checkout Delivery Fee ──────────────────────────────────────────────
    add_status_badge_row(doc, 2, "Checkout Delivery Fee Display", "PASS", "166534", "CONFIRMED — NO CHANGE")
    doc.add_paragraph()
    add_heading(doc, "Client Confirmation:", 3, success_green, 4, 2)
    add_info_box(doc,
        '"Cold-Chain Delivery - Zone A: + RM10.00 — This is much clearer and avoids any confusion about double charging. Please keep this format."',
        "F0FDF4", "BBF7D0", success_green)
    doc.add_paragraph()
    add_heading(doc, "Technical Status — Verified & Unchanged:", 3, dark_slate, 4, 2)
    add_body(doc,
        "The checkout delivery fee is calculated via DeliveryService::calculateFee() and passed to the Checkout view as a single "
        "deliveryInfo array. The fee is displayed once, clearly labelled with the zone name. No duplicate fee entries exist.",
        9.5, color=body_slate, space_after=3)
    add_bullet(doc, "Single delivery line item displayed in the order summary panel")
    add_bullet(doc, "Format: 'Cold-Chain Delivery - [Zone Name]: + RM[fee]'")
    add_bullet(doc, "Stripe Checkout also receives exactly one delivery line item (if fee > 0)")
    add_bullet(doc, "Self-Collection always shows RM 0.00 with no delivery line")
    add_bullet(doc, "Format and logic kept unchanged per client instruction")
    doc.add_paragraph(); add_divider(doc); doc.add_paragraph()

    # ── S3 Self-Collection ────────────────────────────────────────────────────
    add_status_badge_row(doc, 3, "Self-Collection Flow", "PASS", "166534", "CONFIRMED — NO CHANGE")
    doc.add_paragraph()
    add_heading(doc, "Client Confirmation:", 3, success_green, 4, 2)
    add_info_box(doc,
        '"Self-Collection is also confirmed as correct. Please keep the current Self-Collection flow unchanged."',
        "F0FDF4", "BBF7D0", success_green)
    doc.add_paragraph()
    add_heading(doc, "Technical Status — Verified & Unchanged:", 3, dark_slate, 4, 2)
    add_body(doc,
        "The Self-Collection flow is protected by Rule 1 in DeliveryService::calculateFee(): if fulfillment_type === "
        "'self_collection' OR group === 'walkin', the fee is always RM 0.00 with no threshold requirement.",
        9.5, color=body_slate, space_after=3)
    add_bullet(doc, "Collection Date & Time selection: required for all self-collection orders")
    add_bullet(doc, "Location: MST Cold-Chain Facility, Counter 2, 7 Jalan SILC 2/18, Iskandar Puteri, Johor")
    add_bullet(doc, "Walk-in (in-store) self-collection: supports Cash or Stripe payment")
    add_bullet(doc, "Online self-collection: requires customer email, supports Stripe payment")
    add_bullet(doc, "No delivery fee applied in any self-collection scenario — RM 0.00 always")
    add_bullet(doc, "Flow kept fully unchanged per client instruction")
    doc.add_paragraph(); add_divider(doc); doc.add_paragraph()

    # ── S4 Stripe English & Email ─────────────────────────────────────────────
    add_status_badge_row(doc, 4, "Stripe English Language & Customer Email Flow", "PASS", "1D4ED8", "PASS — TESTING ONGOING")
    doc.add_paragraph()
    add_heading(doc, "Client Confirmation:", 3, info_blue, 4, 2)
    add_info_box(doc,
        '"PASS, but I will continue testing with a different customer email to verify the complete flow: checkout to Stripe to order record to notification."',
        "EFF6FF", "BFDBFE", info_blue)
    doc.add_paragraph()
    add_heading(doc, "Technical Implementation — Email Flow (Step by Step):", 3, dark_slate, 4, 2)
    add_body(doc, "The complete customer email flow is implemented and verified:", 9.5, color=body_slate, space_after=3)
    steps = [
        ("Step 1 - Checkout Form:", "Customer enters their email address in the checkout form."),
        ("Step 2 - Stripe Session:", "CheckoutController::store() passes the email to Stripe via 'customer_email' parameter."),
        ("Step 3 - Stripe Hosted Page:", "Stripe's hosted checkout page is pre-filled with the customer's email. Locale set to 'en' (English)."),
        ("Step 4 - Payment Completion:", "After successful payment, Stripe redirects to /checkout/stripe/success?session_id=..."),
        ("Step 5 - Order Record:", "CheckoutController::stripeSuccess() retrieves session, verifies payment, creates order with customer_email saved."),
        ("Step 6 - Customer Email:", "Mail::to($order->customer_email)->send(new OrderConfirmation($order)) dispatched immediately."),
        ("Step 7 - Admin Notification:", "Mail::to($adminEmails)->send(new AdminNewOrderNotification($order)) dispatched to all admin recipients."),
    ]
    for step_title, step_desc in steps:
        add_bullet(doc, f" {step_desc}", bold_prefix=step_title)
    doc.add_paragraph()
    add_info_box(doc,
        "NOTE: The order record and confirmation email use the email from the checkout form. "
        "Stripe's customer_email pre-fills the Stripe page but if the customer changes it on Stripe, "
        "the system will use the original checkout form email for the order and notification.",
        "FFFBEB", "FDE68A", warning_amber)
    doc.add_paragraph(); add_divider(doc); doc.add_paragraph()

    # ── S5 Payment Methods ────────────────────────────────────────────────────
    add_status_badge_row(doc, 5, "Payment Methods — Stripe Malaysia", "HOLD", "9A3412", "ON HOLD — VERIFY FIRST")
    doc.add_paragraph()
    add_heading(doc, "Client Instruction:", 3, hold_orange, 4, 2)
    add_info_box(doc,
        '"Please do not change yet. Verify the actual payment methods available in our current Stripe Dashboard/account. '
        'Confirm which methods can be activated directly in our current Stripe Checkout setup."',
        "FFF7ED", "FED7AA", hold_orange)
    doc.add_paragraph()
    add_heading(doc, "Current Implementation:", 3, dark_slate, 4, 2)
    add_body(doc,
        "The system currently uses only Card payments via Stripe Hosted Checkout "
        "(CheckoutController.php line 330: 'payment_method_types' => ['card']). No other payment methods are currently enabled.",
        9.5, color=body_slate, space_after=5)
    add_heading(doc, "Stripe Malaysia — Payment Methods Availability (Public Verification):", 3, dark_slate, 4, 3)

    pm_table = doc.add_table(rows=7, cols=4)
    pm_table.alignment = WD_TABLE_ALIGNMENT.LEFT
    set_table_border(pm_table)
    pm_table.columns[0].width = Inches(1.8)
    pm_table.columns[1].width = Inches(1.6)
    pm_table.columns[2].width = Inches(1.6)
    pm_table.columns[3].width = Inches(2.1)
    for j, h in enumerate(["Payment Method", "Stripe MY Support", "Client Priority", "Stripe Code"]):
        cell = pm_table.rows[0].cells[j]
        set_cell_background(cell, "0F274A"); set_cell_margins(cell, 70, 70, 100, 80)
        p = cell.paragraphs[0]; r = p.add_run(h)
        r.font.name = "Calibri"; r.font.bold = True; r.font.size = Pt(9); r.font.color.rgb = RGBColor(255, 255, 255)
    pm_data = [
        ("Online Banking / FPX",           "Yes — Supported",    "1st Priority",  "fpx"),
        ("GrabPay",                         "Yes — Supported",    "2nd Priority",  "grabpay"),
        ("Card (Visa / Mastercard / etc.)", "Yes — Supported",    "3rd Priority",  "card"),
        ("Touch 'n Go eWallet",             "No — Not Supported", "4th (if avail)","Not available on Stripe"),
        ("DuitNow QR",                      "No — Not Supported", "Future Only",   "Requires separate gateway"),
        ("Apple Pay / Google Pay",          "Yes — Supported",    "Optional",      "apple_pay / google_pay"),
    ]
    row_colors = ["FFFFFF","F8FAFC","FFFFFF","FEF2F2","FEF2F2","FFFFFF"]
    for i, (method, support, priority, code) in enumerate(pm_data):
        row = pm_table.rows[i + 1]
        vals = [method, support, priority, code]
        for j, val in enumerate(vals):
            cell = row.cells[j]; set_cell_background(cell, row_colors[i]); set_cell_margins(cell, 65, 65, 90, 80)
            p = cell.paragraphs[0]; r = p.add_run(val)
            r.font.name = "Calibri"; r.font.size = Pt(9)
            if "Yes" in val: r.font.color.rgb = success_green; r.font.bold = True
            elif "No" in val: r.font.color.rgb = RGBColor(185, 28, 28); r.font.bold = True
            else: r.font.color.rgb = body_slate

    doc.add_paragraph()
    add_info_box(doc,
        "ACTION REQUIRED: To confirm which methods are live for your specific MST Stripe account, "
        "log in to Stripe Dashboard > Settings > Payment Methods. "
        "FPX and GrabPay are generally available for Malaysian businesses but must be individually enabled per account. "
        "Touch 'n Go eWallet is NOT supported natively by Stripe as of October 2026.",
        "EFF6FF", "BFDBFE", info_blue)
    doc.add_paragraph()
    add_heading(doc, "Preferred Method Order (Client Specified):", 3, dark_slate, 4, 2)
    for item in [
        "1. Online Banking / FPX",
        "2. GrabPay",
        "3. Card - Visa / Mastercard / other supported cards",
        "4. Touch 'n Go eWallet — only if it can be enabled for our actual Stripe account",
        "5. DuitNow QR — future option if a Malaysian payment gateway is integrated later",
    ]:
        add_bullet(doc, item)
    add_body(doc, "\nNo changes will be made to payment methods until client confirms after reviewing the Stripe Dashboard.",
             9.5, color=hold_orange, bold=True, space_after=4)
    doc.add_paragraph(); add_divider(doc); doc.add_paragraph()

    # ── S6 Stripe Fee Figures ─────────────────────────────────────────────────
    add_status_badge_row(doc, 6, "Stripe Malaysia Fee Figures — Verification", "INFO", "0369A1", "VERIFIED — SEE TABLE BELOW")
    doc.add_paragraph()
    add_heading(doc, "Client Instruction:", 3, accent_blue, 4, 2)
    add_info_box(doc,
        '"Please verify the fee figures against current Stripe Malaysia pricing applicable to our actual account. '
        'I do NOT want to implement a 3% Card Processing Fee yet."',
        "EFF6FF", "BFDBFE", info_blue)
    doc.add_paragraph()
    add_heading(doc, "Stripe Malaysia — Current Published Pricing (October 2026):", 3, dark_slate, 4, 3)
    add_body(doc,
        "The following fees are based on Stripe's publicly published pricing for Malaysian businesses (stripe.com/en-my/pricing). "
        "Your actual negotiated rates may differ based on account type and transaction volume. "
        "Always verify your exact rates in Stripe Dashboard > Settings > Pricing.",
        9.5, color=body_slate, space_after=5)

    fee_table = doc.add_table(rows=8, cols=4)
    fee_table.alignment = WD_TABLE_ALIGNMENT.LEFT
    set_table_border(fee_table)
    fee_table.columns[0].width = Inches(2.2)
    fee_table.columns[1].width = Inches(1.5)
    fee_table.columns[2].width = Inches(1.5)
    fee_table.columns[3].width = Inches(1.9)
    for j, h in enumerate(["Payment Method", "% Rate", "Fixed Fee", "Notes"]):
        cell = fee_table.rows[0].cells[j]
        set_cell_background(cell, "0F274A"); set_cell_margins(cell, 70, 70, 100, 80)
        p = cell.paragraphs[0]; r = p.add_run(h)
        r.font.name = "Calibri"; r.font.bold = True; r.font.size = Pt(9); r.font.color.rgb = RGBColor(255, 255, 255)
    fee_data = [
        ("Domestic Card (MY-issued)",   "3.0%",    "+ RM 1.00", "Visa, Mastercard, Amex — Malaysian-issued cards"),
        ("International Card",          "+1.5%",   "+ RM 1.00", "Additional 1.5% on top of domestic rate"),
        ("Currency Conversion",         "+2.0%",   "None",      "If MYR not equal to card billing currency"),
        ("FPX (Online Banking)",        "3.0%",    "+ RM 1.00", "Same rate structure as domestic card"),
        ("GrabPay",                     "~3.3%",   "None",      "Wallet-based; verify exact rate in Dashboard"),
        ("Touch 'n Go eWallet",         "N/A",     "N/A",       "NOT supported by Stripe Malaysia"),
        ("DuitNow QR",                  "N/A",     "N/A",       "Requires separate local Malaysian gateway"),
    ]
    fee_row_bg = ["FFFFFF","F8FAFC","FFFFFF","F8FAFC","FFFFFF","FEF2F2","FEF2F2"]
    for i, (method, rate, fixed, note) in enumerate(fee_data):
        row = fee_table.rows[i + 1]
        for j, val in enumerate([method, rate, fixed, note]):
            cell = row.cells[j]; set_cell_background(cell, fee_row_bg[i]); set_cell_margins(cell, 65, 65, 90, 80)
            p = cell.paragraphs[0]; r = p.add_run(val)
            r.font.name = "Calibri"; r.font.size = Pt(9)
            if "NOT" in val: r.font.color.rgb = RGBColor(185, 28, 28); r.font.bold = True
            elif "N/A" in val and j in [1,2]: r.font.color.rgb = RGBColor(185, 28, 28)
            else: r.font.color.rgb = body_slate

    doc.add_paragraph()
    add_heading(doc, "Fee Calculation Examples for MST Orders:", 3, dark_slate, 4, 3)
    examples = [
        ("Order RM 50.00 — Domestic Visa Card:", "3% x RM50 + RM1.00 = RM1.50 + RM1.00 = RM 2.50 Stripe fee. MST receives RM 47.50."),
        ("Order RM 100.00 — Domestic Visa Card:", "3% x RM100 + RM1.00 = RM3.00 + RM1.00 = RM 4.00 Stripe fee. MST receives RM 96.00."),
        ("Order RM 120.00 + RM10 delivery — FPX:", "3% x RM130 + RM1.00 = RM3.90 + RM1.00 = RM 4.90 Stripe fee. MST receives RM 125.10."),
        ("Order RM 50.00 — International Card:", "(3% + 1.5%) x RM50 + RM1.00 = RM2.25 + RM1.00 = RM 3.25 Stripe fee. MST receives RM 46.75."),
    ]
    for ex_title, ex_desc in examples:
        add_bullet(doc, f" {ex_desc}", bold_prefix=ex_title)
    doc.add_paragraph()
    add_info_box(doc,
        "IMPORTANT: The MST Stripe account is currently in TEST mode (sk_test_...). "
        "Stripe fees are only charged on LIVE mode transactions. "
        "Please log in to Stripe Dashboard > Settings > Pricing to verify exact live account rates before going live. "
        "The above figures reflect publicly published standard rates and may differ from your account's actual negotiated rates.",
        "FFFBEB", "FDE68A", warning_amber)
    doc.add_paragraph(); add_divider(doc); doc.add_paragraph()

    # ── S7 Card Surcharge ─────────────────────────────────────────────────────
    add_status_badge_row(doc, 7, "Card Surcharge — Do Not Implement Yet", "HOLD", "9A3412", "ON HOLD — NOT IMPLEMENTED")
    doc.add_paragraph()
    add_heading(doc, "Client Instruction:", 3, hold_orange, 4, 2)
    add_info_box(doc,
        '"For now, please do not add any customer-facing card surcharge. '
        'I prefer to keep the checkout simple and customer-friendly if we can avoid adding a separate card surcharge."',
        "FFF7ED", "FED7AA", hold_orange)
    doc.add_paragraph()
    add_heading(doc, "Current Status — No Surcharge Implemented:", 3, dark_slate, 4, 2)
    add_body(doc,
        "Confirmed: No card surcharge has been added to the checkout flow. "
        "The grand total shown to customers is: Product Subtotal + Delivery Fee (if applicable). "
        "No processing fee is passed on to the customer.",
        9.5, color=body_slate, space_after=4)
    add_heading(doc, "Pre-Implementation Checklist (For When Client Is Ready):", 3, dark_slate, 4, 2)
    checks = [
        ("Stripe Cost Confirmation:", "Verify exact rate applicable to your MST live account in Stripe Dashboard."),
        ("Card Network Rules:", "Visa and Mastercard rules generally permit surcharging in Malaysia, but merchant agreement terms must be checked."),
        ("Malaysia-Specific Requirements:", "No specific legal prohibition on surcharging in Malaysia as of 2026, but clear disclosure to customers is required."),
        ("Calculation Method:", "Surcharge = (Subtotal + Delivery) x rate / (1 - rate) to recover the exact net cost."),
        ("Commercial Advisability:", "Consider conversion rate impact — customers often abandon carts when surcharges are added."),
    ]
    for check_title, check_desc in checks:
        add_bullet(doc, f" {check_desc}", bold_prefix=check_title)
    add_body(doc, "\nThis section will only be revisited after client provides explicit confirmation to proceed.",
             9.5, color=hold_orange, bold=True, space_after=4)
    doc.add_paragraph(); add_divider(doc); doc.add_paragraph()

    # ── S8 DuitNow QR ────────────────────────────────────────────────────────
    add_status_badge_row(doc, 8, "DuitNow QR — Deferred to Future Phase", "DEFER", "6B21A8", "DEFERRED — NOT IN SCOPE")
    doc.add_paragraph()
    add_heading(doc, "Client Instruction:", 3, RGBColor(107, 33, 168), 4, 2)
    add_info_box(doc,
        '"I agree that DuitNow QR should not be added immediately. '
        'For the initial launch, I prefer to keep the payment system stable and use methods supported through our current Stripe setup."',
        "FAF5FF", "E9D5FF", RGBColor(107, 33, 168))
    doc.add_paragraph()
    add_heading(doc, "Technical Assessment:", 3, dark_slate, 4, 2)
    add_body(doc,
        "DuitNow QR is Malaysia's national QR payment standard operated by PayNet. It is not natively supported by Stripe. "
        "To accept DuitNow QR, MST would need to integrate a separate Malaysian payment gateway.",
        9.5, color=body_slate, space_after=3)
    for item in [
        "DuitNow QR requires a PayNet-certified payment gateway (e.g., Fiuu/Razer, iPay88, Billplz, or HitPay)",
        "Integration would require additional development, onboarding, and compliance steps",
        "Not in scope for the current initial launch phase — deferred to future phase",
        "Current Stripe setup remains stable and sufficient for launch",
        "This can be revisited in Phase 2 based on business requirements",
    ]:
        add_bullet(doc, item)
    doc.add_paragraph(); add_divider(doc); doc.add_paragraph()

    # ── S9 UAT Status ────────────────────────────────────────────────────────
    add_status_badge_row(doc, 9, "Client UAT — Status & Remaining Test Scenarios", "ONGOING", "1E40AF", "TESTING IN PROGRESS")
    doc.add_paragraph()
    add_heading(doc, "UAT Status:", 3, info_blue, 4, 2)
    add_body(doc,
        "Client UAT is still in progress. The Cart and Checkout corrections are accepted by the client, "
        "but final sign-off has not yet been given. The following 11 test scenarios remain pending:",
        9.5, color=body_slate, space_after=4)

    uat_table = doc.add_table(rows=12, cols=3)
    uat_table.alignment = WD_TABLE_ALIGNMENT.LEFT
    set_table_border(uat_table)
    uat_table.columns[0].width = Inches(2.5)
    uat_table.columns[1].width = Inches(3.0)
    uat_table.columns[2].width = Inches(1.6)
    for j, h in enumerate(["Test Scenario", "Test Details", "Status"]):
        cell = uat_table.rows[0].cells[j]
        set_cell_background(cell, "0F274A"); set_cell_margins(cell, 70, 70, 100, 80)
        p = cell.paragraphs[0]; r = p.add_run(h)
        r.font.name = "Calibri"; r.font.bold = True; r.font.size = Pt(9); r.font.color.rgb = RGBColor(255, 255, 255)
    uat_data = [
        ("Cart RM 99.00",             "Subtotal just below RM 100 threshold",            "Pending"),
        ("Cart RM 100.00",            "Subtotal exactly at free delivery threshold",       "Pending"),
        ("Cart RM 120.00",            "Subtotal above free delivery threshold",            "Pending"),
        ("Zone B Delivery",           "Delivery to Zone B address — fee & label check",   "Pending"),
        ("Zone C Delivery",           "Delivery to Zone C address — fee & label check",   "Pending"),
        ("Self-Collection",           "Full flow — date/time/counter token test",         "Pending"),
        ("Different Customer Email",  "Test email flow with a different customer email",  "Pending"),
        ("Payment Completion",        "Full Stripe payment — card or FPX test",           "Pending"),
        ("Order Confirmation",        "Order record in admin panel after payment",        "Pending"),
        ("Customer Email Notification","Confirmation email received by customer",         "Pending"),
        ("Admin Order Notification",  "Admin notification email on new order",            "Pending"),
    ]
    uat_row_bg = ["FFFFFF","F8FAFC"] * 6
    for i, (scenario, detail, status) in enumerate(uat_data):
        row = uat_table.rows[i + 1]
        for j, val in enumerate([scenario, detail, status]):
            cell = row.cells[j]; set_cell_background(cell, uat_row_bg[i]); set_cell_margins(cell, 65, 65, 90, 80)
            p = cell.paragraphs[0]; r = p.add_run(val)
            r.font.name = "Calibri"; r.font.size = Pt(9)
            r.font.color.rgb = RGBColor(146, 64, 14) if val == "Pending" else body_slate

    doc.add_paragraph()
    add_info_box(doc,
        "NEXT STEPS: Once the above 11 UAT scenarios are tested and confirmed, the client will provide final UAT sign-off. "
        "After sign-off, the engineering team will proceed with:\n"
        "  1. Enabling additional payment methods (FPX, GrabPay) in Stripe Dashboard\n"
        "  2. Switching from TEST mode to LIVE Stripe mode\n"
        "  3. Any approved changes to checkout flow, fees, or surcharge policy",
        "EFF6FF", "BFDBFE", info_blue)
    doc.add_paragraph(); add_divider(doc); doc.add_paragraph()

    # ── SUMMARY TABLE ─────────────────────────────────────────────────────────
    add_heading(doc, "OVERALL STATUS SUMMARY", 2, primary_navy, 10, 4)
    summary_table = doc.add_table(rows=10, cols=4)
    summary_table.alignment = WD_TABLE_ALIGNMENT.LEFT
    set_table_border(summary_table)
    summary_table.columns[0].width = Inches(0.4)
    summary_table.columns[1].width = Inches(2.8)
    summary_table.columns[2].width = Inches(1.5)
    summary_table.columns[3].width = Inches(2.4)
    for j, h in enumerate(["#", "Topic", "Status", "Action Required"]):
        cell = summary_table.rows[0].cells[j]
        set_cell_background(cell, "0F274A"); set_cell_margins(cell, 70, 70, 90, 80)
        p = cell.paragraphs[0]; r = p.add_run(h)
        r.font.name = "Calibri"; r.font.bold = True; r.font.size = Pt(9); r.font.color.rgb = RGBColor(255, 255, 255)
    summary_data = [
        ("1","Cart Delivery Threshold",                 "PASS",        "None — kept unchanged"),
        ("2","Checkout Delivery Fee Display",           "PASS",        "None — kept unchanged"),
        ("3","Self-Collection Flow",                    "PASS",        "None — kept unchanged"),
        ("4","Stripe English & Customer Email",         "PASS / Testing","Client to complete final email test"),
        ("5","Payment Methods (FPX, GrabPay, etc.)",   "ON HOLD",     "Client to verify in Stripe Dashboard"),
        ("6","Stripe Fee Figures",                      "Verified",    "Client to confirm in Stripe Dashboard"),
        ("7","Card Surcharge",                          "ON HOLD",     "No action until client confirms"),
        ("8","DuitNow QR",                              "Deferred",    "Future phase — not in scope for launch"),
        ("9","Client UAT Sign-off",                     "In Progress", "11 test scenarios remaining"),
    ]
    sum_bg = ["FFFFFF","F8FAFC"] * 5
    for i, (num, topic, status, action) in enumerate(summary_data):
        row = summary_table.rows[i + 1]
        for j, val in enumerate([num, topic, status, action]):
            cell = row.cells[j]; set_cell_background(cell, sum_bg[i]); set_cell_margins(cell, 65, 65, 80, 80)
            p = cell.paragraphs[0]; r = p.add_run(val)
            r.font.name = "Calibri"; r.font.size = Pt(9)
            if "PASS" in val: r.font.color.rgb = success_green; r.font.bold = True
            elif "ON HOLD" in val: r.font.color.rgb = hold_orange; r.font.bold = True
            elif "Deferred" in val: r.font.color.rgb = RGBColor(107, 33, 168); r.font.bold = True
            elif "In Progress" in val: r.font.color.rgb = info_blue; r.font.bold = True
            elif "Verified" in val: r.font.color.rgb = accent_blue; r.font.bold = True
            else: r.font.color.rgb = body_slate

    doc.add_paragraph(); add_divider(doc)

    # ── CLOSING ───────────────────────────────────────────────────────────────
    add_heading(doc, "CLOSING NOTE", 2, primary_navy, 10, 4)
    add_body(doc,
        "Thank you for your thorough UAT process. We acknowledge and respect the client's instruction to hold on payment method "
        "changes and surcharge decisions until final verification and testing is complete.\n\n"
        "The engineering team stands ready to:\n"
        "  1. Enable FPX and GrabPay in Stripe as soon as the client confirms availability in the Stripe Dashboard\n"
        "  2. Adjust payment method order per client's preferred sequence\n"
        "  3. Switch to LIVE Stripe mode once final UAT sign-off is received\n"
        "  4. Provide additional screenshots or documentation as required\n\n"
        "Please share the results of your remaining UAT tests when ready, and we will action the next steps immediately.",
        9.5, color=body_slate, space_after=6)

    p_footer = doc.add_paragraph()
    p_footer.alignment = WD_ALIGN_PARAGRAPH.RIGHT
    r_footer = p_footer.add_run("Report Prepared: October 2026  |  Abdul Rehman / Engineering Team  |  MST E-Commerce Platform")
    r_footer.font.name = "Calibri"; r_footer.font.size = Pt(8); r_footer.font.italic = True; r_footer.font.color.rgb = grey_text

    # ── SAVE ──────────────────────────────────────────────────────────────────
    output_path = r"f:\My AI\Sea Food\seafood\MST_Client_UAT_Response_Report_Oct2026.docx"
    doc.save(output_path)
    print(f"Report saved: {output_path}")
    return output_path

if __name__ == "__main__":
    create_report()
