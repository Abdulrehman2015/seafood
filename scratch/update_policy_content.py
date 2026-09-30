import sqlite3
import mysql.connector

# Connect to MySQL
mysql_conn = mysql.connector.connect(
    host="127.0.0.1",
    user="root",
    password="",
    database="oceanfresh"
)
mysql_cur = mysql_conn.cursor(dictionary=True)

# Connect to SQLite
sqlite_conn = sqlite3.connect("database/database.sqlite")
sqlite_conn.row_factory = sqlite3.Row
sqlite_cur = sqlite_conn.cursor()

# Get existing shipping policy from MySQL
mysql_cur.execute("SELECT * FROM policies WHERE slug = 'shipping-policy'")
sp = mysql_cur.fetchone()

# Construct updated Shipping Policy EN
sp_en = """<div class="policy-header-badge mb-3">
    <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2 rounded-pill fw-semibold">
        <i class="bi bi-shield-check me-1"></i> Effective Date: September 25, 2026 | Last Updated: September 25, 2026
    </span>
</div>

<div class="lead text-dark mb-4 fw-normal">
    <p><strong>MST Import and Export Sdn. Bhd.</strong> supplies frozen seafood, frozen meat, frozen food, ingredients and other temperature-sensitive products.</p>
</div>

<hr class="my-4 text-muted opacity-25">

<div class="policy-section mb-4">
    <h3 class="h5 fw-bold text-dark mb-3"><i class="bi bi-truck text-primary me-2"></i>1. Standard Local Delivery Coverage</h3>
    <p>MST provides door-to-door delivery with transparent tier and zone-based delivery arrangements:</p>
    
    <div class="row g-3 my-2">
        <div class="col-md-6">
            <div class="card h-100 border-0 bg-light p-3 shadow-sm">
                <div class="d-flex align-items-center mb-2">
                    <span class="badge bg-primary me-2 px-2 py-1">B2B / Wholesale</span>
                    <strong class="text-dark">Commercial Orders</strong>
                </div>
                <p class="fs-5 fw-bold text-primary mb-0">Standard Delivery Threshold: RM350</p>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card h-100 border-0 bg-light p-3 shadow-sm">
                <div class="d-flex align-items-center mb-2">
                    <span class="badge bg-success me-2 px-2 py-1">B2C / Retail</span>
                    <strong class="text-dark">Consumer Orders</strong>
                </div>
                <p class="fs-5 fw-bold text-success mb-0">Standard Delivery Threshold: RM100</p>
            </div>
        </div>
    </div>
    
    <p class="text-muted small mt-2">
        <i class="bi bi-geo-alt me-1 text-primary"></i> <strong>Standard Local Delivery Coverage:</strong> <code>Johor Bahru and Iskandar Puteri / Nusajaya</code>.
    </p>
</div>

<div class="policy-section mb-4">
    <h3 class="h5 fw-bold text-dark mb-3"><i class="bi bi-cart-check text-primary me-2"></i>2. Orders Below Standard Delivery Threshold</h3>
    <p>B2C / Retail customers can complete checkout at any order amount. For orders below RM100, an additional delivery or transport surcharge may apply based on your delivery zone/postcode.</p>
    <p>However, transportation / delivery charges will apply.</p>
    <p class="text-muted small">The applicable delivery charge will be communicated before the order is confirmed.</p>
</div>

<div class="policy-section mb-4">
    <h3 class="h5 fw-bold text-dark mb-3"><i class="bi bi-pin-map text-primary me-2"></i>3. Outside Standard Local Area</h3>
    <p>Delivery outside the standard local area (Johor Bahru and Iskandar Puteri / Nusajaya) is not automatically available.</p>
    <p>Delivery outside the standard local area may be:</p>
    <ul class="list-unstyled ps-3">
        <li class="mb-2"><i class="bi bi-check2 text-primary me-2"></i>Considered on a case-by-case basis</li>
        <li class="mb-2"><i class="bi bi-check2 text-primary me-2"></i>Subject to destination</li>
        <li class="mb-2"><i class="bi bi-check2 text-primary me-2"></i>Subject to product requirements</li>
        <li class="mb-2"><i class="bi bi-check2 text-primary me-2"></i>Subject to logistics requirements</li>
        <li class="mb-2"><i class="bi bi-check2 text-primary me-2"></i>Subject to applicable transportation charges</li>
    </ul>
    <p>Applicable transportation/logistics charges may be payable by the customer unless otherwise agreed with MST.</p>
</div>

<div class="policy-section mb-4">
    <h3 class="h5 fw-bold text-dark mb-3"><i class="bi bi-receipt text-primary me-2"></i>4. Delivery / Transportation Charges</h3>
    <p>Delivery or transportation charges may depend on factors including:</p>
    <ul class="list-unstyled ps-3">
        <li class="mb-2"><i class="bi bi-dot text-primary fs-5 me-1"></i>Delivery location / zone</li>
        <li class="mb-2"><i class="bi bi-dot text-primary fs-5 me-1"></i>Distance</li>
        <li class="mb-2"><i class="bi bi-dot text-primary fs-5 me-1"></i>Order quantity / weight</li>
        <li class="mb-2"><i class="bi bi-dot text-primary fs-5 me-1"></i>Vehicle requirements</li>
        <li class="mb-2"><i class="bi bi-dot text-primary fs-5 me-1"></i>Logistics requirements</li>
        <li class="mb-2"><i class="bi bi-dot text-primary fs-5 me-1"></i>Cold-chain requirements</li>
        <li class="mb-2"><i class="bi bi-dot text-primary fs-5 me-1"></i>Delivery schedule</li>
        <li class="mb-2"><i class="bi bi-dot text-primary fs-5 me-1"></i>Other applicable logistics requirements</li>
    </ul>
    <p class="text-muted small">Where a delivery charge applies, it will be communicated to the customer before final order confirmation according to actual system capability.</p>
</div>

<div class="policy-section mb-4">
    <h3 class="h5 fw-bold text-dark mb-3"><i class="bi bi-calendar-check text-primary me-2"></i>5. Delivery Schedule</h3>
    <p>Delivery times are estimates unless specifically confirmed in writing.</p>
    <p class="text-muted small">Delivery may be affected by traffic, weather, public holidays, vehicle availability, product availability, customer availability or circumstances beyond MST's reasonable control.</p>
</div>

<div class="policy-section mb-4">
    <h3 class="h5 fw-bold text-dark mb-3"><i class="bi bi-signpost-2 text-primary me-2"></i>6. Delivery Address</h3>
    <p>Customers must provide accurate:</p>
    <ul class="list-unstyled ps-3">
        <li class="mb-2"><i class="bi bi-check2 text-primary me-2"></i>Delivery address</li>
        <li class="mb-2"><i class="bi bi-check2 text-primary me-2"></i>Contact person</li>
        <li class="mb-2"><i class="bi bi-check2 text-primary me-2"></i>Contact number</li>
        <li class="mb-2"><i class="bi bi-check2 text-primary me-2"></i>Receiving instructions</li>
        <li class="mb-2"><i class="bi bi-check2 text-primary me-2"></i>Operating or receiving hours where applicable</li>
    </ul>
    <p class="text-muted small">To the extent permitted by applicable law, MST is not liable for delays or additional charges caused by inaccurate or incomplete information provided by the customer.</p>
</div>

<div class="policy-section mb-4">
    <h3 class="h5 fw-bold text-dark mb-3"><i class="bi bi-box-seam text-primary me-2"></i>7. Receiving Frozen Products</h3>
    <p>Customers should ensure that an authorised person is available to receive the order.</p>
    <p>Customers should inspect the products and packaging promptly upon delivery.</p>
    <p>Any product-quality complaint must be reported to MST within <strong>12 hours</strong> of receiving the goods.</p>
    <div class="alert alert-info py-2 px-3 small border-0 rounded-3 my-2">
        <i class="bi bi-info-circle me-1"></i> Please refer to our <a href="/en/policy/refund-policy" class="alert-link fw-semibold">Refund &amp; Return Policy</a>.
    </div>
</div>

<div class="policy-section mb-4">
    <h3 class="h5 fw-bold text-dark mb-3"><i class="bi bi-snow2 text-primary me-2"></i>8. Frozen Product Storage</h3>
    <p>Customers are responsible for transferring frozen products to appropriate frozen storage promptly after receiving the delivery.</p>
    <p class="text-muted small">To the extent permitted by applicable law, MST is not liable for deterioration occurring after delivery due to improper customer storage, thawing, handling, or other circumstances attributable to the customer. Nothing in this policy limits or excludes statutory rights that cannot legally be excluded under applicable law.</p>
</div>

<div class="policy-section mb-4">
    <h3 class="h5 fw-bold text-dark mb-3"><i class="bi bi-exclamation-triangle text-primary me-2"></i>9. Failed Delivery</h3>
    <p>If delivery cannot be completed because:</p>
    <ul class="list-unstyled ps-3">
        <li class="mb-2"><i class="bi bi-x-circle text-danger me-2"></i>No authorised recipient is available</li>
        <li class="mb-2"><i class="bi bi-x-circle text-danger me-2"></i>Incorrect address was provided</li>
        <li class="mb-2"><i class="bi bi-x-circle text-danger me-2"></i>Customer is unavailable</li>
        <li class="mb-2"><i class="bi bi-x-circle text-danger me-2"></i>Receiving arrangements were not followed</li>
        <li class="mb-2"><i class="bi bi-x-circle text-danger me-2"></i>Customer requests a change after dispatch</li>
    </ul>
    <p class="text-muted small">Additional transportation, waiting, redelivery or handling charges may apply.</p>
</div>

<div class="policy-section mb-4">
    <h3 class="h5 fw-bold text-dark mb-3"><i class="bi bi-shop text-primary me-2"></i>10. Self-Collection (Walk-in)</h3>
    <div class="mb-2"><span class="badge bg-secondary-subtle text-dark border px-3 py-1 rounded-pill fw-semibold">Self-collection only · No delivery</span></div>
    <p>Customers who select Self-Collection (Walk-in Self-Collection) collect their confirmed orders directly from our premises. There is no delivery charge because the customer is collecting the order themselves (this is self-collection, not a free delivery service).</p>
    <p>Orders are subject to product availability and payment confirmation. Payment confirmation must occur before the order proceeds to preparation and before the order can be released for collection.</p>
    <p>Customers should check their order at the time of collection where reasonably practicable and contact MST promptly if there is an apparent issue with the order.</p>
    <p class="text-muted small">Detailed collection instructions (including physical collection at Counter 2) will be provided upon order confirmation and pickup notification. Once collected, customers are responsible for transferring frozen products to appropriate frozen storage promptly.</p>
</div>

<div class="policy-section mb-4">
    <h3 class="h5 fw-bold text-dark mb-3"><i class="bi bi-building text-primary me-2"></i>11. Wholesale and Bulk Deliveries</h3>
    <p>Large or wholesale orders may require scheduled delivery arrangements.</p>
    <p class="text-muted small">Specific delivery arrangements may be stated in the quotation, sales confirmation or other commercial agreement.</p>
</div>

<div class="policy-section mb-4">
    <h3 class="h5 fw-bold text-dark mb-3"><i class="bi bi-globe2 text-primary me-2"></i>12. International / Cross-Border Orders</h3>
    <p>MST's current website market positioning remains: <strong>Malaysia and Singapore</strong>.</p>
    <p>International and cross-border orders are: <strong>Available / considered on a case-by-case basis, subject to product, destination, logistics, regulatory and commercial requirements</strong>.</p>
    <p class="text-muted small">MST does not operate a universal worldwide or automatic international delivery network. Regional and international supply remains a future or case-by-case development direction.</p>
    <p>Where international or cross-border orders are agreed, customers may be responsible for applicable:</p>
    <ul class="list-unstyled ps-3">
        <li class="mb-2"><i class="bi bi-dot text-primary fs-5 me-1"></i>Freight and transport charges</li>
        <li class="mb-2"><i class="bi bi-dot text-primary fs-5 me-1"></i>Customs clearance and documentation</li>
        <li class="mb-2"><i class="bi bi-dot text-primary fs-5 me-1"></i>Import permits and regulatory compliance</li>
        <li class="mb-2"><i class="bi bi-dot text-primary fs-5 me-1"></i>Duties, taxes, and destination fees</li>
        <li class="mb-2"><i class="bi bi-dot text-primary fs-5 me-1"></i>Handling and cold-chain logistics charges</li>
    </ul>
    <p class="text-muted small">unless otherwise agreed in writing.</p>
</div>

<div class="policy-section mb-4">
    <h3 class="h5 fw-bold text-dark mb-3"><i class="bi bi-clipboard-check text-primary me-2"></i>13. Proof of Delivery</h3>
    <p>Delivery may be confirmed through:</p>
    <ul class="list-unstyled ps-3">
        <li class="mb-2"><i class="bi bi-check2 text-primary me-2"></i>Delivery order (DO)</li>
        <li class="mb-2"><i class="bi bi-check2 text-primary me-2"></i>Invoice</li>
        <li class="mb-2"><i class="bi bi-check2 text-primary me-2"></i>Proof of delivery</li>
        <li class="mb-2"><i class="bi bi-check2 text-primary me-2"></i>Customer signature</li>
        <li class="mb-2"><i class="bi bi-check2 text-primary me-2"></i>Digital confirmation</li>
        <li class="mb-2"><i class="bi bi-check2 text-primary me-2"></i>Other reasonable delivery records</li>
    </ul>
</div>

<div class="policy-section mb-4">
    <h3 class="h5 fw-bold text-dark mb-3"><i class="bi bi-headset text-primary me-2"></i>14. Contact</h3>
    <div class="card bg-light border-0 shadow-sm p-4 rounded-3">
        <p class="fw-bold fs-6 text-dark mb-2">MST Import and Export Sdn. Bhd.</p>
        <p class="mb-2 text-muted">
            <i class="bi bi-geo-alt-fill text-primary me-2"></i>7 Jalan SILC 2/18, Kawasan Perindustrian SILC, 79200 Iskandar Puteri, Johor, Malaysia
            <br>
            <a href="https://maps.google.com/?q=7+Jalan+SILC+2/18+Kawasan+Perindustrian+SILC+79200+Iskandar+Puteri+Johor+Malaysia" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-outline-primary mt-2">
                <i class="bi bi-map me-1"></i> Get Directions (Google Maps)
            </a>
        </p>
        <hr class="my-2 text-muted opacity-25">
        <p class="mb-2">
            <i class="bi bi-envelope-fill text-primary me-2"></i><strong>Email:</strong> 
            <a href="mailto:mikatrading15@gmail.com" class="text-decoration-none fw-semibold">mikatrading15@gmail.com</a>
        </p>
        <p class="mb-2">
            <i class="bi bi-telephone-fill text-primary me-2"></i><strong>Phone:</strong> 
            <a href="tel:+60132800168" class="text-decoration-none fw-semibold">+60 13-280 0168</a>
        </p>
        <p class="mb-0">
            <i class="bi bi-whatsapp text-success me-2"></i><strong>WhatsApp:</strong> 
            <a href="https://wa.me/601112710260" target="_blank" rel="noopener noreferrer" class="text-decoration-none fw-semibold me-2">+60 11-1271 0260</a>
            <a href="https://wa.me/601112710260" target="_blank" rel="noopener noreferrer" class="badge bg-success text-white text-decoration-none">
                <i class="bi bi-whatsapp me-1"></i>Chat on WhatsApp
            </a>
        </p>
    </div>
</div>"""

