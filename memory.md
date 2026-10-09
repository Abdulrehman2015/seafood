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
