# Product Requirements Document (PRD)

**Project Name:** Unified B2B & B2C Frozen Seafood E-Commerce & Cold-Chain Logistics Platform  
**Client Entity:** MST Import and Export Sdn. Bhd. (镁嘉国际贸易有限公司)  
**Lead Architect:** Abdul Rehman  
**Target Markets:** Johor Bahru (Local Direct), West Malaysia (Outstation Cold-Chain), Singapore (Cross-Border Sourcing)  
**Version:** 2.0 (Production Release / UAT Sign-Off Phase)  
**Date:** October 2026  

---

## 1. Executive Summary & Vision

MST Import and Export Sdn. Bhd. is an established frozen seafood importer and distribution enterprise operating out of a cold-chain storage facility in SILC Industrial Park, Iskandar Puteri, Johor Bahru. 

The primary business objective is to transition from manual, phone-based, and fragmented sales channels into a unified digital commerce platform capable of simultaneously serving four distinct buyer groups:
1. **Retail Consumers (B2C)** purchasing frozen seafood packages for home consumption.
2. **Walk-In Store Customers** accessing a fast-track on-premise catalog via physical QR code scanning.
3. **Wholesale Accounts (B2B)** comprising restaurants, caterers, and grocers requiring tiered trade pricing and strict carton-level Minimum Order Quantities (MOQs).
4. **Bulk Trading Clients** requesting floating market quotations and commercial pallet-scale freight shipments.

### Core Value Proposition
- **Strict Role-Based Price Segregation:** Zero leakage of wholesale or trading price margins to retail guests.
- **Precision Cold-Chain Logistics:** Dynamic postal routing across 10 designated Zone A districts in Johor with free delivery threshold incentives (RM150 B2C / RM350 B2B).
- **Trilingual Accessibility:** Frictionless switching across English, Simplified Chinese (简体中文), and Bahasa Melayu (BM).
- **Multi-Currency Clarity:** Dual display in Malaysian Ringgit (MYR), Singapore Dollar (SGD), and US Dollar (USD) with live exchange rate synchronization and MYR base legal settlement.
- **Enterprise Security at Rest:** Cryptographic Hash/Cipher storage for sensitive configuration, eliminating plaintext credential exposure.

---

## 2. Customer Personas & Role Matrix

| Customer Tier | Entry & Authentication | Catalog Visibility | Pricing Schedule | MOQ Constraints | Fulfillment & Payment Methods |
| :--- | :--- | :--- | :--- | :--- | :--- |
| **1. Retail Customer (B2C)** | Public browsing; guest checkout or standard self-registration. | Full retail catalog. | Retail Price (MYR/SGD/USD) | MOQ = 1 unit / pack | • Online Stripe card checkout or Cash<br>• Zone A Local Delivery or Store Self-Collection |
| **2. Walk-In Customer** | Physical QR scan at SILC Counter 2; session-based token. | Dedicated Walk-in in-stock catalog (`is_walkin_available = 1`). | Walk-In Price | Unit purchase (MOQ = 1) | • Immediate Counter Cash or Stripe<br>• Store Self-Collection only (Address hidden) |
| **3. Wholesale Partner (B2B)** | Self-registration with SSM business number; pending admin approval. | Full wholesale commercial catalog. | Wholesale Price (Exclusive) | Mandatory item/carton thresholds (`moq_wholesale`) | • Stripe or credit checkout<br>• Local delivery, Outstation, or Pickup<br>• 1-Click order re-order |
| **4. Trading Client (Bulk)** | Business vetting & executive administrator verification. | Bulk commodity seafood catalog. | Trading Price & RFQ Floating Quote | Pallet / Bulk thresholds (`moq_trading`) | • Standard checkout OR Request for Quotation (RFQ)<br>• Custom logistics quotation via WhatsApp |

---

## 3. Detailed Feature Specifications

### 3.1. Multi-Tier Product & Inventory Catalog
- **Multi-Price Engine:** Up to four independent prices per SKU: `price_retail`, `price_walkin`, `price_wholesale`, `price_trading`.
- **Foreign Currency Fields:** Optional product-level overrides for `wholesale_price_sgd`, `trading_price_usd`.
- **Cold-Chain Product Specifications:** Catch method, country of origin, glazing percentage, packaging specifications, storage temperature (-18°C), and random catch-weight indicators.
- **Inventory & Stock Decrement:** Automated stock deduction on order confirmation; low-stock alerts and visibility switches (`is_active`, `track_stock`).
- **Media Gallery:** Multi-image gallery with WebP auto-compression and folder management.

