# Project Memory & Knowledge Base

**Project:** MST Seafood Unified B2B & B2C E-Commerce Platform  
**Target File Reference:** `memory.md`  
**Client Entity:** MST Import and Export Sdn. Bhd. (镁嘉国际贸易有限公司)  
**Lead Developer:** Abdul Rehman  
**Primary Client Contact:** Wendy (Client Project Lead)  
**Physical Facility:** 7, Jalan SILC 2/18, Kawasan Perindustrian SILC, 79200 Iskandar Puteri, Johor Bahru, Malaysia  
**Code Repository:** `https://github.com/Abdulrehman2015/seafood.git`  
**Last Updated:** October 2026  

---

## 1. Key Stakeholders & Project Context

- **Client Organization:** MST Import and Export Sdn. Bhd. specializes in frozen seafood cold-chain sourcing, wholesale supply, and international distribution across Malaysia and Singapore.
- **Client Project Lead (Wendy):** Focuses on operational clarity, exact local delivery coverage (Johor postal accuracy), operational manual completeness, transparent commercial scoping, and smooth UAT testing.
- **Lead Architect & Developer (Abdul Rehman):** Designed and built the unified Laravel platform, delivered numerous enterprise-grade value-added modules beyond baseline scope at no extra charge, and maintains continuous deployment.

---

## 2. Architectural Decisions & Key Rationale (ADR)

### ADR 1: Dual Matching Engine for Kulai Postcode 81000
- **Context:** Wendy clarified that only *Indahpura* is included in Zone A local delivery; the rest of the Kulai district is rural or outstation cold-chain.
- **Problem:** Pos Malaysia assigns postcode `81000` to both Bandar Indahpura and the wider Kulai district. Relying purely on numeric postcode matching causes false positives.
- **Decision:** Implemented a two-stage rule in `app/Models/DeliveryZone.php`:
  ```php
  if ($this->code === 'ZONE-A' && $cleanPostcode === '81000') {
      if (!str_contains($cleanCity, 'indahpura')) {
          return false; // Routes to Outstation Zone B
      }
  }
  ```
- **Outcome:** Orders for Indahpura qualify for Zone A; orders for rural Kulai are routed to Outstation cold-chain.

### ADR 2: Zero Pre-Billing on Outstation Delivery Charges
- **Context:** Outstation deliveries across West Malaysia require variable Styrofoam boxes (small, medium, jumbo) and dry ice depending on product volume and ambient temperature.
- **Decision:** Outstation orders display *“Outstation Transportation Fee — To Be Confirmed”* and charge **RM 0.00** shipping fee on Stripe. A direct WhatsApp button (`wa.me`) allows MST dispatchers to quote the exact cold-chain packaging and freight rate directly with the buyer prior to dispatch.
- **Outcome:** Eliminates customer dispute risks and credit card chargebacks from inaccurate automated freight estimates.

### ADR 3: Sensitive Configuration Encryption in Database Hash Format
- **Context:** The client requested that `.env` be secured and credentials be stored in the database in Hash format.
- **Problem:** If external credentials like Stripe Secret Keys or Gmail SMTP passwords were one-way hashed with `bcrypt`, the server could never authenticate with Stripe or Google.
- **Decision:** Implemented two-way authenticated OpenSSL AES-256-CBC encryption with HMAC-SHA256 signatures (`Crypt::encryptString`) in `app/Models/Setting.php`, prefixed with `enc:`.
  - Sensitive keys are automatically encrypted before writing to MySQL (`Setting::set()`).
  - Values are transparently decrypted when read into memory (`Setting::get()`).
  - `.env` plaintext values (`MAIL_PASSWORD`, `STRIPE_KEY`, `STRIPE_SECRET`) were completely removed.
- **Outcome:** In raw database dumps or phpMyAdmin, credentials appear as opaque hash/cipher strings (`enc:eyJpdiI6...`), while the application functions seamlessly.

### ADR 4: Decoupled Pingdom 100 Performance Optimization
- **Context:** E-commerce performance benchmarks in Southeast Asia require sub-1-second loads to avoid bounce rates.
- **Decision:**
  1. The root `/` route detects locale via cookies and renders `HomeController::index()` directly without an HTTP 302 redirect.
  2. Created `/cdn-assets/...` routes with `cdn_storage()` helper that strip cookies, set 1-year immutable cache headers, and gzip compress assets.
- **Outcome:** 100/100 performance scores on Pingdom and Google PageSpeed.

---

## 3. Critical Gotchas & Developer Tips

