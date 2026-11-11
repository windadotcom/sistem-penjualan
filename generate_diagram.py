"""
Generate Class Diagram PNG menggunakan tkinter canvas + PIL/Pillow bawaan Python.
Fallback: pakai turtle/canvas to PostScript lalu convert, atau langsung draw dengan
modul 'html' + simpan sebagai SVG yang bisa dibuka browser.
"""
import os

SVG = '''<?xml version="1.0" encoding="UTF-8"?>
<svg xmlns="http://www.w3.org/2000/svg" width="1400" height="1100" viewBox="0 0 1400 1100"
     style="background:#f8fffe;font-family:Segoe UI,Arial,sans-serif;">

  <!-- ── Definisi Gradients & Shadows ── -->
  <defs>
    <filter id="shadow">
      <feDropShadow dx="2" dy="2" stdDeviation="3" flood-color="#00000022"/>
    </filter>
    <linearGradient id="model-grad" x1="0" y1="0" x2="0" y2="1">
      <stop offset="0%" stop-color="#059669"/>
      <stop offset="100%" stop-color="#047857"/>
    </linearGradient>
    <linearGradient id="ctrl-grad" x1="0" y1="0" x2="0" y2="1">
      <stop offset="0%" stop-color="#0e7490"/>
      <stop offset="100%" stop-color="#0c6280"/>
    </linearGradient>
    <linearGradient id="mw-grad" x1="0" y1="0" x2="0" y2="1">
      <stop offset="0%" stop-color="#7c3aed"/>
      <stop offset="100%" stop-color="#6d28d9"/>
    </linearGradient>
    <marker id="arrow" markerWidth="10" markerHeight="7" refX="9" refY="3.5" orient="auto">
      <polygon points="0 0, 10 3.5, 0 7" fill="#475569"/>
    </marker>
    <marker id="arrow-dash" markerWidth="10" markerHeight="7" refX="9" refY="3.5" orient="auto">
      <polygon points="0 0, 10 3.5, 0 7" fill="#94a3b8"/>
    </marker>
  </defs>

  <!-- ═══════════════════════════════════
       JUDUL
  ═══════════════════════════════════ -->
  <text x="700" y="38" text-anchor="middle" font-size="22" font-weight="800" fill="#065f46">
    Class Diagram — Green Shop (Sistem Penjualan)
  </text>
  <line x1="200" y1="46" x2="1200" y2="46" stroke="#10b981" stroke-width="2"/>

  <!-- LEGEND -->
  <rect x="30" y="60" width="14" height="14" rx="3" fill="url(#model-grad)"/>
  <text x="50" y="72" font-size="11" fill="#374151">Model</text>
  <rect x="110" y="60" width="14" height="14" rx="3" fill="url(#ctrl-grad)"/>
  <text x="130" y="72" font-size="11" fill="#374151">Controller</text>
  <rect x="220" y="60" width="14" height="14" rx="3" fill="url(#mw-grad)"/>
  <text x="240" y="72" font-size="11" fill="#374151">Middleware</text>
  <line x1="310" y1="67" x2="360" y2="67" stroke="#475569" stroke-width="2" marker-end="url(#arrow)"/>
  <text x="368" y="72" font-size="11" fill="#374151">Relasi (hasMany/belongsTo)</text>
  <line x1="550" y1="67" x2="600" y2="67" stroke="#94a3b8" stroke-width="1.5" stroke-dasharray="6,3" marker-end="url(#arrow-dash)"/>
  <text x="608" y="72" font-size="11" fill="#374151">Dependency (uses)</text>

  <!-- ═══════════════════════════════════
       SECTION LABEL
  ═══════════════════════════════════ -->
  <text x="30" y="105" font-size="13" font-weight="700" fill="#059669">■ MODELS</text>
  <text x="530" y="105" font-size="13" font-weight="700" fill="#0e7490">■ CONTROLLERS</text>
  <text x="1130" y="105" font-size="13" font-weight="700" fill="#7c3aed">■ MIDDLEWARE</text>

  <!-- ═══════════════════════════════════
       MODEL: User  (x=30, y=120)
  ═══════════════════════════════════ -->
  <g filter="url(#shadow)">
    <rect x="30" y="115" width="240" height="225" rx="10" fill="white" stroke="#d1fae5" stroke-width="1.5"/>
    <rect x="30" y="115" width="240" height="34" rx="10" fill="url(#model-grad)"/>
    <rect x="30" y="135" width="240" height="14" fill="url(#model-grad)"/>
    <text x="150" y="137" text-anchor="middle" font-size="13" font-weight="700" fill="white">User</text>
    <!-- attrs -->
    <line x1="30" y1="149" x2="270" y2="149" stroke="#e5e7eb" stroke-width="1"/>
    <text x="42" y="165" font-size="11" fill="#374151">+ id : int (PK)</text>
    <text x="42" y="181" font-size="11" fill="#374151">+ name : string</text>
    <text x="42" y="197" font-size="11" fill="#374151">+ email : string (unique)</text>
    <text x="42" y="213" font-size="11" fill="#374151">+ password : string (hashed)</text>
    <text x="42" y="229" font-size="11" fill="#374151">+ role : string (admin|kasir)</text>
    <text x="42" y="245" font-size="11" fill="#374151">+ created_at : timestamp</text>
    <!-- methods -->
    <line x1="30" y1="253" x2="270" y2="253" stroke="#e5e7eb" stroke-width="1"/>
    <text x="42" y="269" font-size="11" fill="#0369a1">+ isAdmin() : bool</text>
    <text x="42" y="285" font-size="11" fill="#0369a1">+ sales() : HasMany</text>
    <text x="42" y="301" font-size="11" fill="#0369a1">+ casts() : array</text>
    <text x="42" y="317" font-size="11" fill="#0369a1">+ factory() : HasFactory</text>
  </g>

  <!-- ═══════════════════════════════════
       MODEL: Product  (x=30, y=365)
  ═══════════════════════════════════ -->
  <g filter="url(#shadow)">
    <rect x="30" y="360" width="240" height="230" rx="10" fill="white" stroke="#d1fae5" stroke-width="1.5"/>
    <rect x="30" y="360" width="240" height="34" rx="10" fill="url(#model-grad)"/>
    <rect x="30" y="380" width="240" height="14" fill="url(#model-grad)"/>
    <text x="150" y="382" text-anchor="middle" font-size="13" font-weight="700" fill="white">Product</text>
    <line x1="30" y1="394" x2="270" y2="394" stroke="#e5e7eb" stroke-width="1"/>
    <text x="42" y="410" font-size="11" fill="#374151">+ id : int (PK)</text>
    <text x="42" y="426" font-size="11" fill="#374151">+ name : string</text>
    <text x="42" y="442" font-size="11" fill="#374151">+ category : string</text>
    <text x="42" y="458" font-size="11" fill="#374151">+ price : int (unsigned)</text>
    <text x="42" y="474" font-size="11" fill="#374151">+ stock : int (unsigned)</text>
    <text x="42" y="490" font-size="11" fill="#374151">+ image_path : string (null)</text>
    <text x="42" y="506" font-size="11" fill="#374151">+ created_at : timestamp</text>
    <line x1="30" y1="514" x2="270" y2="514" stroke="#e5e7eb" stroke-width="1"/>
    <text x="42" y="530" font-size="11" fill="#0369a1">+ getImageUrlAttribute() : string</text>
    <text x="42" y="546" font-size="11" fill="#0369a1">+ hasImage() : bool</text>
    <text x="42" y="562" font-size="11" fill="#0369a1">+ saleItems() : HasMany</text>
  </g>

  <!-- ═══════════════════════════════════
       MODEL: Sale  (x=30, y=620)
  ═══════════════════════════════════ -->
  <g filter="url(#shadow)">
    <rect x="30" y="615" width="240" height="285" rx="10" fill="white" stroke="#d1fae5" stroke-width="1.5"/>
    <rect x="30" y="615" width="240" height="34" rx="10" fill="url(#model-grad)"/>
    <rect x="30" y="635" width="240" height="14" fill="url(#model-grad)"/>
    <text x="150" y="637" text-anchor="middle" font-size="13" font-weight="700" fill="white">Sale</text>
    <line x1="30" y1="649" x2="270" y2="649" stroke="#e5e7eb" stroke-width="1"/>
    <text x="42" y="665" font-size="11" fill="#374151">+ id : int (PK)</text>
    <text x="42" y="681" font-size="11" fill="#374151">+ user_id : int (FK, null)</text>
    <text x="42" y="697" font-size="11" fill="#374151">+ total : bigint</text>
    <text x="42" y="713" font-size="11" fill="#374151">+ paid : bigint</text>
    <text x="42" y="729" font-size="11" fill="#374151">+ source : string (kasir|online)</text>
    <text x="42" y="745" font-size="11" fill="#374151">+ status : string</text>
    <text x="42" y="761" font-size="11" fill="#374151">+ payment_method : string</text>
    <text x="42" y="777" font-size="11" fill="#374151">+ customer_name : string</text>
    <text x="42" y="793" font-size="11" fill="#374151">+ customer_phone : string</text>
    <text x="42" y="809" font-size="11" fill="#374151">+ customer_address : text</text>
    <line x1="30" y1="817" x2="270" y2="817" stroke="#e5e7eb" stroke-width="1"/>
    <text x="42" y="833" font-size="11" fill="#0369a1">+ user() : BelongsTo</text>
    <text x="42" y="849" font-size="11" fill="#0369a1">+ items() : HasMany</text>
    <text x="42" y="865" font-size="11" fill="#0369a1">+ scopeValid() : Builder</text>
    <text x="42" y="881" font-size="11" fill="#0369a1">+ getCashierNameAttribute() : string</text>
  </g>

  <!-- ═══════════════════════════════════
       MODEL: SaleItem  (x=300, y=720)
  ═══════════════════════════════════ -->
  <g filter="url(#shadow)">
    <rect x="300" y="715" width="210" height="250" rx="10" fill="white" stroke="#d1fae5" stroke-width="1.5"/>
    <rect x="300" y="715" width="210" height="34" rx="10" fill="url(#model-grad)"/>
    <rect x="300" y="735" width="210" height="14" fill="url(#model-grad)"/>
    <text x="405" y="737" text-anchor="middle" font-size="13" font-weight="700" fill="white">SaleItem</text>
    <line x1="300" y1="749" x2="510" y2="749" stroke="#e5e7eb" stroke-width="1"/>
    <text x="312" y="765" font-size="11" fill="#374151">+ id : int (PK)</text>
    <text x="312" y="781" font-size="11" fill="#374151">+ sale_id : int (FK)</text>
    <text x="312" y="797" font-size="11" fill="#374151">+ product_id : int (FK, null)</text>
    <text x="312" y="813" font-size="11" fill="#374151">+ product_name : string</text>
    <text x="312" y="829" font-size="11" fill="#374151">+ price : int</text>
    <text x="312" y="845" font-size="11" fill="#374151">+ qty : int</text>
    <text x="312" y="861" font-size="11" fill="#374151">+ subtotal : bigint</text>
    <text x="312" y="877" font-size="11" fill="#374151">+ created_at : timestamp</text>
    <line x1="300" y1="885" x2="510" y2="885" stroke="#e5e7eb" stroke-width="1"/>
    <text x="312" y="901" font-size="11" fill="#0369a1">+ sale() : BelongsTo</text>
    <text x="312" y="917" font-size="11" fill="#0369a1">+ product() : BelongsTo</text>
    <text x="312" y="933" font-size="11" fill="#0369a1">+ saleItems() : HasMany</text>
  </g>

  <!-- ═══════════════════════════════════
       CONTROLLERS
  ═══════════════════════════════════ -->
  <!-- DashboardController -->
  <g filter="url(#shadow)">
    <rect x="530" y="115" width="220" height="95" rx="10" fill="white" stroke="#cffafe" stroke-width="1.5"/>
    <rect x="530" y="115" width="220" height="34" rx="10" fill="url(#ctrl-grad)"/>
    <rect x="530" y="135" width="220" height="14" fill="url(#ctrl-grad)"/>
    <text x="640" y="137" text-anchor="middle" font-size="12" font-weight="700" fill="white">DashboardController</text>
    <line x1="530" y1="149" x2="750" y2="149" stroke="#e5e7eb" stroke-width="1"/>
    <text x="542" y="165" font-size="11" fill="#0369a1">+ index() : View</text>
    <text x="542" y="181" font-size="10" fill="#64748b">  ↳ revenue, transactions, sold,</text>
    <text x="542" y="195" font-size="10" fill="#64748b">    productCount, newOrders, low</text>
  </g>

  <!-- ProductController -->
  <g filter="url(#shadow)">
    <rect x="530" y="230" width="220" height="175" rx="10" fill="white" stroke="#cffafe" stroke-width="1.5"/>
    <rect x="530" y="230" width="220" height="34" rx="10" fill="url(#ctrl-grad)"/>
    <rect x="530" y="250" width="220" height="14" fill="url(#ctrl-grad)"/>
    <text x="640" y="252" text-anchor="middle" font-size="12" font-weight="700" fill="white">ProductController</text>
    <line x1="530" y1="264" x2="750" y2="264" stroke="#e5e7eb" stroke-width="1"/>
    <text x="542" y="280" font-size="11" fill="#0369a1">+ index(Request) : View</text>
    <text x="542" y="296" font-size="11" fill="#0369a1">+ create() : View</text>
    <text x="542" y="312" font-size="11" fill="#0369a1">+ store(Request) : Redirect</text>
    <text x="542" y="328" font-size="11" fill="#0369a1">+ edit(Product) : View</text>
    <text x="542" y="344" font-size="11" fill="#0369a1">+ update(Request, Product) : Redirect</text>
    <text x="542" y="360" font-size="11" fill="#0369a1">+ destroy(Product) : Redirect</text>
    <text x="542" y="376" font-size="11" fill="#7c3aed">- validated(Request) : array</text>
    <text x="542" y="392" font-size="10" fill="#64748b">  ↳ upload/hapus image via Storage</text>
  </g>

  <!-- SaleController -->
  <g filter="url(#shadow)">
    <rect x="530" y="425" width="220" height="145" rx="10" fill="white" stroke="#cffafe" stroke-width="1.5"/>
    <rect x="530" y="425" width="220" height="34" rx="10" fill="url(#ctrl-grad)"/>
    <rect x="530" y="445" width="220" height="14" fill="url(#ctrl-grad)"/>
    <text x="640" y="447" text-anchor="middle" font-size="12" font-weight="700" fill="white">SaleController</text>
    <line x1="530" y1="459" x2="750" y2="459" stroke="#e5e7eb" stroke-width="1"/>
    <text x="542" y="475" font-size="11" fill="#0369a1">+ index() : View</text>
    <text x="542" y="491" font-size="11" fill="#0369a1">+ create() : View</text>
    <text x="542" y="507" font-size="11" fill="#0369a1">+ store(Request) : Redirect</text>
    <text x="542" y="523" font-size="10" fill="#64748b">  ↳ DB::transaction, lockForUpdate</text>
    <text x="542" y="539" font-size="11" fill="#0369a1">+ show(Sale) : View</text>
    <text x="542" y="555" font-size="10" fill="#64748b">  ↳ cetak struk</text>
  </g>

  <!-- OrderController -->
  <g filter="url(#shadow)">
    <rect x="530" y="590" width="220" height="110" rx="10" fill="white" stroke="#cffafe" stroke-width="1.5"/>
    <rect x="530" y="590" width="220" height="34" rx="10" fill="url(#ctrl-grad)"/>
    <rect x="530" y="610" width="220" height="14" fill="url(#ctrl-grad)"/>
    <text x="640" y="612" text-anchor="middle" font-size="12" font-weight="700" fill="white">OrderController</text>
    <line x1="530" y1="624" x2="750" y2="624" stroke="#e5e7eb" stroke-width="1"/>
    <text x="542" y="640" font-size="11" fill="#0369a1">+ index(Request) : View</text>
    <text x="542" y="656" font-size="10" fill="#64748b">  ↳ filter by status, pagination</text>
    <text x="542" y="672" font-size="11" fill="#0369a1">+ update(Request, Sale) : Redirect</text>
    <text x="542" y="688" font-size="10" fill="#64748b">  ↳ restore stok jika dibatalkan</text>
  </g>

  <!-- ReportController -->
  <g filter="url(#shadow)">
    <rect x="530" y="720" width="220" height="120" rx="10" fill="white" stroke="#cffafe" stroke-width="1.5"/>
    <rect x="530" y="720" width="220" height="34" rx="10" fill="url(#ctrl-grad)"/>
    <rect x="530" y="740" width="220" height="14" fill="url(#ctrl-grad)"/>
    <text x="640" y="742" text-anchor="middle" font-size="12" font-weight="700" fill="white">ReportController</text>
    <line x1="530" y1="754" x2="750" y2="754" stroke="#e5e7eb" stroke-width="1"/>
    <text x="542" y="770" font-size="11" fill="#0369a1">+ index(Request) : View</text>
    <text x="542" y="786" font-size="11" fill="#0369a1">+ csv(Request) : StreamResponse</text>
    <text x="542" y="802" font-size="10" fill="#64748b">  ↳ unduh laporan CSV</text>
    <text x="542" y="818" font-size="11" fill="#7c3aed">- query(Request) : Builder</text>
    <text x="542" y="834" font-size="10" fill="#64748b">  ↳ filter from/to date</text>
  </g>

  <!-- ShopController -->
  <g filter="url(#shadow)">
    <rect x="770" y="115" width="230" height="280" rx="10" fill="white" stroke="#cffafe" stroke-width="1.5"/>
    <rect x="770" y="115" width="230" height="34" rx="10" fill="url(#ctrl-grad)"/>
    <rect x="770" y="135" width="230" height="14" fill="url(#ctrl-grad)"/>
    <text x="885" y="137" text-anchor="middle" font-size="12" font-weight="700" fill="white">ShopController</text>
    <line x1="770" y1="149" x2="1000" y2="149" stroke="#e5e7eb" stroke-width="1"/>
    <text x="782" y="165" font-size="11" fill="#0369a1">+ index(Request) : View</text>
    <text x="782" y="181" font-size="10" fill="#64748b">  ↳ katalog produk publik</text>
    <text x="782" y="197" font-size="11" fill="#0369a1">+ cart() : View</text>
    <text x="782" y="213" font-size="11" fill="#0369a1">+ add(Request, Product) : Redirect</text>
    <text x="782" y="229" font-size="11" fill="#0369a1">+ update(Request, Product) : Redirect</text>
    <text x="782" y="245" font-size="11" fill="#0369a1">+ remove(Product) : Redirect</text>
    <text x="782" y="261" font-size="11" fill="#0369a1">+ checkout() : View</text>
    <text x="782" y="277" font-size="11" fill="#0369a1">+ placeOrder(Request) : Redirect</text>
    <text x="782" y="293" font-size="10" fill="#64748b">  ↳ DB::transaction, lockForUpdate</text>
    <text x="782" y="309" font-size="11" fill="#0369a1">+ done(Sale) : View</text>
    <text x="782" y="325" font-size="10" fill="#64748b">  ↳ konfirmasi pesanan</text>
    <text x="782" y="341" font-size="11" fill="#7c3aed">- cartItems() : array</text>
    <text x="782" y="357" font-size="10" fill="#64748b">  ↳ session cart + subtotal</text>
    <text x="782" y="373" font-size="10" fill="#64748b">  ↳ sinkron stok realtime</text>
  </g>

  <!-- AuthController -->
  <g filter="url(#shadow)">
    <rect x="770" y="415" width="230" height="100" rx="10" fill="white" stroke="#cffafe" stroke-width="1.5"/>
    <rect x="770" y="415" width="230" height="34" rx="10" fill="url(#ctrl-grad)"/>
    <rect x="770" y="435" width="230" height="14" fill="url(#ctrl-grad)"/>
    <text x="885" y="437" text-anchor="middle" font-size="11" font-weight="700" fill="white">AuthenticatedSessionController</text>
    <line x1="770" y1="449" x2="1000" y2="449" stroke="#e5e7eb" stroke-width="1"/>
    <text x="782" y="465" font-size="11" fill="#0369a1">+ create() : View</text>
    <text x="782" y="481" font-size="11" fill="#0369a1">+ store(LoginRequest) : Redirect</text>
    <text x="782" y="497" font-size="11" fill="#0369a1">+ destroy(Request) : Redirect</text>
  </g>

  <!-- ProfileController -->
  <g filter="url(#shadow)">
    <rect x="770" y="535" width="230" height="95" rx="10" fill="white" stroke="#cffafe" stroke-width="1.5"/>
    <rect x="770" y="535" width="230" height="34" rx="10" fill="url(#ctrl-grad)"/>
    <rect x="770" y="555" width="230" height="14" fill="url(#ctrl-grad)"/>
    <text x="885" y="557" text-anchor="middle" font-size="12" font-weight="700" fill="white">ProfileController</text>
    <line x1="770" y1="569" x2="1000" y2="569" stroke="#e5e7eb" stroke-width="1"/>
    <text x="782" y="585" font-size="11" fill="#0369a1">+ edit(Request) : View</text>
    <text x="782" y="601" font-size="11" fill="#0369a1">+ update(ProfileUpdateRequest) : Redirect</text>
    <text x="782" y="617" font-size="11" fill="#0369a1">+ destroy(Request) : Redirect</text>
  </g>

  <!-- ═══════════════════════════════════
       MIDDLEWARE: RoleMiddleware
  ═══════════════════════════════════ -->
  <g filter="url(#shadow)">
    <rect x="1060" y="115" width="220" height="110" rx="10" fill="white" stroke="#ede9fe" stroke-width="1.5"/>
    <rect x="1060" y="115" width="220" height="34" rx="10" fill="url(#mw-grad)"/>
    <rect x="1060" y="135" width="220" height="14" fill="url(#mw-grad)"/>
    <text x="1170" y="137" text-anchor="middle" font-size="12" font-weight="700" fill="white">RoleMiddleware</text>
    <line x1="1060" y1="149" x2="1280" y2="149" stroke="#e5e7eb" stroke-width="1"/>
    <text x="1072" y="165" font-size="11" fill="#0369a1">+ handle(Request, Closure,</text>
    <text x="1072" y="181" font-size="11" fill="#0369a1">    ...roles) : Response</text>
    <text x="1072" y="201" font-size="10" fill="#64748b">  Akses: admin, kasir</text>
    <text x="1072" y="216" font-size="10" fill="#64748b">  Guard: products, orders,</text>
    <text x="1072" y="228" font-size="10" fill="#64748b">         reports</text>
  </g>

  <!-- ═══════════════════════════════════
       RELASI MODEL (panah solid)
  ═══════════════════════════════════ -->

  <!-- User 1 --→ * Sale (hasMany) -->
  <path d="M 150 340 L 150 615" stroke="#059669" stroke-width="2"
        fill="none" marker-end="url(#arrow)"/>
  <text x="155" y="490" font-size="10" fill="#059669" font-weight="600">1</text>
  <text x="155" y="610" font-size="10" fill="#059669" font-weight="600">0..*</text>
  <text x="158" y="530" font-size="10" fill="#059669">hasMany</text>

  <!-- Sale 1 --→ * SaleItem (hasMany) -->
  <path d="M 270 757 L 300 800" stroke="#059669" stroke-width="2"
        fill="none" marker-end="url(#arrow)"/>
  <text x="264" y="766" font-size="10" fill="#059669" font-weight="600">1</text>
  <text x="288" y="820" font-size="10" fill="#059669" font-weight="600">1..*</text>
  <text x="248" y="800" font-size="10" fill="#059669">hasMany</text>

  <!-- SaleItem → Product (belongsTo) -->
  <path d="M 370 715 L 200 592" stroke="#059669" stroke-width="2"
        fill="none" marker-end="url(#arrow)"/>
  <text x="240" y="700" font-size="10" fill="#059669" font-weight="600">0..*</text>
  <text x="180" y="610" font-size="10" fill="#059669" font-weight="600">1</text>
  <text x="237" y="668" font-size="10" fill="#059669">belongsTo</text>

  <!-- ═══════════════════════════════════
       DEPENDENCY CONTROLLER → MODEL (panah putus)
  ═══════════════════════════════════ -->

  <!-- Dashboard → Product, Sale, SaleItem -->
  <path d="M 530 155 L 270 230" stroke="#94a3b8" stroke-width="1.5" stroke-dasharray="6,3"
        fill="none" marker-end="url(#arrow-dash)"/>
  <path d="M 530 162 L 270 500" stroke="#94a3b8" stroke-width="1.5" stroke-dasharray="6,3"
        fill="none" marker-end="url(#arrow-dash)"/>

  <!-- Product Ctrl → Product -->
  <path d="M 530 330 L 270 460" stroke="#94a3b8" stroke-width="1.5" stroke-dasharray="6,3"
        fill="none" marker-end="url(#arrow-dash)"/>

  <!-- Sale Ctrl → Sale, Product -->
  <path d="M 530 490 L 270 720" stroke="#94a3b8" stroke-width="1.5" stroke-dasharray="6,3"
        fill="none" marker-end="url(#arrow-dash)"/>
  <path d="M 530 480 L 270 430" stroke="#94a3b8" stroke-width="1.5" stroke-dasharray="6,3"
        fill="none" marker-end="url(#arrow-dash)"/>

  <!-- Order Ctrl → Sale, Product -->
  <path d="M 530 640 L 270 760" stroke="#94a3b8" stroke-width="1.5" stroke-dasharray="6,3"
        fill="none" marker-end="url(#arrow-dash)"/>

  <!-- Report Ctrl → Sale -->
  <path d="M 530 770 L 270 790" stroke="#94a3b8" stroke-width="1.5" stroke-dasharray="6,3"
        fill="none" marker-end="url(#arrow-dash)"/>

  <!-- Shop Ctrl → Product, Sale, SaleItem -->
  <path d="M 770 250 L 270 430" stroke="#94a3b8" stroke-width="1.5" stroke-dasharray="6,3"
        fill="none" marker-end="url(#arrow-dash)"/>
  <path d="M 770 260 L 270 730" stroke="#94a3b8" stroke-width="1.5" stroke-dasharray="6,3"
        fill="none" marker-end="url(#arrow-dash)"/>

  <!-- Auth Ctrl → User -->
  <path d="M 770 455 L 270 200" stroke="#94a3b8" stroke-width="1.5" stroke-dasharray="6,3"
        fill="none" marker-end="url(#arrow-dash)"/>

  <!-- Profile Ctrl → User -->
  <path d="M 770 580 L 270 210" stroke="#94a3b8" stroke-width="1.5" stroke-dasharray="6,3"
        fill="none" marker-end="url(#arrow-dash)"/>

  <!-- Middleware → Controllers (guard) -->
  <path d="M 1060 170 L 750 280" stroke="#7c3aed" stroke-width="1.5" stroke-dasharray="4,3"
        fill="none" marker-end="url(#arrow-dash)"/>
  <path d="M 1060 175 L 750 485" stroke="#7c3aed" stroke-width="1.5" stroke-dasharray="4,3"
        fill="none" marker-end="url(#arrow-dash)"/>
  <path d="M 1060 180 L 750 760" stroke="#7c3aed" stroke-width="1.5" stroke-dasharray="4,3"
        fill="none" marker-end="url(#arrow-dash)"/>
  <text x="900" y="265" font-size="10" fill="#7c3aed" font-weight="600">«guard»</text>

</svg>'''

out_path = r'c:\Users\RPL-08\sistem-penjualan\class_diagram.svg'
with open(out_path, 'w', encoding='utf-8') as f:
    f.write(SVG)

print(f'SVG saved: {out_path}')