### 3.2. Cold-Chain Fulfillment & Delivery Logistics
- **Zone A Local Delivery:** Covers 10 confirmed areas in Southern Johor:
  1. Gelang Patah (`81550`, `79200`)
  2. Iskandar Puteri / Nusajaya (`79000`, `79100`, `79200`, `79250`, `79500`)
  3. Johor Bahru Core (`80000`–`80990`, `81200`)
  4. Kulai — Indahpura Only (`81000` strictly filtered by "Indahpura" keyword)
  5. Masai (`81750`)
  6. Senai (`81400`)
  7. Skudai (`81300` recognized as Zone A)
  8. Setia Eco Gardens (`81550`)
  9. Mount Austin (`81100`)
  10. Confirmed ICQ Second Link Checkpoint Area
- **Delivery Fee Rules:**
  - Zone A orders `< RM150`: RM10 delivery fee automatically applied.
  - Zone A orders `≥ RM150`: Free Standard Delivery (RM0.00).
  - Outstation Orders: Tagged as *“Outstation Transportation Fee — To Be Confirmed”*; RM0 added to Stripe checkout; WhatsApp coordination link displayed.
  - Delivery Lead Time: Mandatory notice displayed: *“Please allow up to 7 working days... delivery date confirmed by MST.”*
- **Store Self-Collection:** 100% Free (RM0.00) at Counter 2, SILC Industrial Park. Street address fields hide automatically; collection date and shift time slot selection are mandatory.

### 3.3. Request for Quotation (RFQ) System (Trading Tier)
- Floating-market items allow Trading clients to build an RFQ inquiry cart rather than immediate payment.
- Sales workbench (`/admin/quotations`) allows managers to input negotiated pricing, set validity expiration timestamps, and dispatch quotes back to the client account.
- 1-Click Quote-to-Order conversion within the validity window.

### 3.4. Payment Processing & Gateway Security
- **Stripe Official Hosted Checkout:** Full Stripe Checkout session redirect with automated webhook signature validation (`checkout.session.completed`).
- **Cash Payments:** Walk-in counter cash and verified wholesale cash-on-collection options generating instant payment reference tokens.
- **Zero Pre-Billing on Outstation Freight:** Unquoted Styrofoam box and cold-chain freight fees are never added to Stripe pre-authorizations.

### 3.5. Trilingual Localization & Currency Conversion
- **Locales:** English (`en`), Simplified Chinese (`zh` / 简体中文), Bahasa Melayu (`bm`).
- **Dynamic Translation CMS:** Administrators can edit UI phrases, banners, and button text directly in the backend (`/admin/translations`).
- **Multi-Currency:** Live FX rate sync via Open Exchange Rates API with administrative manual rate overrides for SGD and USD.

### 3.6. Security, Authentication & Data Protection
- **Email OTP Verification:** Cryptographically secure 6-digit OTP verification upon customer registration with brute-force lockout.
- **SSM Corporate Vetting:** Business registration formatting checks and duplicate phone/SSM alerts during wholesale sign-up.
- **Encrypted Database Settings:** All sensitive credentials (`mail_password`, `stripe_secret`, `stripe_test_secret`, `whatsapp_api_key`) are encrypted at rest using AES-256-CBC with HMAC-SHA256 signatures (`enc:...`).
- **Sanitized Environment:** `.env` file free of plaintext secrets.

---

## 4. Non-Functional Requirements (NFR)

1. **Performance:** Pingdom 100 score architecture with zero redirects on root `/` URL and cookie-free CDN asset delivery routes (`cdn_storage()`). Sub-1-second page loads on Singapore/Malaysia networks.
2. **Scalability:** Capable of handling 50,000+ monthly catalog visits, 500+ SKUs, and concurrent checkout sessions without session lock contention.
3. **Reliability & Availability:** 99.9% uptime target on high-speed regional hosting (Singapore/Malaysia datacenter).
4. **Data Integrity:** Strict foreign key relational integrity across orders, order items, quotations, and customer records with transaction rollback protections.
5. **Auditability:** Complete 1-click self-contained `.sql` database backup download tool in the back-office (`/admin/database`).

---

## 5. Acceptance Criteria & Final UAT Checklist

- [x] Retail prices are visible to guests; Wholesale and Trading prices are hidden on client DOM.
- [x] Walk-In QR route correctly displays physical in-stock items with express checkout.
- [x] Skudai (`81300`) and Kulai Indahpura (`81000`) correctly calculate Zone A delivery fees.
- [x] Orders ≥ RM150 receive free delivery; orders < RM150 receive RM10 fee.
- [x] Outstation orders do not charge unconfirmed shipping to Stripe.
- [x] Self-Collection hides street address fields and enforces date/time slots.
- [x] Trilingual language switching and currency conversion operate without layout breakage.
- [x] Database settings table securely encrypts secrets in Hash format (`enc:...`).
- [x] `.env` contains no plaintext secrets.
- [x] Complete illustrated Admin & Operations Manual is delivered.
