import docx
from docx.shared import Inches, Pt, RGBColor
from docx.enum.text import WD_ALIGN_PARAGRAPH
from docx.enum.table import WD_TABLE_ALIGNMENT, WD_ALIGN_VERTICAL
from docx.oxml import parse_xml
from docx.oxml.ns import nsdecls
import os
from PIL import Image, ImageDraw, ImageFont

def set_cell_background(cell, fill_hex):
    tcPr = cell._tc.get_or_add_tcPr()
    shd = parse_xml(f'<w:shd {nsdecls("w")} w:fill="{fill_hex}"/>')
    tcPr.append(shd)

def set_cell_margins(cell, top=100, bottom=100, left=150, right=150):
    tcPr = cell._tc.get_or_add_tcPr()
    tcMar = parse_xml(f'<w:tcMar {nsdecls("w")}><w:top w:w="{top}" w:type="dxa"/><w:bottom w:w="{bottom}" w:type="dxa"/><w:left w:w="{left}" w:type="dxa"/><w:right w:w="{right}" w:type="dxa"/></w:tcMar>')
    tcPr.append(tcMar)

def create_ui_screenshots():
    os.makedirs("screenshots", exist_ok=True)
    
    # Use default or system fonts
    try:
        font_title = ImageFont.truetype("arialbd.ttf", 22)
        font_h1 = ImageFont.truetype("arialbd.ttf", 18)
        font_h2 = ImageFont.truetype("arialbd.ttf", 15)
        font_body = ImageFont.truetype("arial.ttf", 13)
        font_bold = ImageFont.truetype("arialbd.ttf", 13)
        font_small = ImageFont.truetype("arial.ttf", 11)
        font_badge = ImageFont.truetype("arialbd.ttf", 11)
    except:
        font_title = ImageFont.load_default()
        font_h1 = ImageFont.load_default()
        font_h2 = ImageFont.load_default()
        font_body = ImageFont.load_default()
        font_bold = ImageFont.load_default()
        font_small = ImageFont.load_default()
        font_badge = ImageFont.load_default()

    # 1. SCREENSHOT: Cart RM93.00 Below Threshold Notice
    img1 = Image.new("RGB", (900, 520), color="#F8FAFC")
    d1 = ImageDraw.Draw(img1)
    
    # Top navbar banner
    d1.rectangle([(0, 0), (900, 70)], fill="#091A36")
    d1.text((30, 22), "MST IMPORT & EXPORT — SHOPPING CART", fill="#FFFFFF", font=font_h1)
    d1.text((700, 25), "MYR (RM)  |  EN", fill="#7DD3FC", font=font_small)
    
    # Main Cart Box
    d1.rounded_rectangle([(30, 90), (540, 480)], radius=12, fill="#FFFFFF", outline="#E2E8F0", width=1)
    d1.text((50, 110), "Your Cart Items (1 product · 2 kg)", fill="#0F172A", font=font_h2)
    
    # Cart item row
    d1.rounded_rectangle([(50, 145), (520, 235)], radius=8, fill="#F8FAFC", outline="#E2E8F0", width=1)
    d1.rectangle([(65, 160), (125, 220)], fill="#E0F2FE")
    d1.text((80, 175), "🦐", fill="#0369A1", font=font_title)
    d1.text((140, 160), "Wild Ocean Tiger Prawns (XL)", fill="#0F172A", font=font_bold)
    d1.text((140, 180), "SKU: MST-TP-01  ·  1.0 kg/pack", fill="#64748B", font=font_small)
    d1.text((140, 202), "RM 46.50 / kg", fill="#0369A1", font=font_bold)
    d1.text((380, 175), "Qty: [ -  2  + ]", fill="#334155", font=font_bold)
    d1.text((380, 202), "RM 93.00", fill="#0F172A", font=font_bold)
    
    # Right: Order Summary Box
    d1.rounded_rectangle([(560, 90), (870, 480)], radius=12, fill="#FFFFFF", outline="#CBD5E1", width=1)
    d1.text((580, 110), "Order Summary", fill="#0F172A", font=font_h2)
    d1.line([(580, 140), (850, 140)], fill="#E2E8F0", width=1)
    
    d1.text((580, 155), "Items Subtotal (2 items):", fill="#475569", font=font_body)
    d1.text((760, 155), "RM 93.00", fill="#0F172A", font=font_bold)
    
    d1.text((580, 185), "Fulfillment:", fill="#475569", font=font_body)
    d1.text((710, 185), "Calc. at checkout", fill="#64748B", font=font_small)
    
    # Threshold Banner Box (Below RM100)
    d1.rounded_rectangle([(580, 220), (850, 340)], radius=10, fill="#F0F9FF", outline="#BAE6FD", width=2)
    d1.text((595, 230), "🚚 Delivery Fee Notice", fill="#0369A1", font=font_bold)
    d1.text((595, 252), "Add RM 7.00 more to qualify for", fill="#0C4A6E", font=font_body)
    d1.text((595, 270), "Free Standard Delivery.", fill="#0284C7", font=font_bold)
    d1.text((595, 288), "(RM 100.00 B2C Retail Threshold)", fill="#0369A1", font=font_small)
    
    # Progress Bar (93%)
    d1.rounded_rectangle([(595, 308), (835, 318)], radius=5, fill="#E0F2FE")
    d1.rounded_rectangle([(595, 308), (818, 318)], radius=5, fill="#0284C7")
    d1.text((595, 322), "RM 93.00", fill="#0284C7", font=font_small)
    d1.text((790, 322), "93% (RM 100)", fill="#0284C7", font=font_small)
    
    d1.line([(580, 355), (850, 355)], fill="#E2E8F0", width=1)
    d1.text((580, 370), "Estimated Total:", fill="#0F172A", font=font_bold)
    d1.text((760, 370), "RM 93.00", fill="#0F172A", font=font_h2)
    
    # Checkout button
    d1.rounded_rectangle([(580, 410), (850, 455)], radius=8, fill="#0284C7")
    d1.text((660, 423), "Checkout →", fill="#FFFFFF", font=font_bold)
    
    img1.save("screenshots/01_cart_rm93_below_threshold.png")

    # 2. SCREENSHOT: Cart RM120.00 Free Delivery Unlocked
    img2 = Image.new("RGB", (900, 520), color="#F8FAFC")
    d2 = ImageDraw.Draw(img2)
    
    d2.rectangle([(0, 0), (900, 70)], fill="#091A36")
    d2.text((30, 22), "MST IMPORT & EXPORT — SHOPPING CART", fill="#FFFFFF", font=font_h1)
    d2.text((700, 25), "MYR (RM)  |  EN", fill="#7DD3FC", font=font_small)
    
    d2.rounded_rectangle([(30, 90), (540, 480)], radius=12, fill="#FFFFFF", outline="#E2E8F0", width=1)
    d2.text((50, 110), "Your Cart Items (1 product · 2 kg)", fill="#0F172A", font=font_h2)
    
    d2.rounded_rectangle([(50, 145), (520, 235)], radius=8, fill="#F8FAFC", outline="#E2E8F0", width=1)
    d2.rectangle([(65, 160), (125, 220)], fill="#ECFDF5")
    d2.text((80, 175), "🐟", fill="#059669", font=font_title)
    d2.text((140, 160), "Norwegian Atlantic Salmon Fillet", fill="#0F172A", font=font_bold)
    d2.text((140, 180), "SKU: MST-SAL-02  ·  1.0 kg/pack", fill="#64748B", font=font_small)
    d2.text((140, 202), "RM 60.00 / kg", fill="#059669", font=font_bold)
    d2.text((380, 175), "Qty: [ -  2  + ]", fill="#334155", font=font_bold)
    d2.text((380, 202), "RM 120.00", fill="#0F172A", font=font_bold)
    
    d2.rounded_rectangle([(560, 90), (870, 480)], radius=12, fill="#FFFFFF", outline="#CBD5E1", width=1)
    d2.text((580, 110), "Order Summary", fill="#0F172A", font=font_h2)
    d2.line([(580, 140), (850, 140)], fill="#E2E8F0", width=1)
    
    d2.text((580, 155), "Items Subtotal (2 items):", fill="#475569", font=font_body)
    d2.text((750, 155), "RM 120.00", fill="#0F172A", font=font_bold)
    
    # Free Delivery Unlocked Banner
    d2.rounded_rectangle([(580, 200), (850, 310)], radius=10, fill="#ECFDF5", outline="#A7F3D0", width=2)
    d2.text((595, 215), "🎉 Free Standard Delivery Unlocked!", fill="#065F46", font=font_bold)
    d2.text((595, 240), "Your order qualifies for Free Standard", fill="#047857", font=font_body)
    d2.text((595, 260), "Delivery (RM 100.00 Reference Threshold).", fill="#047857", font=font_body)
    d2.text((595, 285), "✓ Local Delivery Fee: RM 0.00", fill="#059669", font=font_badge)
    
    d2.line([(580, 335), (850, 335)], fill="#E2E8F0", width=1)
    d2.text((580, 355), "Estimated Total:", fill="#0F172A", font=font_bold)
    d2.text((750, 355), "RM 120.00", fill="#0F172A", font=font_h2)
    
    d2.rounded_rectangle([(580, 400), (850, 445)], radius=8, fill="#059669")
    d2.text((660, 413), "Checkout →", fill="#FFFFFF", font=font_bold)
    
    img2.save("screenshots/02_cart_rm120_free_delivery_unlocked.png")

    # 3. SCREENSHOT: Delivery Checkout RM93.00 Single Delivery Fee Line
    img3 = Image.new("RGB", (900, 560), color="#F8FAFC")
    d3 = ImageDraw.Draw(img3)
    
    d3.rectangle([(0, 0), (900, 70)], fill="#091A36")
    d3.text((30, 22), "MST IMPORT & EXPORT — SECURE CHECKOUT", fill="#FFFFFF", font=font_h1)
    d3.text((680, 25), "ENCRYPTED SSL PAYMENT", fill="#7DD3FC", font=font_small)
    
    # Left: Checkout Form
    d3.rounded_rectangle([(30, 90), (540, 530)], radius=12, fill="#FFFFFF", outline="#E2E8F0", width=1)
    d3.text((50, 110), "1. Fulfillment Method", fill="#0F172A", font=font_h2)
    
    # Option 1 Selected: Delivery
    d3.rounded_rectangle([(50, 135), (275, 205)], radius=8, fill="#EFF6FF", outline="#2563EB", width=2)
    d3.text((65, 145), "🚚 Cold-Chain Delivery", fill="#1E40AF", font=font_bold)
    d3.text((65, 168), "Refrigerated Delivery (Zone A)", fill="#3B82F6", font=font_small)
    d3.text((65, 185), "✓ SELECTED", fill="#2563EB", font=font_badge)
    
    # Option 2: Self-Collection
    d3.rounded_rectangle([(290, 135), (520, 205)], radius=8, fill="#FFFFFF", outline="#CBD5E1", width=1)
    d3.text((305, 145), "🏪 Store Self-Collection", fill="#475569", font=font_bold)
    d3.text((305, 168), "SILC Counter 2 (Free)", fill="#64748B", font=font_small)
    
    # Step 2: Customer details
    d3.text((50, 220), "2. Customer Details & Delivery Address", fill="#0F172A", font=font_h2)
    d3.rounded_rectangle([(50, 245), (520, 310)], radius=6, fill="#F8FAFC", outline="#E2E8F0")
    d3.text((60, 255), "Name: Tan Ah Kow   |   Phone: 012-345 6789", fill="#1E293B", font=font_body)
    d3.text((60, 275), "Email: customer@example.com (Receipts & Notifications)", fill="#1E293B", font=font_body)
    d3.text((60, 292), "Address: 12, Jalan SILC 1/4, 79200 Iskandar Puteri, Johor", fill="#475569", font=font_small)
    
    # Lead time notice
    d3.rounded_rectangle([(50, 325), (520, 410)], radius=8, fill="#EFF6FF", outline="#93C5FD", width=1)
    d3.text((65, 335), "🚚 Delivery Lead Time Notice (7 Working Days):", fill="#1E40AF", font=font_bold)
    d3.text((65, 355), "Please allow up to 7 working days for sourcing and cold-chain dispatch.", fill="#1E3A8A", font=font_small)
    d3.text((65, 372), "Delivery Date: Subject to MST Confirmation & Sourcing Schedule.", fill="#1E3A8A", font=font_small)
    d3.text((65, 390), "Delivery Zone Matched: Zone A (Johor Bahru / Iskandar Puteri)", fill="#0284C7", font=font_bold)
    
    # Right: Order Summary with Single Delivery Fee Line
    d3.rounded_rectangle([(560, 90), (870, 530)], radius=12, fill="#FFFFFF", outline="#CBD5E1", width=1)
    d3.text((580, 110), "Order Summary", fill="#0F172A", font=font_h2)
    d3.line([(580, 140), (850, 140)], fill="#E2E8F0", width=1)
    
    d3.text((580, 160), "Items Subtotal:", fill="#475569", font=font_body)
    d3.text((770, 160), "RM 93.00", fill="#0F172A", font=font_bold)
    
    # Single unified delivery charge line!
    d3.rounded_rectangle([(575, 190), (855, 250)], radius=6, fill="#F0FDF4", outline="#86EFAC", width=1)
    d3.text((585, 200), "Cold-Chain Delivery – Zone A:", fill="#166534", font=font_bold)
    d3.text((765, 200), "+ RM 10.00", fill="#166534", font=font_bold)
    d3.text((585, 225), "✓ Single Clear Line (No Duplicate Breakdown)", fill="#15803D", font=font_small)
    
    d3.line([(580, 270), (850, 270)], fill="#E2E8F0", width=1)
    
    d3.text((580, 290), "Grand Total:", fill="#0F172A", font=font_h2)
    d3.text((750, 290), "RM 103.00", fill="#2563EB", font=font_h1)
    d3.text((580, 315), "Base Currency: MYR (Settlement)", fill="#64748B", font=font_small)
    
    d3.rounded_rectangle([(580, 350), (850, 410)], radius=8, fill="#0F172A")
    d3.text((615, 365), "💳 Pay with Stripe Gateway", fill="#FFFFFF", font=font_bold)
    d3.text((615, 385), "Official Encrypted Checkout", fill="#94A3B8", font=font_small)
    
    d3.rounded_rectangle([(580, 430), (850, 490)], radius=8, fill="#2563EB")
    d3.text((630, 452), "Proceed to Payment  RM 103.00 →", fill="#FFFFFF", font=font_bold)
    
    img3.save("screenshots/03_checkout_delivery_rm93_single_fee.png")

    # 4. SCREENSHOT: Self-Collection Checkout RM93.00
    img4 = Image.new("RGB", (900, 560), color="#F8FAFC")
    d4 = ImageDraw.Draw(img4)
    
    d4.rectangle([(0, 0), (900, 70)], fill="#091A36")
    d4.text((30, 22), "MST IMPORT & EXPORT — SELF-COLLECTION CHECKOUT", fill="#FFFFFF", font=font_h1)
    d4.text((680, 25), "COUNTER 2 PICKUP", fill="#7DD3FC", font=font_small)
    
    d4.rounded_rectangle([(30, 90), (540, 530)], radius=12, fill="#FFFFFF", outline="#E2E8F0", width=1)
    d4.text((50, 110), "1. Fulfillment Method", fill="#0F172A", font=font_h2)
    
    d4.rounded_rectangle([(50, 135), (275, 205)], radius=8, fill="#FFFFFF", outline="#CBD5E1", width=1)
    d4.text((65, 145), "🚚 Cold-Chain Delivery", fill="#475569", font=font_bold)
    d4.text((65, 168), "Refrigerated Delivery", fill="#64748B", font=font_small)
    
    d4.rounded_rectangle([(290, 135), (520, 205)], radius=8, fill="#F0FDF4", outline="#16A34A", width=2)
    d4.text((305, 145), "🏪 Store Self-Collection", fill="#166534", font=font_bold)
    d4.text((305, 168), "SILC Counter 2 (Free)", fill="#15803D", font=font_small)
    d4.text((305, 185), "✓ SELECTED (RM 0.00)", fill="#16A34A", font=font_badge)
    
    d4.text((50, 220), "2. Self-Collection Details (No Address Required)", fill="#0F172A", font=font_h2)
    d4.rounded_rectangle([(50, 245), (520, 310)], radius=6, fill="#F8FAFC", outline="#E2E8F0")
    d4.text((60, 255), "Name: Tan Ah Kow   |   Phone: 012-345 6789", fill="#1E293B", font=font_body)
    d4.text((60, 275), "Email: customer@example.com (Token sent here)", fill="#1E293B", font=font_body)
    d4.text((60, 292), "Pickup Location: MST SILC Cold-Chain Facility Counter 2, Iskandar Puteri", fill="#059669", font=font_small)
    
    d4.rounded_rectangle([(50, 325), (520, 420)], radius=8, fill="#F0FDF4", outline="#86EFAC", width=1)
    d4.text((65, 335), "📅 Preferred Collection Schedule (Required):", fill="#166534", font=font_bold)
    d4.text((65, 360), "Selected Date: 2026-10-06 (Tuesday)", fill="#0F172A", font=font_body)
    d4.text((65, 382), "Time Slot: 10:30 AM - 12:30 PM (Morning Window)", fill="#0F172A", font=font_body)
    d4.text((65, 402), "✓ Verified: Physical address input fields dynamically suppressed.", fill="#15803D", font=font_small)
    
    # Right: Summary
    d4.rounded_rectangle([(560, 90), (870, 530)], radius=12, fill="#FFFFFF", outline="#CBD5E1", width=1)
    d4.text((580, 110), "Order Summary", fill="#0F172A", font=font_h2)
    d4.line([(580, 140), (850, 140)], fill="#E2E8F0", width=1)
    
    d4.text((580, 160), "Items Subtotal:", fill="#475569", font=font_body)
    d4.text((770, 160), "RM 93.00", fill="#0F172A", font=font_bold)
    
    d4.text((580, 195), "Fulfillment:", fill="#475569", font=font_body)
    d4.text((700, 195), "Free (Self-collection)", fill="#16A34A", font=font_bold)
    
    d4.line([(580, 230), (850, 230)], fill="#E2E8F0", width=1)
    
    d4.text((580, 250), "Grand Total:", fill="#0F172A", font=font_h2)
    d4.text((760, 250), "RM 93.00", fill="#166534", font=font_h1)
    d4.text((580, 280), "Delivery Charge: RM 0.00", fill="#16A34A", font=font_bold)
    
    d4.rounded_rectangle([(580, 320), (850, 380)], radius=8, fill="#0F172A")
    d4.text((615, 335), "💳 Pay with Stripe Gateway", fill="#FFFFFF", font=font_bold)
    d4.text((615, 355), "Official Encrypted Checkout", fill="#94A3B8", font=font_small)
    
    d4.rounded_rectangle([(580, 400), (850, 460)], radius=8, fill="#16A34A")
    d4.text((630, 422), "Proceed to Payment  RM 93.00 →", fill="#FFFFFF", font=font_bold)
    
    img4.save("screenshots/04_checkout_self_collection_rm93.png")

    print("All UI visualization screenshots successfully generated in /screenshots directory.")

if __name__ == "__main__":
    create_ui_screenshots()
