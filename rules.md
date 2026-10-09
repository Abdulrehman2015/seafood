# Business, Technical & Operational Rules

**Project:** MST Seafood Unified B2B & B2C E-Commerce Platform  
**Target File Reference:** `rules.md`  
**Enforcement Level:** Mandatory System Constraints  
**Version:** 2.0 (Post-Consolidation UAT Alignment)  

---

## 1. Customer Tier & Pricing Governance Rules

### Rule 1.1: Server-Side Price Protection (Zero Margin Leakage)
- **Constraint:** Wholesale (`price_wholesale`) and Trading (`price_trading`) prices must NEVER be delivered to client-side DOM or JavaScript payloads for unauthorized users.
- **Enforcement:** Pricing is resolved strictly on the server (`Product::getPriceForGroup($group)`). If a visitor is a guest or Retail customer, wholesale and trading price attributes are stripped from API and view responses.
- **Validation:** Inspecting network payloads or page source code will never expose distributor or wholesale margins to competitors or retail consumers.

### Rule 1.2: Minimum Order Quantity (MOQ) Validation
- **Retail:** `MOQ = 1` unit / package.
- **Walk-In:** `MOQ = 1` unit (or standard retail unit specification).
- **Wholesale:** Item-level MOQ (`moq_wholesale`) is mandatory. Cart validation prevents checkout progression if any line item quantity is below the SKU threshold.
- **Trading:** Pallet or bulk MOQ (`moq_trading`) is enforced on trading SKUs.

### Rule 1.3: Walk-In Express Flow Isolation
- **Constraint:** Walk-In purchases can only occur via on-premise QR scan or tokenized walk-in route (`/walkin`).
- **Fulfillment:** Walk-In customers are restricted to **Store Self-Collection only** (delivery options are blocked).
- **Catalog Filter:** Only items marked `is_walkin_available = 1` appear in the walk-in catalog.

---

## 2. Fulfillment, Logistics & Postal Coverage Rules

### Rule 2.1: Zone A Geographic Scope (10 Confirmed Areas)
Zone A coverage is restricted to the 10 confirmed areas compiled from Postcode.my:
1. Gelang Patah (`81550`, `79200`)
2. Iskandar Puteri / Nusajaya (`79000`, `79100`, `79200`, `79250`, `79500`)
3. Johor Bahru Core (`80000`–`80990`, `81200`)
4. Kulai — Indahpura Only (`81000`)
5. Masai (`81750`)
6. Senai (`81400`)
7. Skudai (`81300`)
8. Setia Eco Gardens (`81550`)
9. Mount Austin (`81100`)
10. Confirmed ICQ Area (Iskandar Puteri / Second Link CIQ)

### Rule 2.2: Kulai Strict Locality Validation (Indahpura Only)
- **Constraint:** Not all of Kulai is Zone A. Postcode `81000` covers both urban Indahpura and rural Kulai.
- **Rule:** If postcode is `81000`, the delivery address or city field **must contain the string `indahpura`** to qualify for Zone A.
- **Fallback:** Postcode `81000` without `indahpura` is classified as **Zone B / Outstation Cold-Chain**.

### Rule 2.3: Skudai Postcode 81300 Inclusion
- **Constraint:** Postcode `81300` in Skudai is recognized as Zone A direct local delivery.
- **Rule:** Hardcoded exclusions on `81300` are prohibited. Both cart and checkout treat `81300` as Zone A.

### Rule 2.4: Free Delivery Threshold Mechanics
- **B2C Threshold:** RM 150.00.
  - Subtotal `< RM 150.00`: RM 10.00 local delivery fee applied.
  - Subtotal `≥ RM 150.00`: RM 0.00 Free Standard Delivery.
- **B2B Threshold:** RM 350.00 (configured in store settings).
- **Non-Blocking Rule:** The RM150/RM350 amount is an incentive threshold, **not a blocking minimum order**. Orders of any value (e.g. RM25, RM50, RM80) are permitted to proceed.