# Construct updated Shipping Policy ZH
sp_zh = """<div class="policy-header-badge mb-3">
    <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2 rounded-pill fw-semibold">
        <i class="bi bi-shield-check me-1"></i> 生效日期：2026年9月25日 | 最近更新：2026年9月25日
    </span>
</div>

<div class="lead text-dark mb-4 fw-normal">
    <p><strong>镁嘉国际贸易有限公司（MST Import and Export Sdn. Bhd.）</strong>供应冷冻海鲜、冷冻肉类、冷冻食品、调理配料及其它温控冷链产品。</p>
</div>

<hr class="my-4 text-muted opacity-25">

<div class="policy-section mb-4">
    <h3 class="h5 fw-bold text-dark mb-3"><i class="bi bi-truck text-primary me-2"></i>1. 标准本地配送范围</h3>
    <p>MST 提供全程冷链门到门配送，并实行清晰透明的分层参考门槛与区域配送机制：</p>
    
    <div class="row g-3 my-2">
        <div class="col-md-6">
            <div class="card h-100 border-0 bg-light p-3 shadow-sm">
                <div class="d-flex align-items-center mb-2">
                    <span class="badge bg-primary me-2 px-2 py-1">商业批发 / B2B</span>
                    <strong class="text-dark">企业与商用订单</strong>
                </div>
                <p class="fs-5 fw-bold text-primary mb-0">标准配送参考门槛：RM350</p>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card h-100 border-0 bg-light p-3 shadow-sm">
                <div class="d-flex align-items-center mb-2">
                    <span class="badge bg-success me-2 px-2 py-1">个人零售 / B2C</span>
                    <strong class="text-dark">零售消费订单</strong>
                </div>
                <p class="fs-5 fw-bold text-success mb-0">标准配送参考门槛：RM100</p>
            </div>
        </div>
    </div>
    
    <p class="text-muted small mt-2">
        <i class="bi bi-geo-alt me-1 text-primary"></i> <strong>标准本地配送范围：</strong> <code>新山及依斯干达公主城 / 努沙再也（Johor Bahru and Iskandar Puteri / Nusajaya）</code>。
    </p>
</div>

<div class="policy-section mb-4">
    <h3 class="h5 fw-bold text-dark mb-3"><i class="bi bi-cart-check text-primary me-2"></i>2. 未达标准配送门槛订单</h3>
    <p>零售客户仍可提交低于适用参考门槛的订单。</p>
    <p>但须额外支付相应的运输或配送运费。</p>
    <p class="text-muted small">适用的运费将在订单确认前根据系统实际能力与客户沟通说明。</p>
</div>

<div class="policy-section mb-4">
    <h3 class="h5 fw-bold text-dark mb-3"><i class="bi bi-pin-map text-primary me-2"></i>3. 标准本地范围以外区域</h3>
    <p>标准本地范围（新山及依斯干达公主城 / 努沙再也）以外的配送不视为自动提供。</p>
    <p>标准本地范围以外的配送可能按以下原则处理：</p>
    <ul class="list-unstyled ps-3">
        <li class="mb-2"><i class="bi bi-check2 text-primary me-2"></i>按个案评估</li>
        <li class="mb-2"><i class="bi bi-check2 text-primary me-2"></i>取决于目的地</li>
        <li class="mb-2"><i class="bi bi-check2 text-primary me-2"></i>取决于产品要求</li>
        <li class="mb-2"><i class="bi bi-check2 text-primary me-2"></i>取决于物流要求</li>
        <li class="mb-2"><i class="bi bi-check2 text-primary me-2"></i>取决于适用的运输费用</li>
    </ul>
    <p>除与 MST 另有约定外，客户可能须承担适用的运输/物流费用。</p>
</div>

<div class="policy-section mb-4">
    <h3 class="h5 fw-bold text-dark mb-3"><i class="bi bi-receipt text-primary me-2"></i>4. 配送 / 运输费用计算</h3>
    <p>配送或运输费用可能会根据以下因素确定：</p>
    <ul class="list-unstyled ps-3">
        <li class="mb-2"><i class="bi bi-dot text-primary fs-5 me-1"></i>配送地点 / 区域</li>
        <li class="mb-2"><i class="bi bi-dot text-primary fs-5 me-1"></i>运输距离</li>
        <li class="mb-2"><i class="bi bi-dot text-primary fs-5 me-1"></i>订单数量 / 重量</li>
        <li class="mb-2"><i class="bi bi-dot text-primary fs-5 me-1"></i>车辆要求</li>
        <li class="mb-2"><i class="bi bi-dot text-primary fs-5 me-1"></i>物流要求</li>
        <li class="mb-2"><i class="bi bi-dot text-primary fs-5 me-1"></i>冷链要求</li>
        <li class="mb-2"><i class="bi bi-dot text-primary fs-5 me-1"></i>配送时间表</li>
        <li class="mb-2"><i class="bi bi-dot text-primary fs-5 me-1"></i>其他适用的物流要求</li>
    </ul>
    <p class="text-muted small">如适用配送费用，将在最终确认订单前根据系统实际能力向客户说明。</p>
</div>

<div class="policy-section mb-4">
    <h3 class="h5 fw-bold text-dark mb-3"><i class="bi bi-calendar-check text-primary me-2"></i>5. 配送时间安排</h3>
    <p>除另有书面明确确认外，所有配送时间均为预估时间。</p>
    <p class="text-muted small">实际配送可能会受到交通拥堵、天气状况、公共假期、车辆调度、产品备货、客户签收安排或超出 MST 合理控制范围的情况影响。</p>
</div>

<div class="policy-section mb-4">
    <h3 class="h5 fw-bold text-dark mb-3"><i class="bi bi-signpost-2 text-primary me-2"></i>6. 配送地址与收货信息</h3>
    <p>客户在提交订单时，须提供准确详尽的收货信息：</p>
    <ul class="list-unstyled ps-3">
        <li class="mb-2"><i class="bi bi-check2 text-primary me-2"></i>完整准确的送货地址与邮编</li>
        <li class="mb-2"><i class="bi bi-check2 text-primary me-2"></i>现场收货联系人姓名</li>
        <li class="mb-2"><i class="bi bi-check2 text-primary me-2"></i>有效联系电话</li>
        <li class="mb-2"><i class="bi bi-check2 text-primary me-2"></i>特定卸货或入库指引</li>
        <li class="mb-2"><i class="bi bi-check2 text-primary me-2"></i>商户营业时间或收货开放时间段</li>
    </ul>
    <p class="text-muted small">在适用法律允许的最大范围内，因客户提供信息不准确或不完整而导致的任何配送延误、二次派送或衍生费用，MST 概不承担相关赔偿责任。</p>
</div>

<div class="policy-section mb-4">
    <h3 class="h5 fw-bold text-dark mb-3"><i class="bi bi-box-seam text-primary me-2"></i>7. 冷冻产品验收规范</h3>
    <p>客户须安排授权人员在送达时现场接收货物并进行当面查验。</p>
    <p>客户应及时检查外包装完整性及产品状态。</p>
    <p>如对产品质量或规格有任何异议，须在签收后 <strong>12小时内</strong> 立即联系 MST 并提交有效凭证。</p>
    <div class="alert alert-info py-2 px-3 small border-0 rounded-3 my-2">
        <i class="bi bi-info-circle me-1"></i> 详见我司 <a href="/zh/policy/refund-policy" class="alert-link fw-semibold">退款与退换货政策</a>。
    </div>
</div>

<div class="policy-section mb-4">
    <h3 class="h5 fw-bold text-dark mb-3"><i class="bi bi-snow2 text-primary me-2"></i>8. 冷冻产品储存与温控责任</h3>
    <p>在货物交付或提取完成后，客户须立即将冷冻产品转移至具备达标温控的专业冷冻设施中存放。</p>
    <p class="text-muted small">在适用法律允许的最大范围内，交付完成后因客户自身仓储不当、解冻失控、二次污染、人为延误入库等造成的任何品质劣化，MST 概不承担责任。本政策不限制法律规定不得排除的法定消费者权利。</p>
</div>

<div class="policy-section mb-4">
    <h3 class="h5 fw-bold text-dark mb-3"><i class="bi bi-exclamation-triangle text-primary me-2"></i>9. 配送未能完成的处理</h3>
    <p>如因以下原因导致配送未能顺利完成：</p>
    <ul class="list-unstyled ps-3">
        <li class="mb-2"><i class="bi bi-x-circle text-danger me-2"></i>现场无授权人员接收</li>
        <li class="mb-2"><i class="bi bi-x-circle text-danger me-2"></i>提供的送货地址错误或无法进入</li>
        <li class="mb-2"><i class="bi bi-x-circle text-danger me-2"></i>客户无法联络</li>
        <li class="mb-2"><i class="bi bi-x-circle text-danger me-2"></i>未按约定时间接货</li>
        <li class="mb-2"><i class="bi bi-x-circle text-danger me-2"></i>车辆已出车后客户临时要求更改时间或地点</li>
    </ul>
    <p class="text-muted small">可能会产生额外的运输、等待、二次配送或装卸处理费用。</p>
</div>

<div class="policy-section mb-4">
    <h3 class="h5 fw-bold text-dark mb-3"><i class="bi bi-shop text-primary me-2"></i>10. 到店自提（Walk-in Self-Collection）</h3>
    <div class="mb-2"><span class="badge bg-secondary-subtle text-dark border px-3 py-1 rounded-pill fw-semibold">仅限到店自提 · 不提供配送</span></div>
    <p>选择到店自提的客户直接前往我司提货已确认的订单。由于客户自行提货，因此不涉及任何配送服务或配送费用（此为到店自提，而非免费配送服务）。</p>
    <p>订单须在库存确认及付款确认后方可进入备货及安排自提。未完成付款的自提订单不会进入备货或提货流程。</p>
    <p>客户在提货时应在合理可行的情况下当面核对订单，如发现明显问题应立即与现场人员联系。</p>
    <p class="text-muted small">详细自提指引（包括在2号柜台提取货物）将在订单确认及提货通知时向客户提供。自提完成后，客户须自行负责将冷冻产品尽快转移至适宜的冷冻设施中妥善存放。</p>
</div>

<div class="policy-section mb-4">
    <h3 class="h5 fw-bold text-dark mb-3"><i class="bi bi-building text-primary me-2"></i>11. 大宗批发与批量配送</h3>
    <p>大宗订单或批发货运可能需要专门排定物流班次与装载计划。</p>
    <p class="text-muted small">具体的物流配送约定可在正式报价单、销售确认书或其他商业协议中载明。</p>
</div>

<div class="policy-section mb-4">
    <h3 class="h5 fw-bold text-dark mb-3"><i class="bi bi-globe2 text-primary me-2"></i>12. 国际与跨境订单</h3>
    <p>MST 当前网站市场定位为：<strong>马来西亚与新加坡</strong>。</p>
    <p>国际及跨境订单：<strong>按个案评估与提供，取决于产品、目的地、物流、监管及商业要求</strong>。</p>
    <p class="text-muted small">区域或国际供应仍属于未来或个案评估的发展方向，MST 不提供普遍性的全球或国际自动配送服务。</p>
    <p>如达成国际或跨境订单，除非另有书面约定，客户可能须承担以下适用费用：</p>
    <ul class="list-unstyled ps-3">
        <li class="mb-2"><i class="bi bi-dot text-primary fs-5 me-1"></i>国际海运/空运运费</li>
        <li class="mb-2"><i class="bi bi-dot text-primary fs-5 me-1"></i>海关清关与报关费</li>
        <li class="mb-2"><i class="bi bi-dot text-primary fs-5 me-1"></i>进出口检验检疫许可费用</li>
        <li class="mb-2"><i class="bi bi-dot text-primary fs-5 me-1"></i>关税与进口税</li>
        <li class="mb-2"><i class="bi bi-dot text-primary fs-5 me-1"></i>本地增值税或消费税</li>
        <li class="mb-2"><i class="bi bi-dot text-primary fs-5 me-1"></i>港口及冷库装卸杂费</li>
        <li class="mb-2"><i class="bi bi-dot text-primary fs-5 me-1"></i>其他目的地相关物流成本</li>
    </ul>
</div>

<div class="policy-section mb-4">
    <h3 class="h5 fw-bold text-dark mb-3"><i class="bi bi-clipboard-check text-primary me-2"></i>13. 交付凭证</h3>
    <p>交付确认可通过以下形式进行记录：</p>
    <ul class="list-unstyled ps-3">
        <li class="mb-2"><i class="bi bi-check2 text-primary me-2"></i>送货单（DO / Delivery Order）</li>
        <li class="mb-2"><i class="bi bi-check2 text-primary me-2"></i>正式发票（Invoice）</li>
        <li class="mb-2"><i class="bi bi-check2 text-primary me-2"></i>签收记录单</li>
        <li class="mb-2"><i class="bi bi-check2 text-primary me-2"></i>客户或指定授权代表签字盖章</li>
        <li class="mb-2"><i class="bi bi-check2 text-primary me-2"></i>电子系统签收或电子照片记录</li>
        <li class="mb-2"><i class="bi bi-check2 text-primary me-2"></i>其他符合商业惯例的交接凭据</li>
    </ul>
</div>

<div class="policy-section mb-4">
    <h3 class="h5 fw-bold text-dark mb-3"><i class="bi bi-headset text-primary me-2"></i>14. 联系我们</h3>
    <div class="card bg-light border-0 shadow-sm p-4 rounded-3">
        <p class="fw-bold fs-6 text-dark mb-2">镁嘉国际贸易有限公司（MST Import and Export Sdn. Bhd.）</p>
        <p class="mb-2 text-muted">
            <i class="bi bi-geo-alt-fill text-primary me-2"></i>7 Jalan SILC 2/18, Kawasan Perindustrian SILC, 79200 Iskandar Puteri, Johor, Malaysia
            <br>
            <a href="https://maps.google.com/?q=7+Jalan+SILC+2/18+Kawasan+Perindustrian+SILC+79200+Iskandar+Puteri+Johor+Malaysia" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-outline-primary mt-2">
                <i class="bi bi-map me-1"></i> 查看谷歌地图导航 (Google Maps)
            </a>
        </p>
        <hr class="my-2 text-muted opacity-25">
        <p class="mb-2">
            <i class="bi bi-envelope-fill text-primary me-2"></i><strong>电子邮件：</strong> 
            <a href="mailto:mikatrading15@gmail.com" class="text-decoration-none fw-semibold">mikatrading15@gmail.com</a>
        </p>
        <p class="mb-2">
            <i class="bi bi-telephone-fill text-primary me-2"></i><strong>服务热线：</strong> 
            <a href="tel:+60132800168" class="text-decoration-none fw-semibold">+60 13-280 0168</a>
        </p>
        <p class="mb-0">
            <i class="bi bi-whatsapp text-success me-2"></i><strong>WhatsApp 咨询：</strong> 
            <a href="https://wa.me/601112710260" target="_blank" rel="noopener noreferrer" class="text-decoration-none fw-semibold me-2">+60 11-1271 0260</a>
            <a href="https://wa.me/601112710260" target="_blank" rel="noopener noreferrer" class="badge bg-success text-white text-decoration-none">
                <i class="bi bi-whatsapp me-1"></i>点击通过 WhatsApp 即时咨询
            </a>
        </p>
    </div>
</div>"""