1. **PowerShell Statement Separators:**
   - On Windows PowerShell, chaining commands with `&&` causes a syntax error. Use `;` instead:
     ```powershell
     php -l app/Models/DeliveryZone.php; php -l app/Services/DeliveryService.php
     ```
2. **GitHub Push Protection (Secret Scanning):**
   - GitHub blocks commits containing strings matching patterns like `rk_test_...` or `sk_test_...` (even in test keys).
   - Never hardcode Stripe keys or passwords as fallback strings in PHP code or migration files. Always read dynamically via `env(...)` or `config(...)`.
3. **Database Migrations on Live Server:**
   - On shared hosting (InfinityFree / cPanel), SSH access may not be available.
   - Run migrations directly via the Admin Panel: Navigate to **Admin Panel > Settings > Database**, and click **"Run Migrations"** (`php artisan migrate --force`).
4. **Clearing Translation Cache:**
   - After updating translations in `admin/translations`, if strings do not refresh immediately, trigger the cache purge via `/admin/translations/cache/clear`.
5. **WhatsApp Number Formatting:**
   - Always store WhatsApp numbers without `+` or hyphens (e.g. `601112710260`) so `wa.me/601112710260` links generate properly across mobile and web WhatsApp.

---

## 4. Key File & Directory Map

| Path | Purpose |
| :--- | :--- |
| `app/Models/Setting.php` | Key-value settings model with automated cryptographic Hash/Cipher storage (`enc:...`). |
| `app/Models/DeliveryZone.php` | Delivery zone model containing 5-digit postcode matching and Indahpura validation. |
| `app/Services/DeliveryService.php` | Logistics service calculating Zone A fees, RM150 thresholds, and Outstation notices. |
| `app/Services/CurrencyService.php` | Multi-currency service synchronizing live FX rates for MYR, SGD, and USD. |
| `app/Services/TranslationService.php` | Trilingual translation service managing English, Chinese, and Malay strings. |
| `app/Http/Controllers/CheckoutController.php` | Handles checkout validation, Stripe hosted session creation, and cash orders. |
| `resources/views/checkout/index.blade.php` | Complete checkout blade with delivery vs self-collection toggles and address hiding. |
| `resources/views/admin/settings/index.blade.php` | Admin settings blade with General, Contact, SMTP, Payment, and Database tabs. |
| `database/migrations/` | Database schema migrations, including Zone A postcodes and encrypted credentials. |
| `app/Console/Commands/SecureCredentialsCommand.php` | Artisan command `php artisan settings:secure` to inspect and encrypt configuration. |
| `MST_Website_Admin_Panel_and_Operations_Manual.docx` | 1.98 MB comprehensive illustrated operations manual for Wendy and MST back-office staff. |
| `MST_Formal_Client_Response_and_Final_Handover_Report.docx` | 45 KB formal response letter, task status report, Zone A verification, 6 handover items & warranty. |

---

## 5. Latest Chat & Conversation Continuity Log (Current State: October 10, 2026)

### 5.1. Context & Client Review Summary (Wendy's Instructions)
In the latest communication, Wendy (MST Project Lead) reviewed the project status and provided explicit directions:
1. **RM650 Coupon Code & Marketing Source Tracking Module:**
   - Wendy instructed to keep this module **ON HOLD**.
   - **Confirmed:** Zero chargeable development was carried out; **RM0.00** billed; completely deferred to post-launch.
2. **Contract Scope Integrity:**
   - Wendy stressed that original project scope, quotation, and written confirmations must remain 100% complete without reduction.
   - **Confirmed:** Full scope preserved, verified, and delivered with zero reduction.
3. **Delivery Rules & UAT:**
   - Requested resolution of remaining UAT items, verification of Zone A postcodes and delivery fee rules, and system readiness for final acceptance.
4. **Operations Manual:**
   - Requested the complete illustrated Admin & Operations Manual as a downloadable Word/PDF attachment for MST permanent records.
5. **Agreed Handover Items:**
   - Requested provision of all 6 agreed handover items:
     1. Complete source code and Git repository access.
     2. Database and backup/export files.
     3. Administrator login credentials and admin panel access.
     4. Hosting/server access and deployment files.
     5. Admin & Operations Manual and technical documentation.
     6. Confirmation of agreed 30-day post-launch warranty and coverage.

### 5.2. Deliverables Produced & Versioned

