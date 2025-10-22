<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>{{ config('app.name', 'Laravel') }} — Hola Mundo</title>

  <style>
    :root{
      --bg1:#0f172a; --bg2:#1e293b; --glass:rgba(255,255,255,.06);
      --txt:#e2e8f0; --muted:#94a3b8; --accent1:#7c3aed; --accent2:#06b6d4;
      --ring: 0 0 0 10px rgba(124,58,237,.12);
    }
    *{box-sizing:border-box}
    html,body{height:100%}
    body{
      margin:0; color:var(--txt); font:500 16px/1.6 system-ui,-apple-system,Segoe UI,Roboto,Ubuntu;
      background: radial-gradient(1200px 800px at 10% 10%, #0b1225 0%, transparent 60%),
                  radial-gradient(900px 700px at 90% 90%, #0b1225 0%, transparent 60%),
                  linear-gradient(135deg, var(--bg1), var(--bg2));
      display:grid; place-items:center; padding:24px;
    }
    .wrap{
      width:100%; max-width:920px; position:relative;
    }
    .card{
      position:relative; overflow:hidden;
      background:linear-gradient(180deg, rgba(255,255,255,.08), rgba(255,255,255,.02));
      border:1px solid rgba(255,255,255,.08);
      box-shadow: 0 10px 40px rgba(0,0,0,.35);
      backdrop-filter: blur(10px);
      border-radius:24px; padding:40px;
    }
    .glow{
      position:absolute; inset:-30%; filter: blur(60px); opacity:.35; z-index:0;
      background:
        radial-gradient(350px 220px at 20% 20%, var(--accent1), transparent 60%),
        radial-gradient(350px 220px at 80% 60%, var(--accent2), transparent 60%);
      animation: float 12s ease-in-out infinite alternate;
    }
    @keyframes float { from{transform:translateY(-10px)} to{transform:translateY(10px)} }

    .hdr{ position:relative; z-index:1; display:flex; align-items:center; gap:14px; }
    .logo{
      width:42px; height:42px; display:grid; place-items:center; border-radius:12px;
      background: linear-gradient(135deg, var(--accent1), var(--accent2));
      color:white; font-weight:800;
      box-shadow: 0 8px 24px rgba(124,58,237,.45);
    }
    .app{ font-weight:700; letter-spacing:.2px; color:var(--txt); opacity:.9 }
    .muted{ color:var(--muted); font-weight:500; }

    .hero{
      position:relative; z-index:1; text-align:center; margin:34px 0 4px;
    }
    .title{
      font-size: clamp(40px, 7vw, 72px);
      line-height:1.05; margin:0 0 12px; font-weight:800; letter-spacing:-.02em;
      background: linear-gradient(90deg, #fff, #dbeafe 35%, #a5b4fc 70%, #67e8f9);
      -webkit-background-clip: text; background-clip: text; color: transparent;
      text-shadow: 0 0 24px rgba(103,232,249,.12);
    }
    .subtitle{ margin:0 auto; max-width:56ch; color:var(--muted) }

    .cta{
      position:relative; z-index:1; margin:28px auto 0; display:flex; gap:12px; justify-content:center; flex-wrap:wrap;
    }
    .btn{
      appearance:none; border:none; cursor:pointer; font-weight:700;
      padding:14px 18px; border-radius:14px; transition:.2s transform, .2s box-shadow, .2s opacity;
      background: linear-gradient(135deg, var(--accent1), var(--accent2)); color:#fff;
      box-shadow: 0 10px 24px rgba(6,182,212,.25);
    }
    .btn:hover{ transform: translateY(-1px); box-shadow: 0 12px 28px rgba(6,182,212,.32); }
    .btn:active{ transform: translateY(0); box-shadow: var(--ring); }

    .ghost{
      background:transparent; color:var(--txt); border:1px solid rgba(255,255,255,.14);
      box-shadow:none;
    }
    .meta{
      position:relative; z-index:1; margin-top:22px; text-align:center; font-size:14px; color:var(--muted)
    }

    /* Dark-mode friendly already. Small tweak for very small screens */
    @media (max-width:420px){ .card{ padding:26px } }
  </style>
</head>
<body>
  <div class="wrap">
    <div class="card">
      <div class="glow" aria-hidden="true"></div>

      <header class="hdr">
        <div class="logo">⚡</div>
        <div>
          <div class="app">{{ config('app.name', 'Laravel') }}</div>
          <div class="muted">Plantilla Blade moderna</div>
        </div>
      </header>

      <section class="hero">
        <h1 class="title">Hola, mundo</h1>
        <p class="subtitle">
          Bienvenido a tu primera vista en Blade. Esta página es liviana, accesible y lista para usar en Laravel sin configurar nada extra.
        </p>

        <div class="cta">
          <button class="btn" onclick="copyRoute()">Copiar ejemplo de ruta</button>
          <a class="btn ghost" href="{{ url('/') }}">Ir al inicio</a>
        </div>

        <p class="meta">Renderizado desde <code>resources/views/hola.blade.php</code></p>
      </section>
    </div>
  </div>

  <script>
    function copyRoute(){
      const txt =
`// routes/web.php
use Illuminate\\Support\\Facades\\Route;

Route::get('/hola', function () {
    return view('hola'); // hola.blade.php
});`;
      navigator.clipboard.writeText(txt).then(()=>{
        alert('Snippet de ruta copiado. Pegalo en routes/web.php');
      });
    }
  </script>
</body>
</html>
