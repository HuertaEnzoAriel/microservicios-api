{{-- resources/views/products/index.blade.php --}}
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Productos</title>
  <link rel="stylesheet" href="{{ asset('css/products.css') }}">
</head>
<body>
<div class="container">

  <div class="header">
    <div class="h-title">Productos</div>
    <div class="toolbar">
      <form id="filtersForm" method="GET" action="{{ url()->current() }}" class="filters">
        <div class="search">
          <input type="text" name="q" value="{{ request('q') }}" placeholder="Buscar por nombre o descripción…" />
          <span class="icon">🔎</span>
        </div>

        <select class="select" name="category_id" onchange="autoSubmit()">
          <option value="">Todas las categorías</option>
            @foreach($categories as $category)
              <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
        </select>

        <input class="input" type="number" step="0.01" name="min_price" value="{{ request('min_price') }}" placeholder="Precio mín." />
        <input class="input" type="number" step="0.01" name="max_price" value="{{ request('max_price') }}" placeholder="Precio máx." />

        <label class="check">
          <input type="checkbox" name="only_active" value="1" @checked(request('only_active')==='1') />
          Activos
        </label>
        <label class="check">
          <input type="checkbox" name="in_stock" value="1" @checked(request('in_stock')==='1') />
          En stock
        </label>

        <select class="select" name="sort" onchange="autoSubmit()">
          @php $sort = request('sort'); @endphp
          <option value="">Orden: nombre</option>
          <option value="newest"     @selected($sort==='newest')>Novedades</option>
          <option value="price_asc"  @selected($sort==='price_asc')>Precio ↑</option>
          <option value="price_desc" @selected($sort==='price_desc')>Precio ↓</option>
          <option value="rating"     @selected($sort==='rating')>Mejor puntaje</option>
        </select>

        <select class="select" name="per_page" onchange="autoSubmit()">
          @php $pp = (int) request('per_page',12); @endphp
          @foreach([8,12,16,24,32] as $n)
            <option value="{{ $n }}" @selected($pp===$n)>{{ $n }}/página</option>
          @endforeach
        </select>

        <button class="btn">Filtrar</button>
        <button type="button" class="btn ghost" onclick="clearFilters()">Limpiar</button>
      </form>

      <div class="view-toggle" role="tablist" aria-label="Vista">
        <button id="gridBtn" class="active" onclick="setView('grid')" aria-selected="true" title="Cuadrícula">⬛</button>
        <button id="listBtn" onclick="setView('list')" aria-selected="false" title="Lista">≣</button>
      </div>
    </div>
  </div>

  <div class="tags" id="activeTags"></div>

  @if(($products ?? collect())->count())
    @php $view = request()->cookie('view_mode','grid'); @endphp
    <div id="productList" class="grid {{ $view==='list' ? 'list-view' : 'grid-view' }}">
      @foreach($products as $product)
        @php
          $rating  = round($product->averageRating() ?? 0, 1);
          $percent = $rating * 20;
          $price   = number_format((float)$product->price, 2, ',', '.');
          $isOut   = (int)$product->stock <= 0;
          $inactive= !$product->is_active;
        @endphp
        <article class="card {{ $view==='list' ? 'list' : '' }}">
          <div class="thumb">
            <div class="badges">
              @if($isOut)<span class="badge err">Agotado</span>@endif
              @if($inactive)<span class="badge warn">Inactivo</span>@endif
              @if($product->weight)<span class="badge">{{ $product->weight }} kg</span>@endif
            </div>
            @if($product->image_url)
              <img alt="Imagen de {{ $product->name }}" src="{{ $product->image_url }}">
            @else
              <div style="color:#2c3a4a;font-weight:700">SIN IMAGEN</div>
            @endif
          </div>
          <div class="content">
            <div class="title">{{ $product->name }}</div>
            <div class="meta">
              @if($product->category) <span>📁 {{ $product->category->name }}</span>@endif
              <span class="small">Stock: {{ (int)$product->stock }}</span>
              <span class="small">{{ $inactive ? 'No visible' : 'Publicado' }}</span>
            </div>

            <div class="rating" aria-label="Puntaje {{ $rating }} de 5">
              <div class="stars" style="font-size:14px; line-height:1;">
                <div class="fill" style="width: {{ $percent }}%"></div>
              </div>
              <span class="count">{{ $rating }} · {{ $product->reviewsCount() }} reseñas</span>
            </div>

            <div class="price">$ {{ $price }}</div>
            @if($product->description)
              <div class="small">{{ \Illuminate\Support\Str::limit(strip_tags($product->description), 120) }}</div>
            @endif

            <div class="actions">
              <button class="btn" onclick="preview({{ $product->id }})">Ver</button>
              <button class="btn primary" {{ $isOut || $inactive ? 'disabled' : '' }} onclick="addToCart({{ $product->id }})">
                {{ $isOut ? 'Sin stock' : 'Añadir al carrito' }}
              </button>
            </div>
          </div>
        </article>
      @endforeach
    </div>

    {{-- Paginación personalizada --}}
    @if($products->hasPages())
      @php
        $current = $products->currentPage();
        $last    = $products->lastPage();
        $start   = max(1, $current - 2);
        $end     = min($last, $current + 2);
      @endphp
      <nav class="pager" aria-label="Paginación de productos">
        {{-- Prev --}}
        <a class="page prev {{ $products->onFirstPage() ? 'is-disabled' : '' }}"
           href="{{ $products->onFirstPage() ? '#' : $products->appends(request()->query())->previousPageUrl() }}"
           aria-label="Anterior" data-page-nav {{ $products->onFirstPage() ? 'aria-disabled=true tabindex=-1' : '' }}>
          ‹
        </a>

        {{-- 1 + elipsis si corresponde --}}
        @if($start > 1)
          <a class="page" href="{{ $products->appends(request()->query())->url(1) }}" data-page-nav>1</a>
          @if($start > 2)
            <span class="page gap" aria-hidden="true">…</span>
          @endif
        @endif

        {{-- Ventana de páginas --}}
        @for($i = $start; $i <= $end; $i++)
          <a class="page {{ $i === $current ? 'is-active' : '' }}"
             href="{{ $products->appends(request()->query())->url($i) }}"
             aria-label="Página {{ $i }}"
             aria-current="{{ $i === $current ? 'page' : 'false' }}"
             data-page-nav>
            {{ $i }}
          </a>
        @endfor

        {{-- elipsis + última si corresponde --}}
        @if($end < $last)
          @if($end < $last - 1)
            <span class="page gap" aria-hidden="true">…</span>
          @endif
          <a class="page" href="{{ $products->appends(request()->query())->url($last) }}" data-page-nav>{{ $last }}</a>
        @endif

        {{-- Next --}}
        <a class="page next {{ !$products->hasMorePages() ? 'is-disabled' : '' }}"
           href="{{ $products->hasMorePages() ? $products->appends(request()->query())->nextPageUrl() : '#' }}"
           aria-label="Siguiente" data-page-nav {{ !$products->hasMorePages() ? 'aria-disabled=true tabindex=-1' : '' }}>
          ›
        </a>
      </nav>

      <div class="pager-meta small">
        Mostrando {{ $products->firstItem() }}–{{ $products->lastItem() }} de {{ $products->total() }}
      </div>
    @endif
  @else
    <div class="empty">No se encontraron productos con los filtros actuales.</div>
  @endif

  <p class="small">Tip: evita N+1 con <code>with(['category','reviews'])</code> y si ordenás por rating usá <code>withAvg('reviews','rating')</code>.</p>
</div>

<script src="{{ asset('js/products.js') }}"></script>
</body>
</html>
