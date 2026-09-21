<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="2.0"
    xmlns:html="http://www.w3.org/TR/REC-html40"
    xmlns:sitemap="http://www.sitemaps.org/schemas/sitemap/0.9"
    xmlns:xhtml="http://www.w3.org/1999/xhtml"
    xmlns:image="http://www.google.com/schemas/sitemap-image/1.1"
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
  <xsl:output method="html" version="1.0" encoding="UTF-8" indent="yes"/>
  <xsl:template match="/">
    <html xmlns="http://www.w3.org/1999/xhtml" lang="en">
      <head>
        <title>Google-Compliant XML Sitemap — MST Import &amp; Export Sdn Bhd</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link rel="icon" type="image/webp" href="/images/favicon.webp" />
        <link rel="shortcut icon" href="/images/favicon.webp" />
        <style type="text/css">
          * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
          }
          body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background: #f8fafc;
            color: #1e293b;
            font-size: 14px;
            line-height: 1.5;
            padding: 0 0 60px 0;
          }
          .sitemap-header {
            background: linear-gradient(135deg, #06152b 0%, #0c2146 45%, #1d4ed8 100%);
            color: #ffffff;
            padding: 40px 24px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 4px 20px rgba(6, 21, 43, 0.15);
          }
          .header-container {
            max-width: 1200px;
            margin: 0 auto;
          }
          .badge-row {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 12px;
            flex-wrap: wrap;
          }
          .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(16, 185, 129, 0.2);
            border: 1px solid rgba(16, 185, 129, 0.4);
            color: #6ee7b7;
            padding: 4px 12px;
            border-radius: 9999px;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.03em;
            text-transform: uppercase;
          }
          .google-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(59, 130, 246, 0.25);
            border: 1px solid rgba(147, 197, 253, 0.4);
            color: #bfdbfe;
            padding: 4px 12px;
            border-radius: 9999px;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.03em;
          }
          .pulse-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #10b981;
            box-shadow: 0 0 8px #10b981;
          }
          .sitemap-title {
            font-size: 28px;
            font-weight: 800;
            letter-spacing: -0.02em;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 12px;
          }
          .sitemap-desc {
            font-size: 14px;
            color: #cbd5e1;
            max-width: 800px;
            line-height: 1.6;
          }
          .header-links {
            margin-top: 18px;
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
          }
          .btn-header {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.25);
            color: #ffffff;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.15s ease;
          }
          .btn-header:hover {
            background: rgba(255, 255, 255, 0.25);
            transform: translateY(-1px);
          }
          .content-wrap {
            max-width: 1200px;
            margin: -24px auto 0 auto;
            padding: 0 20px;
          }
          .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
            margin-bottom: 24px;
          }
          .stat-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 18px 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
            display: flex;
            align-items: center;
            justify-content: space-between;
          }
          .stat-label {
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #64748b;
            margin-bottom: 4px;
          }
          .stat-value {
            font-size: 26px;
            font-weight: 800;
            color: #0f172a;
          }
          .stat-icon {
            font-size: 24px;
            opacity: 0.85;
          }
          .table-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.05);
            overflow: hidden;
          }
          .table-toolbar {
            padding: 18px 22px;
            background: #ffffff;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
          }
          .search-input {
            width: 100%;
            max-width: 380px;
            padding: 10px 14px 10px 38px;
            background: #f8fafc url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="%2394a3b8" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>') no-repeat 12px center;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            font-size: 13px;
            outline: none;
            transition: all 0.15s ease;
          }
          .search-input:focus {
            background-color: #ffffff;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
          }
          .counter-text {
            font-size: 13px;
            color: #64748b;
            font-weight: 500;
          }
          .counter-text strong {
            color: #0f172a;
          }
          table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
          }
          thead th {
            background: #f8fafc;
            padding: 12px 18px;
            font-size: 11px;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            border-bottom: 1px solid #e2e8f0;
          }
          tbody tr {
            border-bottom: 1px solid #f1f5f9;
            transition: background 0.1s ease;
          }
          tbody tr:hover {
            background: #f8fafc;
          }
          tbody td {
            padding: 12px 18px;
            vertical-align: middle;
            font-size: 13px;
          }
          .col-index {
            width: 50px;
            color: #94a3b8;
            font-weight: 600;
            font-size: 12px;
          }
          .col-url a {
            color: #1d4ed8;
            text-decoration: none;
            font-weight: 600;
            word-break: break-all;
            display: inline-flex;
            align-items: center;
            gap: 6px;
          }
          .col-url a:hover {
            color: #2563eb;
            text-decoration: underline;
          }
          .col-url a::after {
            content: "↗";
            font-size: 11px;
            opacity: 0.6;
          }
          .img-preview {
            width: 38px;
            height: 38px;
            border-radius: 6px;
            object-fit: cover;
            border: 1px solid #e2e8f0;
            display: inline-block;
            vertical-align: middle;
          }
          .img-placeholder {
            width: 38px;
            height: 38px;
            border-radius: 6px;
            background: #f1f5f9;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #94a3b8;
            font-size: 11px;
            font-weight: 700;
          }
          .priority-pill {
            display: inline-block;
            padding: 3px 9px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 700;
          }
          .priority-high {
            background: #dcfce7;
            color: #15803d;
          }
          .priority-medium {
            background: #e0f2fe;
            color: #0369a1;
          }
          .priority-normal {
            background: #f1f5f9;
            color: #475569;
          }
          .changefreq-tag {
            display: inline-block;
            padding: 3px 8px;
            background: #f1f5f9;
            color: #475569;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 600;
            text-transform: capitalize;
          }
          .date-cell {
            color: #64748b;
            font-size: 12px;
            white-space: nowrap;
          }
          .footer-note {
            margin-top: 24px;
            text-align: center;
            font-size: 12px;
            color: #94a3b8;
          }
          @media (max-width: 768px) {
            .sitemap-header {
              padding: 24px 16px;
            }
            .sitemap-title {
              font-size: 22px;
            }
            tbody td, thead th {
              padding: 10px 12px;
            }
            .hide-mobile {
              display: none;
            }
          }
        </style>
        <script type="text/javascript">
          document.addEventListener('DOMContentLoaded', function() {
            var searchInput = document.getElementById('sitemapSearch');
            var tableBody = document.getElementById('sitemapTableBody');
            var rows = tableBody ? tableBody.getElementsByTagName('tr') : [];
            var countDisplay = document.getElementById('visibleCount');

            if (searchInput &amp;&amp; rows.length) {
              searchInput.addEventListener('input', function() {
                var query = this.value.toLowerCase().trim();
                var visible = 0;
                for (var i = 0; i &lt; rows.length; i++) {
                  var urlCell = rows[i].querySelector('.col-url');
                  var text = urlCell ? urlCell.textContent.toLowerCase() : '';
                  if (!query || text.indexOf(query) !== -1) {
                    rows[i].style.display = '';
                    visible++;
                  } else {
                    rows[i].style.display = 'none';
                  }
                }
                if (countDisplay) {
                  countDisplay.textContent = visible;
                }
              });
            }
          });
        </script>
      </head>
      <body>
        <div class="sitemap-header">
          <div class="header-container">
            <div class="badge-row">
              <span class="status-pill">
                <span class="pulse-dot"></span> Google-Compliant XML Sitemap
              </span>
              <span class="google-pill">
                Sitemaps 0.9 + Image Sitemap 1.1 + Hreflang
              </span>
            </div>
            <h1 class="sitemap-title">
              <span>🗺️</span> XML Sitemap Index
            </h1>
            <p class="sitemap-desc">
              Structured XML index built strictly according to <strong>Google Search Central SEO Guidelines</strong>. Includes 100% canonical URLs (zero 404s, zero duplicate parameter clutter), multilingual <code>xhtml:link</code> alternates with <code>x-default</code>, and Google Image Sitemap (<code>image:image</code>) tags for rich visual indexing on Google Images and Shopping.
            </p>
            <div class="header-links">
              <a href="/" class="btn-header">🌐 Storefront Home</a>
              <a href="/admin/page-seo" class="btn-header">⚙️ Page SEO Admin</a>
              <a href="/admin/sitemap" class="btn-header">🛠️ Sitemap Management</a>
            </div>
          </div>
        </div>

        <div class="content-wrap">
          <div class="stats-grid">
            <div class="stat-card">
              <div>
                <div class="stat-label">Total Canonical URLs</div>
                <div class="stat-value"><xsl:value-of select="count(sitemap:urlset/sitemap:url)"/></div>
              </div>
              <div class="stat-icon">📄</div>
            </div>
            <div class="stat-card">
              <div>
                <div class="stat-label">Google Indexed Images</div>
                <div class="stat-value"><xsl:value-of select="count(sitemap:urlset/sitemap:url/image:image)"/></div>
              </div>
              <div class="stat-icon" style="color:#d97706">🖼️</div>
            </div>
            <div class="stat-card">
              <div>
                <div class="stat-label">Product URLs</div>
                <div class="stat-value"><xsl:value-of select="count(sitemap:urlset/sitemap:url[contains(sitemap:loc, '/shop/')])"/></div>
              </div>
              <div class="stat-icon" style="color:#2563eb">🦐</div>
            </div>
            <div class="stat-card">
              <div>
                <div class="stat-label">Core Canonical Pages</div>
                <div class="stat-value"><xsl:value-of select="count(sitemap:urlset/sitemap:url[not(contains(sitemap:loc, '/shop/'))])"/></div>
              </div>
              <div class="stat-icon" style="color:#16a34a">🏛️</div>
            </div>
          </div>

          <div class="table-card">
            <div class="table-toolbar">
              <input type="text" id="sitemapSearch" class="search-input" placeholder="Quick search canonical URLs..." />
              <div class="counter-text">
                Showing <strong id="visibleCount"><xsl:value-of select="count(sitemap:urlset/sitemap:url)"/></strong> of <strong><xsl:value-of select="count(sitemap:urlset/sitemap:url)"/></strong> Canonical URLs
              </div>
            </div>

            <table>
              <thead>
                <tr>
                  <th class="col-index">#</th>
                  <th style="width:60px">Image</th>
                  <th>Canonical URL Location</th>
                  <th>Priority</th>
                  <th class="hide-mobile">Frequency</th>
                  <th>Last Modified</th>
                </tr>
              </thead>
              <tbody id="sitemapTableBody">
                <xsl:for-each select="sitemap:urlset/sitemap:url">
                  <tr>
                    <td class="col-index"><xsl:value-of select="position()"/></td>
                    <td>
                      <xsl:choose>
                        <xsl:when test="image:image/image:loc">
                          <a target="_blank">
                            <xsl:attribute name="href">
                              <xsl:value-of select="image:image/image:loc"/>
                            </xsl:attribute>
                            <img class="img-preview" alt="Product Image">
                              <xsl:attribute name="src">
                                <xsl:value-of select="image:image/image:loc"/>
                              </xsl:attribute>
                            </img>
                          </a>
                        </xsl:when>
                        <xsl:otherwise>
                          <span class="img-placeholder">N/A</span>
                        </xsl:otherwise>
                      </xsl:choose>
                    </td>
                    <td class="col-url">
                      <a>
                        <xsl:attribute name="href">
                          <xsl:value-of select="sitemap:loc"/>
                        </xsl:attribute>
                        <xsl:attribute name="target">_blank</xsl:attribute>
                        <xsl:value-of select="sitemap:loc"/>
                      </a>
                    </td>
                    <td>
                      <xsl:choose>
                        <xsl:when test="number(sitemap:priority) &gt;= 0.9">
                          <span class="priority-pill priority-high"><xsl:value-of select="sitemap:priority"/></span>
                        </xsl:when>
                        <xsl:when test="number(sitemap:priority) &gt;= 0.7">
                          <span class="priority-pill priority-medium"><xsl:value-of select="sitemap:priority"/></span>
                        </xsl:when>
                        <xsl:otherwise>
                          <span class="priority-pill priority-normal"><xsl:value-of select="sitemap:priority"/></span>
                        </xsl:otherwise>
                      </xsl:choose>
                    </td>
                    <td class="hide-mobile">
                      <span class="changefreq-tag"><xsl:value-of select="sitemap:changefreq"/></span>
                    </td>
                    <td class="date-cell">
                      <xsl:value-of select="substring(sitemap:lastmod, 1, 10)"/>
                      <span style="color:#94a3b8;margin-left:4px"><xsl:value-of select="substring(sitemap:lastmod, 12, 5)"/></span>
                    </td>
                  </tr>
                </xsl:for-each>
              </tbody>
            </table>
          </div>

          <div class="footer-note">
            MST Import &amp; Export Sdn Bhd — Built strictly to Google Search Central specifications (Sitemaps Protocol 0.9 + Image Sitemap 1.1).
          </div>
        </div>
      </body>
    </html>
  </xsl:template>
</xsl:stylesheet>
