# UI/UX Design System & Specification

**Project:** MST Seafood Unified B2B & B2C E-Commerce Platform  
**Target File Reference:** `design.md`  
**Aesthetic Vision:** Premium Ocean Cold-Chain Maritime Commercial Experience  
**Core Design Philosophy:** Clarity, Clean Contrast, Trustworthiness, Zero Clutter  

---

## 1. Visual Design Philosophy & Mood

The visual language of the MST platform reflects the cold-chain frozen seafood industry: clean arctic blues, deep marine navy, crisp whites, and vibrant accent blues. 

The user interface balances two distinct commercial requirements:
1. **Consumer Trust & Appetite Appeal (B2C & Walk-In):** High-resolution product imagery, clear catch specifications, freshness guarantees, and transparent delivery thresholds.
2. **Operational Efficiency & Speed (B2B & Wholesale):** Clear tabular pricing, prominent carton MOQ badges, rapid bulk re-ordering, printable tax invoices, and uncluttered checkout flows.

---

## 2. Design Tokens & Color Palette

### 2.1. Color System

| Token Name | Hex Code | Semantic Role | Usage Context |
| :--- | :--- | :--- | :--- |
| **Brand Primary Navy** | `#0F274A` | Primary Brand | Headers, hero background, primary buttons, admin top bar |
| **Marine Accent Blue** | `#1D4ED8` | Primary Interactive | Links, active tab states, primary CTAs, progress indicators |
| **Ocean Teal** | `#0F766E` | Cold-Chain Freshness | Cold-chain badges, quality certifications, secondary accents |
| **Dark Heading** | `#0F172A` | Typography (H1-H3) | Page titles, product names, price headers |
| **Charcoal Body** | `#1E293B` | Typography (Body) | Paragraph text, product descriptions, table values |
| **Slate Secondary** | `#475569` | Secondary Text | Breadcrumbs, metadata labels, SKU badges, timestamps |
| **Border Gray** | `#E2E8F0` | Structural Borders | Card outlines, table dividers, input borders |
| **Card Surface** | `#FFFFFF` | Background Surface | Product cards, checkout cards, modal backgrounds |
| **Muted Background** | `#F8FAFC` | Page Background | Body background, table alternate row striping |
| **Success Green** | `#166534` | Confirmation / Free | Free delivery badges, paid status, order completed |
| **Alert Amber** | `#B45309` | Notices / Warnings | Outstation quotation notices, MOQ shortfalls, pending review |
| **Error Red** | `#DC2626` | Destructive Actions | Validation errors, out-of-stock badges, account blocked |

### 2.2. CSS Custom Properties
```css
:root {
    --color-primary-navy: #0f274a;
    --color-accent-blue: #1d4ed8;
    --color-ocean-teal: #0f766e;
    --color-heading: #0f172a;
    --color-body: #1e293b;
    --color-slate: #475569;
    --color-border: #e2e8f0;
    --color-surface: #ffffff;
    --color-bg: #f8fafc;
    --color-success: #166534;
    --color-warning: #b45309;
    --color-error: #dc2626;

    --font-heading: 'Outfit', 'Inter', system-ui, -apple-system, sans-serif;
    --font-body: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, sans-serif;

    --radius-sm: 6px;
    --radius-md: 10px;
    --radius-lg: 14px;
    --radius-full: 9999px;

    --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.05);
    --shadow-md: 0 4px 12px rgba(15, 39, 74, 0.08);
    --shadow-lg: 0 10px 25px rgba(15, 39, 74, 0.12);
}
```

---

## 3. Typography Hierarchy

| Level | Font Family | Size (Desktop / Mobile) | Weight | Line Height |
| :--- | :--- | :--- | :--- | :--- |
| **Hero Title (H1)** | Outfit / Heading Stack | `clamp(1.75rem, 4vw, 2.5rem)` | 800 (Extra Bold) | 1.15 |
| **Section Header (H2)** | Outfit / Heading Stack | `clamp(1.35rem, 2.8vw, 1.85rem)` | 700 (Bold) | 1.25 |
| **Card Title (H3)** | Outfit / Heading Stack | `1.15rem` (18.4px) | 700 (Bold) | 1.3 |
| **Body Primary** | Inter / Body Stack | `0.95rem` (15.2px) | 400 (Regular) | 1.55 |
| **Body Bold / Labels** | Inter / Body Stack | `0.90rem` (14.4px) | 600 (Semi-Bold) | 1.4 |
| **Badges & Footnotes** | Inter / Body Stack | `0.78rem` (12.5px) | 500 (Medium) | 1.35 |

---