# Construct updated Shipping Policy BM
sp_bm = """<div class="policy-header-badge mb-3">
    <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2 rounded-pill fw-semibold">
        <i class="bi bi-shield-check me-1"></i> Tarikh Berkuat Kuasa: 25 September 2026 | Terakhir Dikemas Kini: 25 September 2026
    </span>
</div>

<div class="lead text-dark mb-4 fw-normal">
    <p><strong>MST Import and Export Sdn. Bhd.</strong> membekalkan makanan laut beku, daging beku, makanan sejuk beku, bahan-bahan makanan dan produk sensitif suhu yang lain.</p>
</div>

<hr class="my-4 text-muted opacity-25">

<div class="policy-section mb-4">
    <h3 class="h5 fw-bold text-dark mb-3"><i class="bi bi-truck text-primary me-2"></i>1. Liputan Penghantaran Tempatan Standard</h3>
    <p>MST menyediakan penghantaran pintu ke pintu dengan pengaturan penghantaran berasaskan zon dan ambang rujukan yang telus:</p>
    
    <div class="row g-3 my-2">
        <div class="col-md-6">
            <div class="card h-100 border-0 bg-light p-3 shadow-sm">
                <div class="d-flex align-items-center mb-2">
                    <span class="badge bg-primary me-2 px-2 py-1">B2B / Borong</span>
                    <strong class="text-dark">Pesanan Komersial</strong>
                </div>
                <p class="fs-5 fw-bold text-primary mb-0">Ambang Penghantaran Standard: RM350</p>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card h-100 border-0 bg-light p-3 shadow-sm">
                <div class="d-flex align-items-center mb-2">
                    <span class="badge bg-success me-2 px-2 py-1">B2C / Runcit</span>
                    <strong class="text-dark">Pesanan Pengguna</strong>
                </div>
                <p class="fs-5 fw-bold text-success mb-0">Ambang Penghantaran Standard: RM100</p>
            </div>
        </div>
    </div>
    
    <p class="text-muted small mt-2">
        <i class="bi bi-geo-alt me-1 text-primary"></i> <strong>Liputan Penghantaran Tempatan Standard:</strong> <code>Johor Bahru dan Iskandar Puteri / Nusajaya</code>.
    </p>
</div>

<div class="policy-section mb-4">
    <h3 class="h5 fw-bold text-dark mb-3"><i class="bi bi-cart-check text-primary me-2"></i>2. Pesanan di Bawah Ambang Penghantaran Standard</h3>
    <p>Pelanggan masih boleh membuat pesanan di bawah nilai rujukan ambang standard yang berkenaan.</p>
    <p>Walau bagaimanapun, caj pengangkutan / penghantaran akan dikenakan.</p>
    <p class="text-muted small">Caj penghantaran yang berkenaan akan dimaklumkan sebelum pesanan disahkan mengikut keupayaan sebenar sistem.</p>
</div>

<div class="policy-section mb-4">
    <h3 class="h5 fw-bold text-dark mb-3"><i class="bi bi-pin-map text-primary me-2"></i>3. Di Luar Kawasan Tempatan Standard</h3>
    <p>Penghantaran di luar kawasan tempatan standard (Johor Bahru dan Iskandar Puteri / Nusajaya) tidak disediakan secara automatik.</p>
    <p>Penghantaran di luar kawasan tempatan standard mungkin:</p>
    <ul class="list-unstyled ps-3">
        <li class="mb-2"><i class="bi bi-check2 text-primary me-2"></i>Dipertimbangkan mengikut kes demi kes</li>
        <li class="mb-2"><i class="bi bi-check2 text-primary me-2"></i>Tertakluk kepada destinasi</li>
        <li class="mb-2"><i class="bi bi-check2 text-primary me-2"></i>Tertakluk kepada keperluan produk</li>
        <li class="mb-2"><i class="bi bi-check2 text-primary me-2"></i>Tertakluk kepada keperluan logistik</li>
        <li class="mb-2"><i class="bi bi-check2 text-primary me-2"></i>Tertakluk kepada caj pengangkutan yang berkaitan</li>
    </ul>
    <p>Caj pengangkutan/logistik yang berkaitan mungkin perlu dibayar oleh pelanggan melainkan dipersetujui sebaliknya dengan MST.</p>
</div>

<div class="policy-section mb-4">
    <h3 class="h5 fw-bold text-dark mb-3"><i class="bi bi-receipt text-primary me-2"></i>4. Pengiraan Caj Penghantaran / Pengangkutan</h3>
    <p>Caj penghantaran atau pengangkutan mungkin bergantung kepada faktor termasuk:</p>
    <ul class="list-unstyled ps-3">
        <li class="mb-2"><i class="bi bi-dot text-primary fs-5 me-1"></i>Lokasi / zon penghantaran</li>
        <li class="mb-2"><i class="bi bi-dot text-primary fs-5 me-1"></i>Jarak</li>
        <li class="mb-2"><i class="bi bi-dot text-primary fs-5 me-1"></i>Kuantiti / berat pesanan</li>
        <li class="mb-2"><i class="bi bi-dot text-primary fs-5 me-1"></i>Keperluan kenderaan</li>
        <li class="mb-2"><i class="bi bi-dot text-primary fs-5 me-1"></i>Keperluan logistik</li>
        <li class="mb-2"><i class="bi bi-dot text-primary fs-5 me-1"></i>Keperluan rantaian sejuk</li>
        <li class="mb-2"><i class="bi bi-dot text-primary fs-5 me-1"></i>Jadual penghantaran</li>
        <li class="mb-2"><i class="bi bi-dot text-primary fs-5 me-1"></i>Keperluan logistik lain yang berkaitan</li>
    </ul>
    <p class="text-muted small">Sekiranya caj penghantaran dikenakan, ia akan dimaklumkan kepada pelanggan sebelum pengesahan pesanan akhir mengikut keupayaan sistem sebenar.</p>
</div>

<div class="policy-section mb-4">
    <h3 class="h5 fw-bold text-dark mb-3"><i class="bi bi-calendar-check text-primary me-2"></i>5. Jadual Penghantaran</h3>
    <p>Masa penghantaran adalah anggaran melainkan disahkan secara khusus secara bertulis.</p>
    <p class="text-muted small">Penghantaran mungkin dipengaruhi oleh kesesakan lalu lintas, cuaca, cuti umum, ketersediaan kenderaan, ketersediaan produk, penerimaan pelanggan atau keadaan di luar kawalan munasabah MST.</p>
</div>

<div class="policy-section mb-4">
    <h3 class="h5 fw-bold text-dark mb-3"><i class="bi bi-signpost-2 text-primary me-2"></i>6. Alamat Penghantaran</h3>
    <p>Pelanggan mesti memberikan maklumat penghantaran yang tepat:</p>
    <ul class="list-unstyled ps-3">
        <li class="mb-2"><i class="bi bi-check2 text-primary me-2"></i>Alamat penghantaran yang lengkap</li>
        <li class="mb-2"><i class="bi bi-check2 text-primary me-2"></i>Nama orang untuk dihubungi</li>
        <li class="mb-2"><i class="bi bi-check2 text-primary me-2"></i>Nombor telefon untuk dihubungi</li>
        <li class="mb-2"><i class="bi bi-check2 text-primary me-2"></i>Arahan penerimaan</li>
        <li class="mb-2"><i class="bi bi-check2 text-primary me-2"></i>Waktu operasi atau penerimaan sekiranya berkaitan</li>
    </ul>
    <p class="text-muted small">Setakat yang dibenarkan oleh undang-undang terpakai, MST tidak bertanggungjawab ke atas sebarang kelewatan atau caj tambahan yang disebabkan oleh maklumat tidak tepat atau tidak lengkap yang diberikan oleh pelanggan.</p>
</div>

<div class="policy-section mb-4">
    <h3 class="h5 fw-bold text-dark mb-3"><i class="bi bi-box-seam text-primary me-2"></i>7. Penerimaan Produk Beku</h3>
    <p>Pelanggan perlu memastikan orang yang diberi kuasa berada di lokasi untuk menerima pesanan.</p>
    <p>Pelanggan perlu memeriksa produk dan bungkusan dengan segera semasa penghantaran.</p>
    <p>Sebarang aduan kualiti produk mesti dilaporkan kepada MST dalam tempoh <strong>12 jam</strong> selepas menerima barangan.</p>
    <div class="alert alert-info py-2 px-3 small border-0 rounded-3 my-2">
        <i class="bi bi-info-circle me-1"></i> Sila rujuk <a href="/bm/policy/refund-policy" class="alert-link fw-semibold">Polisi Bayaran Balik &amp; Pemulangan</a> kami.
    </div>
</div>

<div class="policy-section mb-4">
    <h3 class="h5 fw-bold text-dark mb-3"><i class="bi bi-snow2 text-primary me-2"></i>8. Penyimpanan Produk Beku</h3>
    <p>Pelanggan bertanggungjawab memindahkan produk beku ke ruang penyimpanan beku yang bersesuaian dengan segera selepas menerima penghantaran.</p>
    <p class="text-muted small">Setakat yang dibenarkan oleh undang-undang terpakai, MST tidak bertanggungjawab terhadap kerosakan selepas penghantaran yang berpunca daripada penyimpanan, penyahbekuan, pengendalian atau keadaan lain yang disebabkan oleh pelanggan. Tiada apa-apa dalam polisi ini mengehadkan hak statutori yang tidak boleh dikecualikan di bawah undang-undang terpakai.</p>
</div>

<div class="policy-section mb-4">
    <h3 class="h5 fw-bold text-dark mb-3"><i class="bi bi-exclamation-triangle text-primary me-2"></i>9. Penghantaran Gagal</h3>
    <p>Sekiranya penghantaran tidak dapat diselesaikan kerana:</p>
    <ul class="list-unstyled ps-3">
        <li class="mb-2"><i class="bi bi-x-circle text-danger me-2"></i>Tiada penerima yang diberi kuasa hadir</li>
        <li class="mb-2"><i class="bi bi-x-circle text-danger me-2"></i>Alamat yang diberikan salah</li>
        <li class="mb-2"><i class="bi bi-x-circle text-danger me-2"></i>Pelanggan tidak dapat dihubungi</li>
        <li class="mb-2"><i class="bi bi-x-circle text-danger me-2"></i>Pengaturan penerimaan tidak dipatuhi</li>
        <li class="mb-2"><i class="bi bi-x-circle text-danger me-2"></i>Pelanggan meminta perubahan selepas penghantaran berlepas</li>
    </ul>
    <p class="text-muted small">Caj tambahan bagi pengangkutan, masa menunggu, penghantaran semula atau pengendalian mungkin dikenakan.</p>
</div>

<div class="policy-section mb-4">
    <h3 class="h5 fw-bold text-dark mb-3"><i class="bi bi-shop text-primary me-2"></i>10. Pengambilan Sendiri (Walk-in Self-Collection)</h3>
    <div class="mb-2"><span class="badge bg-secondary-subtle text-dark border px-3 py-1 rounded-pill fw-semibold">Pengambilan sendiri sahaja · Tiada penghantaran</span></div>
    <p>Pelanggan yang memilih Pengambilan Sendiri (Walk-in Self-Collection) mengambil pesanan yang telah disahkan terus di premis kami. Tiada caj penghantaran kerana pelanggan mengambil pesanan mereka sendiri (ini adalah pengambilan sendiri, bukan perkhidmatan penghantaran percuma).</p>
    <p>Pesanan tertakluk kepada ketersediaan produk dan pengesahan pembayaran. Pengesahan pembayaran mesti dibuat sebelum pesanan diproses untuk penyediaan dan sebelum pesanan boleh dilepaskan untuk diambil.</p>
    <p>Pelanggan perlu memeriksa pesanan mereka semasa pengambilan sekiranya munasabah dan praktikal, serta menghubungi MST dengan segera jika terdapat sebarang isu ketara.</p>
    <p class="text-muted small">Arahan pengambilan terperinci (termasuk pengambilan fizikal di Kaunter 2) akan diberikan semasa pengesahan pesanan dan pemberitahuan pengambilan. Sebaik sahaja diambil, pelanggan bertanggungjawab memindahkan produk beku ke storan beku yang bersesuaian dengan segera.</p>
</div>

<div class="policy-section mb-4">
    <h3 class="h5 fw-bold text-dark mb-3"><i class="bi bi-building text-primary me-2"></i>11. Penghantaran Pukal dan Borong</h3>
    <p>Pesanan borong atau pukal mungkin memerlukan penetapan jadual penghantaran yang berasingan.</p>
    <p class="text-muted small">Pengaturan penghantaran khusus boleh dinyatakan dalam sebut harga, pengesahan jualan atau perjanjian komersial yang berkaitan.</p>
</div>

<div class="policy-section mb-4">
    <h3 class="h5 fw-bold text-dark mb-3"><i class="bi bi-globe2 text-primary me-2"></i>12. Pesanan Antarabangsa / Rentas Sempadan</h3>
    <p>Kedudukan pasaran laman web MST pada masa ini kekal: <strong>Malaysia dan Singapura</strong>.</p>
    <p>Pesanan antarabangsa dan rentas sempadan: <strong>Tersedia / dipertimbangkan mengikut kes demi kes, tertakluk kepada keperluan produk, destinasi, logistik, pengawalseliaan dan komersial</strong>.</p>
    <p class="text-muted small">Bekalan serantau dan antarabangsa kekal sebagai arah pembangunan masa hadapan atau susunan kes demi kes; MST tidak menyediakan perkhidmatan rangkaian penghantaran global sejagat automatik.</p>
    <p>Sekiranya pesanan antarabangsa atau rentas sempadan dipersetujui, pelanggan mungkin bertanggungjawab ke atas:</p>
    <ul class="list-unstyled ps-3">
        <li class="mb-2"><i class="bi bi-dot text-primary fs-5 me-1"></i>Tambang logistik dan caj pengangkutan</li>
        <li class="mb-2"><i class="bi bi-dot text-primary fs-5 me-1"></i>Pelepasan kastam dan dokumentasi</li>
        <li class="mb-2"><i class="bi bi-dot text-primary fs-5 me-1"></i>Permit import dan pematuhan kawal selia</li>
        <li class="mb-2"><i class="bi bi-dot text-primary fs-5 me-1"></i>Duti kastam, cukai dan fi destinasi</li>
        <li class="mb-2"><i class="bi bi-dot text-primary fs-5 me-1"></i>Caj pengendalian dan logistik rantaian sejuk</li>
    </ul>
    <p class="text-muted small">melainkan dipersetujui sebaliknya secara bertulis.</p>
</div>

<div class="policy-section mb-4">
    <h3 class="h5 fw-bold text-dark mb-3"><i class="bi bi-clipboard-check text-primary me-2"></i>13. Bukti Penghantaran</h3>
    <p>Penghantaran boleh disahkan melalui:</p>
    <ul class="list-unstyled ps-3">
        <li class="mb-2"><i class="bi bi-check2 text-primary me-2"></i>Nota penghantaran (DO / Delivery Order)</li>
        <li class="mb-2"><i class="bi bi-check2 text-primary me-2"></i>Invois</li>
        <li class="mb-2"><i class="bi bi-check2 text-primary me-2"></i>Bukti penghantaran</li>
        <li class="mb-2"><i class="bi bi-check2 text-primary me-2"></i>Tandatangan pelanggan atau wakil sah</li>
        <li class="mb-2"><i class="bi bi-check2 text-primary me-2"></i>Pengesahan digital atau rekod foto</li>
        <li class="mb-2"><i class="bi bi-check2 text-primary me-2"></i>Rekod penghantaran munasabah yang lain</li>
    </ul>
</div>

<div class="policy-section mb-4">
    <h3 class="h5 fw-bold text-dark mb-3"><i class="bi bi-headset text-primary me-2"></i>14. Hubungi Kami</h3>
    <div class="card bg-light border-0 shadow-sm p-4 rounded-3">
        <p class="fw-bold fs-6 text-dark mb-2">MST Import and Export Sdn. Bhd.</p>
        <p class="mb-2 text-muted">
            <i class="bi bi-geo-alt-fill text-primary me-2"></i>7 Jalan SILC 2/18, Kawasan Perindustrian SILC, 79200 Iskandar Puteri, Johor, Malaysia
            <br>
            <a href="https://maps.google.com/?q=7+Jalan+SILC+2/18+Kawasan+Perindustrian+SILC+79200+Iskandar+Puteri+Johor+Malaysia" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-outline-primary mt-2">
                <i class="bi bi-map me-1"></i> Dapatkan Arah (Google Maps)
            </a>
        </p>
        <hr class="my-2 text-muted opacity-25">
        <p class="mb-2">
            <i class="bi bi-envelope-fill text-primary me-2"></i><strong>Emel:</strong> 
            <a href="mailto:mikatrading15@gmail.com" class="text-decoration-none fw-semibold">mikatrading15@gmail.com</a>
        </p>
        <p class="mb-2">
            <i class="bi bi-telephone-fill text-primary me-2"></i><strong>Telefon:</strong> 
            <a href="tel:+60132800168" class="text-decoration-none fw-semibold">+60 13-280 0168</a>
        </p>
        <p class="mb-0">
            <i class="bi bi-whatsapp text-success me-2"></i><strong>WhatsApp:</strong> 
            <a href="https://wa.me/601112710260" target="_blank" rel="noopener noreferrer" class="text-decoration-none fw-semibold me-2">+60 11-1271 0260</a>
            <a href="https://wa.me/601112710260" target="_blank" rel="noopener noreferrer" class="badge bg-success text-white text-decoration-none">
                <i class="bi bi-whatsapp me-1"></i>Sembang di WhatsApp
            </a>
        </p>
    </div>
</div>"""