### Rule 2.5: Outstation Cold-Chain Billing & Transparency
- **Constraint:** Outstation cold-chain delivery requires specialized Styrofoam box packaging and dry ice sizing.
- **Rule:** Outstation orders display *“Outstation Transportation Fee — To Be Confirmed”*.
- **Payment Engine Rule:** Exactly **RM 0.00** delivery fee is added to the Stripe payment intent. MST will coordinate logistics costs with the customer via WhatsApp before dispatch.

### Rule 2.6: Store Self-Collection Protections
- **Constraint:** Pickup at SILC Counter 2 is 100% free (RM 0.00).
- **UI Rule:** When Self-Collection is selected:
  1. Delivery street address, city, state, and postcode fields are hidden.
  2. Pickup Date (`collection_date`) and Time Slot (`collection_time`) are required.

### Rule 2.7: Delivery Lead Time Notice
- **Constraint:** All delivery orders must display the standard notice:
  *“Please allow up to 7 working days for order sourcing and cold-chain delivery arrangements. The delivery date will be confirmed by MST based on product availability and delivery scheduling.”*

---

## 3. Data Protection, Cryptography & Security Rules

### Rule 3.1: Zero Plaintext Credentials in `.env`
- **Constraint:** Plaintext passwords, third-party API keys, and Stripe secrets must NOT be committed to git or stored in cleartext in `.env`.
- **Enforcement:** `.env` maintains blank placeholders (`MAIL_PASSWORD=`, `STRIPE_SECRET=`). Credentials reside in the database `settings` table.

### Rule 3.2: Authenticated Hash/Cipher Storage (`enc:...`)
- **Constraint:** All sensitive setting keys (`mail_password`, `stripe_secret`, `stripe_test_secret`, `stripe_live_secret`, `whatsapp_api_key`) must be encrypted before database insertion.
- **Algorithm:** OpenSSL AES-256-CBC cipher with HMAC-SHA256 authenticated signature (`Crypt::encryptString`), prefixed with `enc:`.
- **Inspection Rule:** Raw SQL queries or database backups must only show encrypted cipher strings.

### Rule 3.3: Email OTP Anti-Brute-Force Lockout
- **Code:** 6-digit cryptographic verification code.
- **Window:** 10-minute expiration.
- **Rate Limit:** Maximum 3 verification attempts per OTP.
- **Lockout:** Exceeding 3 failed attempts sets `email_otp_blocked_at` and requires administrative manual unblock in the backend.

---

## 4. Administrative Governance & Responsibility Matrix

| Administrative Function | Client Managed via Admin Panel? | Developer Technical Assistance Required? |
| :--- | :--- | :--- |
| Updating Company Address, Phone, WhatsApp, Email | **YES** (`/admin/settings`) | No |
| Updating Banners, Announcements, Hero Text | **YES** (`/admin/settings` & `/admin/policies`) | No |
| Editing Product Prices, Descriptions, Photos, Stock | **YES** (`/admin/products`) | No |
| Adding New Categories & Sorting Order | **YES** (`/admin/categories`) | No |
| Adding / Removing Postcodes in Delivery Zones | **YES** (`/admin/delivery-zones`) | No |
| Changing Free Delivery Threshold (RM150) | **YES** (`/admin/delivery-zones`) | No |
| Reviewing & Approving B2B Wholesale Applicants | **YES** (`/admin/customers`) | No |
| Setting Confirmed Delivery Dates & Notifying Buyers | **YES** (`/admin/orders`) | No |
| Translating Product & Website UI Strings (EN/ZH/BM) | **YES** (`/admin/translations`) | No |
| Downloading Full .SQL Database Backups | **YES** (`/admin/database`) | No |
| Adding New Payment Gateway (e.g. FPX direct, e-Wallets) | No | **YES** (Requires Gateway API Integration) |
| Altering Core Database Architecture or Schemas | No | **YES** (Requires Laravel Migration) |
| Redesigning Page Layouts or Header Navigation Bars | No | **YES** (Requires Blade Template Editing) |