## 4. Key Page Layouts & Component Design

### 4.1. Navigation Bar & Header
- **Top Utility Strip:** Displays physical warehouse operating hours (`Mon–Sat: 8am–6pm`), WhatsApp quick link (`+60 11-1271 0260`), active currency indicator (MYR/SGD/USD), and trilingual locale dropdown (EN / 中文 / BM).
- **Main Navbar:** Brand logo, search bar with autocomplete, primary navigation links (Shop, Categories, Wholesale Sourcing, Walk-in Express, Contact), customer tier badge (`Retail Customer`, `Wholesale Partner`, `In-Store Walk-in`), and interactive shopping cart pill with live badge counter.

### 4.2. Shopping Cart Component
- **Dynamic Delivery Progress Tracker:** Visual progress bar at the top of the cart showing order progress toward the RM150 (B2C) or RM350 (B2B) free delivery threshold:
  - If subtotal < threshold: Displays animated amber progress bar with remaining shortfall (e.g. `“Add RM 42.50 more to unlock Free Standard Delivery!”`).
  - If subtotal ≥ threshold: Unlocks green badge (`“🎉 Congratulations! You have unlocked Free Standard Delivery (Zone A).”`).
- **Line Item Cards:** High-resolution product thumbnail, SKU tag, localized packaging unit (e.g. `1 kg pack`), unit price, quantity increment/decrement buttons, line subtotal, and remove action.
- **Tier MOQ Indicator:** If a wholesale customer selects fewer items than the required MOQ, the card renders a prominent alert badge and blocks checkout progression until satisfied.

### 4.3. Checkout Experience (3-Step Stepper)
- **Visual Stepper:** Step 1: Shopping Cart (Completed) → Step 2: Checkout & Payment (Active) → Step 3: Order Confirmation.
- **Fulfillment Selector (Radio Tiles):**
  - **Cold-Chain Delivery:** Displays street address, postcode, city, state, and 7-day lead-time notice. Postcode changes trigger debounced API calls to recalculate fees.
  - **Store Self-Collection (Counter 2, SILC):** Selecting pickup automatically animates and hides delivery address fields, renders the SILC facility pickup map, sets the delivery fee to RM 0.00, and requires mandatory selection of a Pickup Date and Shift Time Slot (8:30–10:30 AM, 10:30 AM–12:30 PM, 1:30–3:30 PM, 3:30–5:30 PM).
- **Dynamic Notice Banner:**
  - *Zone A Matched:* Renders green or blue notice indicating applicable local delivery fee (RM10 or Free RM0).
  - *Outstation Matched:* Renders orange notice banner stating: *“Outstation Cold-Chain Delivery: Outstation transportation charges will be confirmed by MST via WhatsApp based on destination and Styrofoam box size. Exactly RM0.00 is charged now on Stripe.”* Includes direct WhatsApp button.
- **Mobile Collapsible Order Summary:** On screens < 992px, order items and fee breakdowns are collapsed into a clean top drawer to maximize vertical form space.

### 4.4. Order Confirmation & Formal Invoicing
- **Customer Success View:** Displays order reference (`ORD-...`), fulfillment method, confirmed or requested delivery/collection timestamp, and transaction status.
- **Printable Formal Invoice (`/admin/orders/{id}/invoice`):** Clean, standard commercial tax invoice formatted for A4 printing:
  - Header: MST company name, Chinese trading name, SSM registration number, SILC physical address.
  - Customer Box: Name, company, contact number, delivery address or pickup token.
  - Line Items: SKU, item description, quantity, unit price (MYR), and line subtotal.
  - Summary: Merchandise subtotal, itemized delivery fee, total in figures, and formal amount in words.

---

## 5. Responsive Breakpoints & Accessibility

| Breakpoint | Target Devices | Layout Adaptations |
| :--- | :--- | :--- |
| **`< 576px` (Mobile)** | Smartphones | Single-column form, bottom sticky checkout bar, 44px minimum touch targets |
| **`576px – 768px` (Tablet Portrait)** | Large Phones, Small Tablets | 2-column product grid, stacked stepper bar |
| **`768px – 992px` (Tablet Landscape)** | iPads, Surface Tablets | Collapsible order summary tray, expanded filter drawer |
| **`≥ 992px` (Desktop)** | Laptops, Desktop Monitors | 2-column checkout layout (60% Form / 40% Sticky Order Summary), 4-column product grid |

- **Contrast Ratios:** All text elements exceed WCAG AA standards (4.5:1 minimum contrast ratio against card surfaces).
- **Form Validation:** Inline error feedback with clear, accessible red text messages and red input border highlights.