# Update Shipping Policy in MySQL
mysql_cur.execute(
    "UPDATE policies SET content = %s, content_zh = %s, content_bm = %s, updated_at = '2026-09-25 00:00:00' WHERE slug = 'shipping-policy'",
    (sp_en, sp_zh, sp_bm)
)
mysql_conn.commit()
print("Updated shipping-policy in MySQL")

# Update Shipping Policy in SQLite
sqlite_cur.execute(
    "UPDATE policies SET content = ?, content_zh = ?, content_bm = ?, updated_at = '2026-09-25 00:00:00' WHERE slug = 'shipping-policy'",
    (sp_en, sp_zh, sp_bm)
)
sqlite_conn.commit()
print("Updated shipping-policy in SQLite")

# Now let's handle terms-and-conditions
mysql_cur.execute("SELECT * FROM policies WHERE slug = 'terms-and-conditions'")
tc = mysql_cur.fetchone()

tc_en = open('scratch/terms_en.html', encoding='utf-8').read()
tc_zh = open('scratch/terms_zh.html', encoding='utf-8').read()
tc_bm = open('scratch/terms_bm.html', encoding='utf-8').read()

# Update terms section 12 in EN
old_terms_en = """<h2>12. Delivery</h2>
<p>Local door-to-door delivery is available based on standard reference delivery thresholds:</p>
<ul>
  <li><strong>B2B / Wholesale:</strong> Reference threshold RM350</li>
  <li><strong>B2C / Retail:</strong> Reference threshold RM100</li>
</ul>
<p>These amounts serve as delivery/order reference thresholds and do not function as hard minimum-order restrictions. Customers can still place or continue orders below these reference amounts where delivery service is available, subject to an additional transportation / delivery fee based on delivery location / zone and logistics requirements. Standard delivery coverage applies to Johor Bahru and Iskandar Puteri / Nusajaya.</p>
<p>For locations outside these areas, customers are responsible for applicable transportation or delivery charges unless otherwise agreed.</p>
<p>Please refer to our Shipping & Delivery Policy.</p>

<h3>Walk-in / Counter Collection</h3>
<p>Walk-in and counter collection orders are subject to product availability, payment confirmation and the collection arrangements provided by MST. Customers should present the applicable order reference or payment confirmation when collecting their order.</p>"""