#### Deliverable 1: Admin & Operations Manual (.docx)
- **File:** `MST_Website_Admin_Panel_and_Operations_Manual.docx` (1.98 MB)
- **Generator Script:** `generate_admin_operations_manual_docx.py`
- **Contents:** 10 chapters covering login, store profile, banners & policies, trilingual translation management & cache purge, product catalog & 4-tier pricing (Retail, Walk-In QR, Wholesale, Trading RFQ), delivery zones & postcodes, order dispatch workflow & invoice printing, wholesale vetting, 1-click database backup, and troubleshooting. Illustrated with high-res screenshots from `doc_assets/`.

#### Deliverable 2: Formal Client Response & Final Handover Report (.docx)
- **File:** `MST_Formal_Client_Response_and_Final_Handover_Report.docx` (45 KB)
- **Generator Script:** `generate_formal_client_response_and_handover_report_docx.py`
- **Contents:**
  - Executive response letter confirming Coupon Module is ON HOLD at RM0.00 and contract scope is 100% intact.
  - Task resolution table covering Stripe test key, encrypted database credentials (`enc:...`), walk-in QR ordering, wholesale vetting, and trilingual UI.
  - Comprehensive Zone A Postcode Verification Table (all 10 localities: Gelang Patah 81550, Iskandar Puteri 79xxx, Johor Bahru 80xxx, Kulai Indahpura only 81000, Masai 81750, Senai 81400, Skudai 81300/81310, Setia Eco Gardens 81550, Mount Austin 81100, ICQ corridor 79200/79250).
  - Delivery fee rules: Zone A Free $\ge$ RM150, RM10 < RM150; Zone B Outstation "To Be Confirmed" with WhatsApp coordination.
  - Dedicated sections for each of the 6 Handover Items (Git repo, DB backup, admin credentials, hosting & deployment, manual & markdown docs, 30-day post-launch warranty).
  - Consolidated Final Acceptance Checklist ready for Wendy's sign-off.

#### Git Status
- Both `.docx` files and generator scripts were committed and pushed to GitHub:
  - **Commit:** `997d5cdb`
  - **Branch:** `main`
  - **Remote:** `https://github.com/Abdulrehman2015/seafood.git`

### 5.3. Local Execution Environment State
Following the document delivery, the local development environment was initialized and verified:
1. **Database Service:**
   - Started MySQL 8.4 daemon (`C:\laragon\bin\mysql\mysql-8.4.3-winx64\bin\mysqld.exe`) on port `3306`.
   - Connected to database `oceanfresh`.
   - Ran all pending migrations (`php artisan migrate --force`):
     - `2026_10_05_000001_update_delivery_zones_for_coldchain_quotation`
     - `2026_10_06_000001_align_zone_a_to_jb_iskandar_puteri_nusajaya`
     - `2026_10_09_000001_update_zone_a_postcodes_for_client_consolidation`
     - `2026_10_09_000002_secure_env_credentials_to_encrypted_database_settings`
2. **Frontend Assets:**
   - Compiled with Vite via `npm run build` into `public/build/` (CSS: 37 kB, JS: 106 kB).
3. **Application Server:**
   - Booted via `php artisan serve --port=8001`.
   - *Note on Port 8001:* Port 8000 was in use by the user's Google Voice / Call Transcription studio app, so port 8001 was selected to avoid conflict.
4. **Verification:**
   - Verified via HTTP curl and browser subagent: `http://127.0.0.1:8001` responds with HTTP 200 OK.
   - Tested `/login` and `/admin` routes (redirects properly to login with HTTP 200).
   - Superadmin account: `admin@mst.my`.

### 5.4. Resuming Work (Next Steps Checklist)
When returning to this project, follow these instructions to continue immediately:
1. **Check Local Servers:**
   - Ensure MySQL is running on port 3306 (`Get-NetTCPConnection -LocalPort 3306`). If not:
     ```powershell
     & "C:\laragon\bin\mysql\mysql-8.4.3-winx64\bin\mysqld.exe" --defaults-file="C:\laragon\bin\mysql\mysql-8.4.3-winx64\my.ini" --console
     ```
   - Ensure Laravel is serving on port 8001 (`Get-NetTCPConnection -LocalPort 8001`). If not:
     ```powershell
     php artisan serve --port=8001
     ```
2. **Client Interaction:**
   - Wendy has all deliverables. Await her final checklist review or proceed to live deployment / hosting configuration upon her feedback.
3. **Live Deployment Reference:**
   - If deploying to live hosting, refer to Handover Item 4 in `MST_Formal_Client_Response_and_Final_Handover_Report.docx` and the automated GitHub Actions workflow in `.github/workflows/deploy.yml`.