new_terms_en = """<h2>12. Delivery & Fulfilment</h2>
<p>Local door-to-door delivery is available based on standard reference delivery thresholds:</p>
<ul>
  <li><strong>B2B / Wholesale:</strong> Reference threshold RM350</li>
  <li><strong>B2C / Retail:</strong> Reference threshold RM100</li>
</ul>
<p>These amounts serve as delivery/order reference thresholds and do not function as hard minimum-order restrictions. Customers can still place or continue orders below these reference amounts where delivery service is available, subject to an additional transportation or delivery fee based on delivery location / zone and logistics requirements.</p>
<p><strong>Standard Local Delivery Coverage:</strong> <code>Johor Bahru and Iskandar Puteri / Nusajaya</code>.</p>
<p>Delivery outside the standard local area is not automatically available and may be considered case-by-case, subject to destination, product requirements, logistics requirements, and applicable transportation charges payable by the customer unless otherwise agreed with MST. International and cross-border orders are available or considered on a case-by-case basis, subject to product, destination, logistics, regulatory and commercial requirements. MST's current website market positioning remains Malaysia and Singapore.</p>
<p>Delivery or transportation charges may depend on factors including delivery location/zone, distance, order quantity/weight, vehicle requirements, logistics requirements, cold-chain requirements, delivery schedule, and other applicable logistics requirements.</p>
<p>Please refer to our Shipping & Delivery Policy.</p>

<h3>Walk-in Self-Collection</h3>
<p>For Walk-in Self-Collection orders (self-collection only · no delivery), customers collect their orders directly from our premises. There is no delivery fee because the customer collects the order in person (this is self-collection, not a free delivery service). Orders are subject to product availability and payment confirmation before proceeding to preparation or collection.</p>"""

if old_terms_en in tc_en:
    tc_en = tc_en.replace(old_terms_en, new_terms_en)
    print("Replaced section 12 in terms EN")
else:
    print("Could not find exact match in terms EN, doing targeted replacement")
    # targeted replacement of headings
    tc_en = tc_en.replace("<h3>Walk-in / Counter Collection</h3>", "<h3>Walk-in Self-Collection</h3>")
    tc_en = tc_en.replace("Standard delivery coverage applies to Johor Bahru and Nusajaya.", "Standard local delivery coverage applies to Johor Bahru and Iskandar Puteri / Nusajaya.")

# Update terms section 12 in ZH
old_terms_zh = """<h2>12. 配送服务</h2>
<p>MST 提供全程冷链本地送货上门服务，并设立以下标准参考门槛：</p>
<ul>
  <li><strong>商业批发 / B2B：</strong>参考起送金额 RM350</li>
  <li><strong>个人零售 / B2C：</strong>参考起送金额 RM100</li>
</ul>
<p>上述金额仅作为配送参考门槛，并非强制性最低订单限制。在配送服务可行的前提下，低于参考金额的订单仍可提交或继续结账，但须根据配送区域/地点及物流要求额外加收适用的运输或配送费用。标准配送范围适用于新山（Johor Bahru）与依斯干达公主城 / 努沙再也（Iskandar Puteri / Nusajaya）。</p>
<p>对于上述标准配送区域以外的地点，除双方另有书面约定外，客户须承担适用的运输或配送费用。</p>
<p>详情请参阅我们的《配送政策与物流条款》。</p>

<h3>门店自选 / 柜台自提</h3>
<p>门店自选或柜台自提订单须视产品供应情况、完成付款确认及遵守 MST 提供的自提安排而定。客户在提货时须出示适用的订单编号或付款凭证。</p>"""

new_terms_zh = """<h2>12. 配送与履约</h2>
<p>MST 提供全程冷链本地送货上门服务，并设立以下标准参考门槛：</p>
<ul>
  <li><strong>商业批发 / B2B：</strong>参考起送金额 RM350</li>
  <li><strong>个人零售 / B2C：</strong>参考起送金额 RM100</li>
</ul>
<p>上述金额仅作为配送参考门槛，并非强制性最低订单限制。在配送服务可行的前提下，低于参考金额的订单仍可提交或继续结账，但须根据配送区域/地点及物流要求额外加收适用的运输或配送费用。</p>
<p><strong>标准本地配送范围：</strong><code>新山及依斯干达公主城 / 努沙再也（Johor Bahru and Iskandar Puteri / Nusajaya）</code>。</p>
<p>标准本地范围以外的配送不视为自动提供，可能按个案评估，并取决于目的地、产品要求、物流要求及适用的运输费用（除另有约定外由客户承担）。国际及跨境订单按个案评估与提供，取决于产品、目的地、物流、监管及商业要求。MST 当前网站市场定位保持为马来西亚与新加坡。</p>
<p>配送或运输费用取决于配送地点/区域、距离、订单数量/重量、车辆要求、物流要求、冷链要求、配送时间表及其他适用物流要求。</p>
<p>详情请参阅我们的《配送政策与物流条款》。</p>

<h3>到店自提（Walk-in Self-Collection）</h3>
<p>到店自提订单（仅限到店自提 · 不提供配送）由客户直接前往我司提货。由于客户自行提货，不收取配送费（此为到店自提，并非免费配送）。订单须在库存确认及付款确认后方可进入备货及安排自提。</p>"""

if old_terms_zh in tc_zh:
    tc_zh = tc_zh.replace(old_terms_zh, new_terms_zh)
    print("Replaced section 12 in terms ZH")
else:
    print("Could not find exact match in terms ZH, doing targeted replacement")
    tc_zh = tc_zh.replace("<h3>门店自选 / 柜台自提</h3>", "<h3>到店自提（Walk-in Self-Collection）</h3>")

# Update terms section 12 in BM
old_terms_bm = """<h2>12. Penghantaran</h2>
<p>Penghantaran tempatan pintu ke pintu disediakan berdasarkan ambang rujukan standard:</p>
<ul>
  <li><strong>B2B / Borong:</strong> Nilai rujukan RM350</li>
  <li><strong>B2C / Runcit:</strong> Nilai rujukan RM100</li>
</ul>
<p>Jumlah ini berfungsi sebagai ambang rujukan penghantaran dan bukan sebagai sekatan pesanan minimum yang ketat. Pelanggan masih boleh membuat atau meneruskan pesanan di bawah nilai rujukan ini sekiranya perkhidmatan penghantaran tersedia, tertakluk kepada caj pengangkutan / penghantaran tambahan berdasarkan lokasi / zon penghantaran dan keperluan logistik. Liputan penghantaran standard terpakai untuk Johor Bahru dan Nusajaya / Iskandar Puteri.</p>
<p>Bagi lokasi di luar kawasan ini, pelanggan bertanggungjawab terhadap caj pengangkutan atau penghantaran yang berkaitan melainkan dipersetujui sebaliknya.</p>
<p>Sila rujuk Polisi Penghantaran kami.</p>

<h3>Pesanan Masuk Sendiri / Pengambilan di Kaunter</h3>
<p>Pesanan secara terus (walk-in) dan pengambilan di kaunter adalah tertakluk kepada ketersediaan stok, pengesahan pembayaran dan pengaturan pengambilan yang disediakan oleh MST. Pelanggan perlu mengemukakan rujukan pesanan atau pengesahan pembayaran yang berkenaan semasa mengambil pesanan mereka.</p>"""

new_terms_bm = """<h2>12. Penghantaran & Pemenuhan</h2>
<p>Penghantaran tempatan pintu ke pintu disediakan berdasarkan ambang rujukan standard:</p>
<ul>
  <li><strong>B2B / Borong:</strong> Ambang rujukan RM350</li>
  <li><strong>B2C / Runcit:</strong> Ambang rujukan RM100</li>
</ul>
<p>Jumlah ini berfungsi sebagai ambang rujukan penghantaran dan bukan sebagai sekatan pesanan minimum yang ketat. Pelanggan masih boleh membuat atau meneruskan pesanan di bawah nilai rujukan ini sekiranya perkhidmatan penghantaran tersedia, tertakluk kepada caj pengangkutan / penghantaran tambahan berdasarkan lokasi / zon penghantaran dan keperluan logistik.</p>
<p><strong>Liputan Penghantaran Tempatan Standard:</strong> <code>Johor Bahru dan Iskandar Puteri / Nusajaya</code>.</p>
<p>Penghantaran di luar kawasan tempatan standard tidak disediakan secara automatik dan boleh dipertimbangkan mengikut kes demi kes, tertakluk kepada destinasi, keperluan produk, keperluan logistik dan caj pengangkutan yang berkenaan (dibayar oleh pelanggan melainkan dipersetujui sebaliknya). Pesanan antarabangsa dan rentas sempadan tersedia atau dipertimbangkan mengikut kes demi kes, tertakluk kepada keperluan produk, destinasi, logistik, pengawalseliaan dan komersial. Kedudukan pasaran laman web MST pada masa ini kekal Malaysia dan Singapura.</p>
<p>Caj penghantaran atau pengangkutan bergantung kepada faktor termasuk lokasi/zon penghantaran, jarak, kuantiti/berat pesanan, keperluan kenderaan, keperluan logistik, keperluan rantaian sejuk, jadual penghantaran dan keperluan logistik lain yang berkaitan.</p>
<p>Sila rujuk Polisi Penghantaran kami.</p>

<h3>Pengambilan Sendiri (Walk-in Self-Collection)</h3>
<p>Bagi pesanan Pengambilan Sendiri (pengambilan sendiri sahaja · tiada penghantaran), pelanggan mengambil pesanan terus di premis kami. Tiada caj penghantaran kerana pelanggan mengambil pesanan sendiri (ini adalah pengambilan sendiri, bukan penghantaran percuma). Pesanan tertakluk kepada ketersediaan produk dan pengesahan pembayaran sebelum diteruskan kepada penyediaan atau pengambilan.</p>"""

if old_terms_bm in tc_bm:
    tc_bm = tc_bm.replace(old_terms_bm, new_terms_bm)
    print("Replaced section 12 in terms BM")
else:
    print("Could not find exact match in terms BM, doing targeted replacement")
    tc_bm = tc_bm.replace("<h3>Pesanan Masuk Sendiri / Pengambilan di Kaunter</h3>", "<h3>Pengambilan Sendiri (Walk-in Self-Collection)</h3>")

# Update Terms in MySQL
mysql_cur.execute(
    "UPDATE policies SET content = %s, content_zh = %s, content_bm = %s, updated_at = '2026-09-25 00:00:00' WHERE slug = 'terms-and-conditions'",
    (tc_en, tc_zh, tc_bm)
)
mysql_conn.commit()
print("Updated terms-and-conditions in MySQL")

# Update Terms in SQLite
sqlite_cur.execute(
    "UPDATE policies SET content = ?, content_zh = ?, content_bm = ?, updated_at = '2026-09-25 00:00:00' WHERE slug = 'terms-and-conditions'",
    (tc_en, tc_zh, tc_bm)
)
sqlite_conn.commit()
print("Updated terms-and-conditions in SQLite")

mysql_conn.close()
sqlite_conn.close()
print("All policies updated successfully!")
